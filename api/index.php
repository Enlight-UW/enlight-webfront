<?php

require 'constants.php';
require 'apiFunctions.php';

// Initialize Slim REST framework.
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

// Initialize Slim application.
$app = new \Slim\Slim();

// Set response headers to JSON (not using anything else here, so can do this
// instead of per-request).
$app->response->headers->set('Content-Type', 'application/json');

// Initialize database connection, we'll probably need it.
$db = new SQLite3('../' . $DB_FILENAME);
$db->busyTimeout(500);

// Create convenience JSON object out of request body. It might not exist.
$requestJSON = json_decode($app->request->getBody());

function failureJSON($msg) {
    die('{"success":false,"message":"' . $msg . '"}');
}

/**
 * Successful request - return a JSON encoded associative array of whatever
 * new information is relevant to the client.
 * 
 * @param type $assArray An associative array of new 
 */
function successJSON($assArray) {
    $successToken = array('success'=>'true');
    $ret = array_merge($assArray, $successToken);
    
    echo (json_encode($ret));
}


// Grab the API key from this request if it's a POST, and verify this API key.
if ($app->request->isPost()) {     
    //TODO: do these checks work?
    if (sizeof($requestJSON) === 0)
        failureJSON('No request body during post!');
    
    if (!isset($requestJSON->apikey))
        failureJSON('No API key in POST body');
    
    if (!verifyAPIKey($db, $requestJSON->apikey))
        failureJSON('Invalid API key!');
    
    // API key is known valid at this point, and this POST request can continue.
}


// Define routes. At this point, the key is already verified and only things
// like priority need to be checked.


$app->get('/', function() {
    echo 'API version 1A.';
      $db->close();
});

////////////////////////////////////////////////////////////////////////////////
// Authentication and control
////////////////////////////////////////////////////////////////////////////////

/**
 * Query the current control queue.
 */
$app->get('/control/query', function () use ($db) {
    $Q_QUERY_CONTROL = <<<stmt
        SELECT controllerID, acquire, ttl, priority, queuePosition, apikey
        FROM controlQueue
        -- WHERE (acquire + ttl) > strftime('%s','now')
stmt;

    $stmt = $db->prepare($Q_QUERY_CONTROL);
    $res = $stmt->execute();
    
    if ($res === FALSE)
        die($db->lastErrorMsg());

    echo rowsAsJSON($res);
    $db->close();
});

/**
 * Request control with this API key.
 */
$app->post('/control/request', function () use ($db, $requestJSON) {
    // Find last queue position in this priority - unless this key already has a
    // control queue entry, in which case abort because we don't want one API key
    // adding itself to the queue again before it expires. Note - the queue contains
    // a history of all control requests by virtue of how it is constructed, so
    // be sure to account only for still valid entries (expire time in the future).
    
    // This query will do a few things:
    // 1) Find our current priority
    // 2) Add us to the end of the queue 
    //    a) but only if we don't already have a prioriy entry
    //    b) and with an expiration time which is the requested time + latest expiration time in this priority queue
    // 3) once that happens, extract the controllerID and return it to the client
    
/*    $Q_REQUEST_CONTROL = <<<stmt
        INSERT INTO controlQueue (priority, acquire, ttl, queuePosition, apikey)
        VALUES
            (
                (SELECT priority FROM apikeys WHERE apikey=:apikey) AS pri,
                MAX((SELECT MAX(acquire) FROM controlQueue WHERE priority=pri) + :requestedLength UNION strftime('%s','now')),
                :requestedLength,
                (SELECT MAX(queuePosition) FROM controlQueue WHERE priority=pri) + 1,
                :apikey
            )
stmt;*/
    
    // temporary to just get testing, doesn't do what we said earlier.
    $Q_REQUEST_CONTROL = <<<stmt
            INSERT INTO controlQueue(priority, acquire, ttl, queuePosition, apiKey)
            VALUES
            ( ( SELECT priority FROM apikeys WHERE apikey=:apikey),
             strftime('%s', 'now'),
            :requestedLength,
            (SELECT MAX(queuePosition) FROM controlQueue) + 1,
            :apikey
            )
stmt;
    
    $Q_REQUEST_CONTROL2 = <<<stmt
            INSERT INTO controlQueue(priority, acquire, ttl, queuePosition, apiKey)
            VALUES
            ( (SELECT priority FROM apikeys WHERE apikey=:apikey),
                strftime('%s', 'now'),
                    3, 4, 'abc123')
stmt;
    
    $stmt = $db->prepare($Q_REQUEST_CONTROL2);
    $stmt->bindValue(':apikey', $requestJSON->apikey);
    $stmt->bindValue(':requestedLength', $requestJSON->requestedLength);
    $res = $stmt->execute();
    
   if ($res === FALSE)
        failureJSON($db->lastErrorMsg());
    
    // Ok, now just run a select and return the data.
    $Q_RETURN_DATA = <<<stmt
        SELECT acquire, ttl, priority, controllerID, queuePosition
        FROM controlQueue
        WHERE apikey=:apikey
stmt;

    $stmt2 = $db->prepare($Q_RETURN_DATA);
    $stmt2->bindValue(':apikey', $requestJSON->apikey);
    $res = $stmt2->execute();
    
    if ($res === FALSE)
        failureJSON($db->lastErrorMsg());
        
    successJSON($res->fetchArray());
    $db->close();
});

/**
 * Release any current controls.
 */
