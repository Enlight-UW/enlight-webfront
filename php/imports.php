<?php

/*
 * Project: CleanWebfront
 * File: importModules.php
 * Author: Alex Kersten
 * 
 * Please add all modules to this list so that we can access them from the scope
 * of webfront.php (this file gets required by it).
 * 
 * Don't add this file to anything other than globalHeader.php! It takes care of
 * everything for you, if you just add all your custom imports here with
 * relative paths from the document root.
 * 
 * Therefore, the paths here are relative to the document root, since they
 * execute from globalHeader.php via webfront.php (which is in /).
 */

//We don't need to add all the modules here manually - anything in the /modules
//directory gets automatically required.
//If other modules depend on it though, manually add it here. Can't be
//guaranteed module.php will get required first (in fact it's not likely) so
//force it here.
require "modules/module.php";

foreach (glob("modules/*.php") as $f) {
    //Since we'll hit module.php and any others we've manually added above twice
    //just require_once here. Normally I'm a fan of require just by itself since
    //it lets you know if you're accidentally running a file twice.
    require_once $f;
}

?>
