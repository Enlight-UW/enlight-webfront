<?php
/*
 * Project: CleanWebfront
 * File: act.php
 * Author: Alex Kersten
 * 
 * Ajax handler for API requests. GET parameters are:
 * * key - the API key assigned to the service
 * * request - the name of the request to invoke
 * * any additional parameters can be specified per request type and will be
 *   documented elsewhere...
 * 
 * The way priority will be enforceed is the following model:
 *  - For a request to be serviced by the native server, it will be prefixed
 *    with a service key (API key).
 *  - Service keys with higher priority will automatically take control, but
 *    only if their opcode action requires it (so the Webfront status update
 *    requests don't lock out anything else). Actually, it might be a good idea
 *    to have a request/relinquish control opcode.
 * 
 */

require "api.php";

?>
