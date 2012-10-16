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
        parent::__construct("Status");
    }

    function getInnerContent() {
        return
                '<p>This is the status page, where all relevant live statistics
           of the fountain and related software will be represented. These
           statistics are refreshed in real-time as the server finds out about
           changes, so what you\'re seeing here is a true reflection of the
           state of things, as far as the server is concerned. Not to say that
           things couldn\'t appear fine here yet still be broken, though.</p>
           
            <h3>Server Status</h3>
            <span class="label">Unknown/Down</span>
            <span class="label">Idle</span>
            <span class="label label-success">Default Pattern</span>
            <span class="label">Override</span>
            
            <h3>cRIO Sensors</h3>
            
            
            <span class="label label-warning">Manhole Cover</span>
            <span class="label label-warning">Spillway Water Level High</span>
            <span class="label label-important">Undocumented Malfunction/Communication Breakdown</span>













';
    }

}

?>
