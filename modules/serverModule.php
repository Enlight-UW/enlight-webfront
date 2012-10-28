<?php

/*
 * Project: CleanWebfront
 * File: serverModule.php
 * Author: Alex Kersten
 * 
 * Module for server-related things like restarting the C++ server or rebooting
 * the actual machine.
 */

class serverModule extends module {

    function __construct() {
        parent::__construct("Server");
    }

    function getInnerContent() {
        return '
    <p>Here you can perform various operations on the server. These shouldn\'t really need to be used that often, but if something goes horribly wrong then these might come in handy.</p>
    <!-- TODO: Make these "buttons" ajax-y -->
    
    <dl class="dl-horizontal">
        <dt>
            <a href="#" class="btn btn-primary btn-small" id="TOGGLE_IDLE">Toggle Idleness</a>
        </dt>
        <dd>
            Toggle the server between Idle and Patterns (or current override). Useful for maintenance or to gently stop the fountain if wetness is momentarily unappreciated.
            Check on the Status page to see the current state of the fountain.
        </dd>
<br />

        <dt>
            <a href="#" class="btn btn-warning btn-small" id="INVALIDATE_API">Invalidate API Usages</a>
        </dt>
        <dd>
            Invalidates all current API sessions and returns control to the Default Pattern state. Useful if an app or service (or Webfront) is misbehaving and not releasing control of the
            fountain when it should. Any API users who want to control the fountain again will need to re-handshake the server.
        </dd>
<br />        

        <dt>
            <a href="#" class="btn btn-warning btn-small" id="RELAUNCH_SERVER">Relaunch Server</a>
        </dt>
        <dd>
            Restarts the underlying C++ server, which should cause it to re-establish a connection to the cRIO and may prove useful in diagnosing a low-level problem. The Webfront may become
            temporarily unavailable or glitchy until the server comes back up. 
        </dd>
<br />

<dt>
            <a href="#" class="btn btn-danger btn-small" id="REBOOT_SERVER">Reboot Server</a>
        </dt>
        <dd>
            Pressing this button is admitting defeat.
        </dd>
<br />
        
    </dl>









';
    }

}

?>
