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
                '<h2>World View</h2>
                    <p>This is the status page, where all relevant live statistics
           of the fountain and related software will be represented. These
           statistics are refreshed in real-time as the server finds out about
           changes, so what you\'re seeing here is a true reflection of the
           state of things, as far as the Webfront is concerned. Not to say that
           things couldn\'t seem fine here yet still be broken, though.</p>
           <div class="well">
            <dl class="dl-horizontal">
                <dt>Server State</dt>
                <dd>
                    <span class="label">Local Server Unreachable</span>
                    <span class="label">Local Server in Error State</span>
                    <span class="label">cRIO Unreachable</span>
                    <span class="label">cRIO in Error State</span><br />
                    <span class="label">Idle</span><br />
                    <span class="label label-success">Default Pattern</span>
                    <span class="label">Pattern:</span>
                    <span class="label">API Override:</span>
                </dd>
                <br />
                <dt>cRIO Sensors</dt>
                <dd>
                    <span class="label label-warning">Manhole Cover</span><br />
                    <span class="label label-important">Spillway Water Level</span><br />
                </dd>
            </dl>
          </div>
';
    }

}

?>
