<?php

/*
 * Project: CleanWebfront
 * File: ajax.php
 * Author: Alex Kersten
 * 
 * The main Ajax handler. If it ISN'T a module and it comes from the browser,
 * handle it here. Otherwise, each of the module php files (in modules/) should
 * have its own code to check if it's being ajax-ed by the browser and handle
 * requests appropriately - just as a way to keep relevant code organized.
 * 
 * Pretty much all that should be here is the callforward for the clientside
 * AJAX which requests state updates, and this returns the current state of the
 * fountain.
 */

if (isset($_POST['updateState'])) {
    require "fountainState.php";
    require "api.php";

    require "startDatabase.php";

    if (!isset($_SESSION['AUTHORIZED'])) {
        die("Not authorized.");
    }

    if ($_SESSION['fountainState']->doStateUpdate()) {
        echo $_SESSION['fountainState']->getState();
    }
}
?>
