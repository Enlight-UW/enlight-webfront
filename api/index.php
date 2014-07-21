<?php

require '../constants.php';

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

// Will move these functions somewhere else eventually.
function verifyAPIKey($key) use ($db) {
    $Q_QUERY_API_KEY_COUNT = <<<stmt
        SELECT COUNT(*) as count FROM apikeys
        WHERE apikey=:key
stmt;

    $stmt = $db->prepare($Q_QUERY_API_KEY_COUNT);
    $stmt->bindValue(':key', $key, SQLITE3_STRING)
    $res = $stmt->execute();

    //TODO: Error check
    
    // If there are any returned, it's a valid key
    $r = $res->fetchArray();
    return $r['count'] > 0;
}

function getAPIKeyPriority($key) use ($db) {
    if (!verifyAPIKey($key))
        return 0;

    $Q_QUERY_API_KEY_PRIORITY = <<<stmt
        SELECT priority FROM apikeys
        WHERE apikey=:key
stmt;

    $stmt = $db->prepare($Q_QUERY_API_KEY_PRIORITY);
    $stmt->bindValue(':key', $key, SQLITE3_STRING)
    //TODO: Error check
    $res = $stmt->execute();
    
    $r = $res->fetchArray();

    return $r['priority'];
    
}

///////////////////////////////////////////////////////

// Define routes.

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
    
    while ($row = $res->fetchArray())
            print_r($row);
    
});

/**
 * Request control with this API key.
 */
$app->post('/control/request', function () {
   
});

/**
 * Release any current controls.
 */
$app->post('/control/release', function() {
    
});


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
        die($db->lastErrorMsg());
    
    echo '[';
    while ($row = $res->fetchArray())
            echo json_encode($row) . ',';
    echo ']';
});

// Start Slim application.
$app->run();
