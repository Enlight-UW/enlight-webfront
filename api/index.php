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

// Grab the API key from this request if it's a POST, and verify this API key.
if ($app->request->isPost()) {
    $body = $app->request->getBody();
    $bodyj = json_decode($body);
    
    //TODO: do these checks work?
    if (sizeof($bodyj) === 0)
        die('{"success":false,"message":"No request body during post!"}');
    
    if (!isset($bodyj[0]->apikey))
        die('{"success":false,"message":"No API key in POST body!"}');
    
    if (!verifyAPIKey($db, $bodyj[0]->apikey))
        die('{"success":false,"message":"Invalid API key!"}');
    
    // API key is known valid at this point, and this POST request can continue.
}

// Define routes. At this point, the key is already verified and only things like priority
// need to be checked.


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
   
});

/**
 * Release any current controls.
 */
$app->post('/control/release', function() {
    
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
        die($db->lastErrorMsg());
    
    rowsAsJSON($res);
});

// Start Slim application.
$app->run();
