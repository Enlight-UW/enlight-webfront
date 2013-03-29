<?php

/*
 * Project: CleanWebfront
 * File: session.php
 * Author: Alex Kersten
 * 
 * This session file will take care of everything session-related like making
 * sure the module objects are initialized once (and only once) per session, as
 * well as documenting session variables that are used.
 * 
 * This should be included in the globalHeader file. Note that the actual
 * session_start() happens in auth.php for authentication reasons, so the order
 * of imports in globalHeader matters.
 */


/**
 * Session variables:
 * 'AUTHORIZED'     Set if the user has passed authorization. All relevant
 *                  checks on this happen via auth.php which is included in
 *                  every file via globalHeaders.php, so you should leave this
 *                  alone. Unset in the logout.php script.
 * 'USERNAME'       Un-escaped string containing the user's login name.
 * 'modules'        An array of module objects to be used on the Webfront.
 * 'db'             Database MySQLi object.
 * 'mysql_host'     The hostname of the MySQL server. Usually localhost but can
 *                  change if need be - just modify it below.
 * 
 * 'fountainState'  State object representing how things should be presented by
 *                  the Webfront interface. Updated every x ms by clientside
 *                  Javascript trigger (Javascript hints PHP to request new
 *                  state from C++).
 */
/**
 * Some default variables here that we should set. These probably will only
 * change based on deployment so we'll put them here in a convenient place. 
 */
$_SESSION['mysql_host'] = "localhost";

require "php/startDatabase.php";

//Check if module objects have been created yet. If not, make them. The ordering
//here will affect the order in which these appear on the navbar.
if (!isset($_SESSION['modules'])) {

    $_SESSION['modules'] = array(new statusModule(), new todoModule(),
        new patternsModule(), new serverModule(),
        new adminModule());

    //Default module is the first one.
    $_SESSION['currentModule'] = 0;
}

if (!isset($_SESSION['fountainState'])) {
    $_SESSION['fountainState'] = new fountainState();
}
?>
