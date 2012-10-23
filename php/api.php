<?php

/*
 * Project: CleanWebfront
 * File: api.php
 * Author: Alex Kersten
 * 
 * This file will take parameters from the user and pass them along to our C++
 * program, assuming the parameters are of the proper format. This is the
 * front-end for ALL API activity, either from the website, or from a mobile
 * device, or from our own programs (kiosk, etc.). This means the webserver will
 * need to be running in order for the fountain server to accept API commands.
 * 
 * The reasoning behind this is that it is easier to verify commands here.
 * 
 * Requests will be checked against their parameter count and format before
 * being passed to the fountain server via UDP.
 * 
 * See the Wiki documentation (once I write it).
 * 
 * Considerations: Please use the functions here when accessing this file from
 * the Webfront. Don't do a api.php?API_KEY=...
 */

?>
