<?php

/*
 * Project: CleanWebfront
 * File: logout.php
 * Author: Alex Kersten
 * 
 * Logs the user out by destroying the session.
 */

session_start();
session_destroy();
header('Location: index.php');
exit();
?>
