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
 */

require "php/startDatabase.php";

//Check if module objects have been created yet. If not, make them.
if (!isset($_SESSION['modules'])) {
    $_SESSION['modules'] = array(new statusModule(), new serverModule(),
        new interactiveModule(), new todoModule());

    //Default module is the first one.
    $_SESSION['currentModule'] = 0;
}

?>