$app->post('/control/release', function() use ($db) {
    // Users can only release controllerIDs under their provided API key.
    // Releasing control will release it for ALL controllerIDs possessed by this
    // API key. Might make that more granular in the future.
    $Q_RELEASE_CONTROL = <<<stmt
            UPDATE controlQueue
            SET ttl = 0
            WHERE apikey=:apikey
stmt;
    
    $stmt = $db->prepare($Q_RELEASE_CONTROL);
    $stmt->bindValue(':apikey', $requestJSON->apikey);
    $res = $stmt->execute();
    if ($res === FALSE)
        failureJSON($db->lastErrorMsg());    
    successJSON([]);
      $db->close();
});


////////////////////////////////////////////////////////////////////////////////
// Fountain interaction
////////////////////////////////////////////////////////////////////////////////

/**
 * Query valve list.
 */
$app->get('/valves', function() use ($db) {
    $Q_QUERY_VALVES = <<<stmt
        SELECT ID, name, description, spraying, enabled
        FROM valves    
stmt;
    
    $stmt = $db->prepare($Q_QUERY_VALVES);
    $res = $stmt->execute();
    if ($res === FALSE)
        failureJSON($db->lastErrorMsg());
    
    echo rowsAsJSON($res);
      $db->close();
});

/**
 * Set all valve states at once with a bitmask. Only 'enabled' valves will be affected.
 */
$app->post('/valves', function() use ($db, $NUM_VALVES, $requestJSON) {
    if (!isset($requestJSON->bitmask))
        failureJSON('No bitmask provided.');
    
    $stmt = $db->prepare("BEGIN TRANSACTION");
    if ($stmt->execute() === FALSE)
        failureJSON($db->lastErrorMsg());
        
    for ($i = 1; $i <= $NUM_VALVES; $i++) {
        $Q_UPDATE_VALVE = <<<stmt
            UPDATE valves
            SET spraying=:spraying
            WHERE ID=:id AND enabled<>0
stmt;

        $stmt = $db->prepare($Q_UPDATE_VALVE);
        $stmt->bindValue(':id', $i);
        $stmt->bindValue(':spraying', (($requestJSON->bitmask) >> ($i - 1)) & 1);
        $res = $stmt->execute();
        
        if ($res === FALSE)
            failureJSON($db->lastErrorMsg());
    }
    
    $stmt = $db->prepare("COMMIT TRANSACTION");
    if ($stmt->execute() === FALSE)
        failureJSON($db->lastErrorMsg());
    
    successJSON([]);
      $db->close();
});

/**
 * Get info about a specific valve.
 */
$app->get('/valves/:id', function($valveID) use ($db) {
    $Q_QUERY_VALVE = <<<stmt
        SELECT ID, name, description, spraying, enabled
        FROM valves
        WHERE ID=:id
stmt;
    
    if (!isset($valveID))
        failureJSON('No valve ID requested!');
    
    $stmt = $db->prepare($Q_QUERY_VALVE);
    $stmt->bindValue(':id', $valveID);
    $res = $stmt->execute();
    
    if ($res === FALSE)
        failureJSON($db->lastErrorMsg());
    
    echo rowsAsJSON($res);
      $db->close();
});

/**
 * Set a specific valve.
 */
$app->post('/valves/:id', function($valveID) use ($db) {
    if (getControllingKey() !== $requestJSON->apikey)
        failureJSON('Not in control...');
        
    if (!isset($requestJSON->bitmask))
        failureJSON('No bitmask provided.');
    
    $Q_UPDATE_VALVE = <<<stmt
        UPDATE valves
        SET spraying=1
        WHERE ID=:id AND enabled<>0
stmt;

    $stmt = $db->prepare($Q_UPDATE_VALVE);
    $stmt->bindValue(':id', $valveID);
    $res = $stmt->execute();

    if ($res === FALSE)
        failureJSON($db->lastErrorMsg());

    successJSON([]);
      $db->close();
});

////////////////////////////////////////////////////////////////////////////////
// Patterns
////////////////////////////////////////////////////////////////////////////////

/**
 * Return a list of patterns known to the server.
 */
$app->get('/patterns', function() use ($db) {
    
    $Q_QUERY_PATTERNS = <<<stmt
            SELECT ID, name, active, description
            FROM patterns
            WHERE active<>0
stmt;
    
    $stmt = $db->prepare($Q_QUERY_PATTERNS);
    $res = $stmt->execute();
    
    if ($res === FALSE)
        failureJSON($db->lastErrorMsg());
    
    echo rowsAsJSON($res);
      $db->close();
});

/**
 * Tell the server to play a specific pattern.
 */
$app->post('/patterns/:id', function($patternID) use ($db) {
    if (getControllingKey() !== $requestJSON->apikey)
        failureJSON('Not in control...');
    
    //TODO implement setCurrent
    if (!isset($patternID))
        failureJSON('No patternID specified...');
    
    $Q_CLEAR_PATTERNS = <<<stmt
            UPDATE patterns
            SET active=0           
stmt;
    
    $stmt = $db->prepare($Q_CLEAR_PATTERNS);
    $res = $stmt->execute();
    
    if ($res === FALSE)
        failureJSON($db->lastErrorMsg());
    
    $Q_ENGAGE_PATTERN = <<<stmt
            UPDATE patterns
            SET active=1
            WHERE ID=:id and enabled<>0
stmt;
    
    $stmt = $db->prepare($Q_ENGAGE_PATTERN);
    $res = $stmt->execute();
    if ($res === FALSE)
        failureJSON($db->lastErrorMsg());
    
    successJSON([]);
      $db->close();
});

// Start Slim application.
$app->run();


//TODO: Logic for things like reading the current pattern/controller/valve states
// out and updating. Not sure on that implementation yet.
