<?php

/*
 * Project: CleanWebfront
 * File: auth.php
 * Author: Alex Kersten
 * 
 * Should be required by all PHP files in the project; checks the status of the
 * user's authentication and redirects them to the login page if it fails.
 * 
 * This file should take care of any necessary loading of profile information
 * into the session.
 */

session_start();

if (!isset($_SESSION['AUTHORIZED'])) {
    header('Location: index.php');
    exit();
}

?>
