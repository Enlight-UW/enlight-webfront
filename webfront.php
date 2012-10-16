<?php

/*
 * Project: CleanWebfront
 * File: webfront.php
 * Author: Alex Kersten
 * 
 * The "index" page of Webfront. Here will be all of the options available to
 * the user, with some information about the server as well. These options will
 * be accessible through a navbar at the top of the page which conveniently
 * hides irrelevant things until the user selects that one.
 * 
 * This file shouldn't need to change much (that's the power of modularity!)
 */

require "globalHeader.php";








require "php/navbar.php";



//Generate content from modules.
for ($i = 0; $i < sizeof($_SESSION['modules']); $i++) {
    echo "<div class=\"well\" id=\"module_" . $i . "\" style=\"display: " .
    ($i == 0 ? "block" : "none" ) . "\">" .
    $_SESSION['modules'][$i]->getInnerContent() . "</div>";
}








require "globalFooter.php";
?>