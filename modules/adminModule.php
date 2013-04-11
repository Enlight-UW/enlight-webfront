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
    require "api.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION['AUTHORIZED'])) {
        die("Not authorized.");
    }

    //We'll generate the actual API key here and pass it through
    $generatedKey = "i'm a test API key";
    api_generateApiKeytuple($generatedkey, $_POST['APIGenUser'], $_POST['APIGenPrioriy']);
}

class adminModule extends module {

    function __construct() {
        parent::__construct("<i class=\"icon-wrench\"></i> Admin", "adminModule");
    }

}

?>
