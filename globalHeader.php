<?php
/*
 * Project: CleanWebfront
 * File: globalHeader.php
 * Author: Alex Kersten
 * 
 * If all goes according to plan, this should be the only import needed in 99%
 * of files here. This automatically imports every relevant library for the
 * Webfront and provides the basic HTML layout as well.
 * 
 * Any additional PHP files that contain classes/namespaces that need to be
 * referenced by the project should be added to the list in this file as well.
 */

require "php/api.php";
require "php/imports.php";
require "php/auth.php";
require "php/features.php";
require "php/fountainState.php";
require "php/session.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link href="css/notInventedHere/bootstrap.min.css" rel="stylesheet" />
        <link href="css/webfront.css" rel="stylesheet" />
        <title>Máquina Webfront</title>
    </head>
    <body>
        <script src="js/notInventedHere/jquery-1.8.2.min.js"></script>
        <script src="js/notInventedHere/jquery.base64.min.js"></script>
        
        <script src="js/notInventedHere/bootstrap.min.js"></script>
        
        <script src="js/notInventedHere/knockout-2.2.1.js"></script>
        <script src="js/notInventedHere/knockout.mapping-latest.js"></script>

        <script src="js/viewModel.js"></script>
        <script src="js/core.js"></script>