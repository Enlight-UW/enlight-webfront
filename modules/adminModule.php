<?php

/*
 * Project: enlight-webfront
 * File: adminModule.php
 * Author: Alex Kersten
 * 
 * This module will handle administrative details of the Webfront. Not sure
 * quite what we want to be on this page but we'll see.
 */

if (isset($_POST['APIGenUser']) && isset($_POST['APIGenPriority'])) {
    require "../php/api.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION['AUTHORIZED'])) {
        die("Not authorized.");
    }

    if (strlen($_POST['APIGenUser']) === 0
            || strlen($_POST['APIGenPriority']) === 0) {
        die("Argument error.");
    }

    $randomness = rand() . hash("sha256", $_POST['APIGenUser'] . $_POST['APIGenPriority']);
    api_generateApiKeytuple($randomness, $_POST['APIGenUser'], $_POST['APIGenPriority']);

    exit(0);
}

if (isset($_POST['updateAPIKeyList'])) {
    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION['AUTHORIZED'])) {
        die("Not authorized.");
    }

    $response = '{"apiItems":[';

    foreach (file("../SHADOW/ApiKeys") as $line) {
        //Parse the JSON out of the line, the format is key:user:priority
        $vals = explode(':', $line);

        if (count($vals) != 3) {
            //Probably an empty row
            continue;
        }

        $response .= '{"apiKeyItem": "' . $vals[0] . '", "apiNameItem": "' . $vals[1] . '", "apiPriorityItem": ' . $vals[2] . '},';
    }

    //Chop off last comma from JSON object array
    $response = substr($response, 0, strlen($response) - 1);

    $response .= ']}';
    echo $response;

    exit(0);
}

class adminModule extends module {

    function __construct() {
        parent::__construct("<i class=\"icon-wrench\"></i> Admin", "adminModule");
    }

}

?>
