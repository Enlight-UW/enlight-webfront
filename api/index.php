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
    
    if (!isset($requestJSON[0]->apikey))
        failureJSON('No API key in POST body');
    
    if (!verifyAPIKey($db, $requestJSON[0]->apikey))
        failureJSON('Invalid API key!');
    
    // API key is known valid at this point, and this POST request can continue.
}


// Define routes. At this point, the key is already verified and only things
// like priority need to be checked.

////////////////////////////////////////////////////////////////////////////////
// Authentication and control
////////////////////////////////////////////////////////////////////////////////

/**
 * Query the current control queue.
 */
$app->get('/control/query', function () use ($db) {
    $Q_QUERY_CONTROL = <<<stmt
        SELECT controllerID, acquired, expires, priority, queuePosition
        FROM controlQueue
        WHERE expires > strftime('%s','now')
stmt;

    $stmt = $db->prepare($Q_QUERY_CONTROL);
    $res = $stmt->execute();
    if ($res === FALSE)
        die($db->lastErrorMsg());
    
    rowsAsJSON($res);
});

/**
 * Request control with this API key.
 */
$app->post('/control/request', function () {
    //TODO: find highest maximum queue position in this priority
    
    //TODO: and set us to one more, with a time of that one's expiry + our
    // requested time.
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
            SET expires = strftime('%s','now','-1 second')
            WHERE apikey=:apikey
stmt;
    
    $stmt = $db->prepare($Q_QUERY_VALVE);
    $stmt->bindValue(':apikey', $requestJSON[0]->apikey);
    $res = $stmt->execute();
    if ($res === FALSE)
        failureJSON($db->lastErrorMsg());    
    successJSON([]);
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
    
    rowsAsJSON($res);
});

/**
 * Set all valve states at once with a bitmask.
 */
$app->post('/valves', function() use ($db) {
   // TODO
          
stmt;
});

/**
 * Get info about a specific valve.
 */
$app->get('/valves/:id', function() use ($db, $app) {
    $Q_QUERY_VALVE = <<<stmt
        SELECT ID, name, description, spraying, enabled
        FROM valves
        WHERE ID=:id
stmt;
    
    if (!isset($requestJSON[0]->id))
        failureJSON('No valve ID requested!');
    
    $stmt = $db->prepare($Q_QUERY_VALVE);
    $stmt->bindValue(':id', $requestJSON[0]->id);
    $res = $stmt->execute();
    
    if ($res === FALSE)
        die($db->lastErrorMsg());
    
    rowsAsJSON($res);
});

/**
 * Set a specific valve.
 */
$app->post('/valves/:id', function() use ($db) {
    $Q_QUERY_VALVE = <<<stmt
        SELECT ID, name, description, spraying, enabled
        FROM valves
        WHERE ID=:id
stmt;
    
    if (!isset($requestJSON[0]->id))
        failureJSON('No valve ID requested!');
    
    $stmt = $db->prepare($Q_QUERY_VALVE);
    $stmt->bindValue(':id', $requestJSON[0]->id);
    $res = $stmt->execute();
    
    if ($res === FALSE)
        die($db->lastErrorMsg());
    
    rowsAsJSON($res);
});

////////////////////////////////////////////////////////////////////////////////
// Patterns
////////////////////////////////////////////////////////////////////////////////

/**
 * Return a list of patterns known to the server.
 */
$app->get('/patterns', function() use ($db) {
    //TODO
});

/**
 * Tell the server to play a specific pattern.
 */
$app->post('/patterns/:id', function() use ($db) {
    //TODO
});

// Start Slim application.
$app->run();
