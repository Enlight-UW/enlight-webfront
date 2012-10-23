<?php

/*
 * Project: CleanWebfront
 * File: startDatabase.php
 * Author: Alex Kersten
 * 
 * Include this file before making database requests, since the connection to
 * the DB seems to drop according to the "whenever PHP feels like it" rule.
 */

if (!isset($_SESSION)) {
    session_start();
}

//Password to connect to the database. Please don't put the actual password here
//and accidentally upload it to GitHub. The default (dummy) value is
//ydvCN3q8z7qUFc7X and this password should always be used in test.
$dbpw = "ydvCN3q8z7qUFc7X";

//User is maquina on database maquina on the default port (3306).
$_SESSION['db'] =
        new mysqli($_SESSION['mysql_host'], 'maquina', $dbpw, 'maquina');


if ($_SESSION['db']->connect_error) {
    unset($_SESSION['db']);
    die("Database connection error.");
}

//echo 'Success... ' . $_SESSION['db']->host_info . "\n";
?>
