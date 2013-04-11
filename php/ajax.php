<?php

/*
 * Project: CleanWebfront
 * File: ajax.php
 * Author: Alex Kersten
 * 
 * The main Ajax handler. If it ISN'T a module and it comes from the browser,
 * or requires our Webfront API key, not just Webfront validation,
 * handle it here. Otherwise, each of the module php files (in modules/) should
 * have its own code to check if it's being ajax-ed by the browser and handle
 * requests appropriately - just as a way to keep relevant code organized.
 */

//CHANGE THIS BEFORE PRODUCTION (C5CDB...)
$WEBFRONT_API_KEY = "C5CDB9175BD6C2D2675CC006A5C9192E2B0DC4D673CE3191FBD393A0D3B721AB";

/**
 * Returns the state of the fountain to the client in a JSON object.
 */
if (isset($_POST['updateState'])) {
    require "fountainState.php";
    require "api.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION['AUTHORIZED'])) {
        die("Not authorized.");
    }

    if ($_SESSION['fountainState']->doStateUpdate()) {
        echo $_SESSION['fountainState']->getState();
    } else {
        echo "{\"error\":true,\"errormessage\":\"The native server is not running.\"}";
    }
}




if (isset($_POST['valveState']) || isset($_POST['restrictState'])) {
    require "api.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION['AUTHORIZED'])) {
        die("Not authorized.");
    }

    if (isset($_POST['valveState'])) {
        api_setValveState($WEBFRONT_API_KEY, $_POST['valveState']);
    } else {
        api_setRestrictState($WEBFRONT_API_KEY, $_POST['restrictState']);
    }
}
?>
