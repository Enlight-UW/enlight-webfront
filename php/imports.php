<?php

/*
 * Project: CleanWebfront
 * File: importModules.php
 * Author: Alex Kersten
 * 
 * Please add all modules to this list so that we can access them from the scope
 * of webfront.php (this file gets required by it).
 */

//Note that these paths execute from globalHeader.php, which means they're
//relative to the working directory of *that* file, not from here.
require "modules/module.php";
require "modules/statusModule.php";
require "modules/serverModule.php";
require "modules/interactiveModule.php";
require "modules/todoModule.php";

?>
