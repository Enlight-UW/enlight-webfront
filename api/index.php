<?php

// Initialize Slim REST framework.
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

// Initialize Slim application.
$app = new \Slim\Slim();

// Set response headers to JSON (not using anything else here, so can do this
// instead of per-request).
$app->response->headers->set('Content-Type', 'application/json');

//
// Define routes.
//

////////////////////////////////////////////////////////////////////////////////
// Authentication and control
////////////////////////////////////////////////////////////////////////////////

/**
 * Query the current control queue.
 */
$app->get('/control/query', function () {
    
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


// Start Slim application.
$app->run();
