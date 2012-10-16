<?php

/*
 * Project: CleanWebfront
 * File: startSessionAndDatabase.java (Expression package is undefined on line 5, column 23 in Templates/Licenses/license-default.txt.)
 * Author: Alex Kersten
 * 
 * Since PHP sucks and doesn't keep database connections open, just #require this
 * file before doing any database access (especially in ajax-triggered methods)
 * to remind PHP to re-open the DB connection. Yes, we do store the db pass in
 * a session variable, problem? That seems to be the only thing PHP likes to
 * hang on to.
 */

if (!isset($_SESSION)) {
    session_start();
}

//Attempt database connection.
//Read the password from our hidden file.
$dbpw = "";

//If this is already set, it's likely we're in a directory that won't be friendly
//to us using this path.
if (!isset($_SESSION['dbpass'])) {
    $pwFile = file("SHADOW/MYSQL") or die("Password missing");

    foreach ($pwFile as $line) {
        $dbpw = $line;
        break;
    }

    $_SESSION['dbpass'] = $dbpw;
}



//User is maquina on database maquina on the default port (3306).
//TODO: Before production, change this value to localhost! Don't need it to be
//trying to access the test server or anything.
$_SESSION['db'] = new mysqli('mysql.dividebyxero.com', 'maquina', $_SESSION['dbpass'],
                'maquina');


if ($_SESSION['db']->connect_error) {
    unset($_SESSION['db']);
    die("Database connection error.");
}

//echo 'Success... ' . $_SESSION['db']->host_info . "\n";
?>
