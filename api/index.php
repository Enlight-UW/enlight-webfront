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
