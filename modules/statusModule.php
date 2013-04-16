<?php

/*
 * Project: CleanWebfront
 * File: statusModule.php
 * Author: Alex Kersten
 *
 * The status module is the default module that users will see on the main page
 * of the Webfront. It will contain sensor information and jet activity
 * indicators and server status indicators.
 */

class statusModule extends module {

    function __construct() {
        parent::__construct("<i class=\"icon-globe\"></i> Status", "statusModule");
    }


}

?>
