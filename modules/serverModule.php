<?php

/*
 * Project: CleanWebfront
 * File: serverModule.php
 * Author: Alex Kersten
 * 
 * Module for server-related things like restarting the C++ server or rebooting
 * the actual machine.
 */

if (isset($_POST['ajax_testUDPMessage']) || isset($_GET['ajax_testUDPMessage'])) {
    //Even though we don't use the database in this callback, include this file
    //because it allows for the session to be resumed.
    require "../php/startDatabase.php";
    
    

    if (!isset($_SESSION['AUTHORIZED'])) {
        die("Not authorized.");
    }
    
    echo 'WORKING?';

    require "../php/api.php"; //Include API functions in this scope.
    api_sendTestMessage(base64_decode($_POST['ajax_testUDPMessage']));

    
    echo 'WORKING';
    
    exit(0);
}

class serverModule extends module {

    function __construct() {
        parent::__construct("Server");
    }

    function getInnerContent() {
        return '<script src="js/modules/serverModule.js"></script>
            
<h2>Get Personal</h2>
    <p>Here you can perform various operations on the server. These shouldn\'t really need to be used that often, but if something goes horribly wrong then these might come in handy.</p>
    <!-- TODO: Make these "buttons" ajax-y -->
    <div class="well">
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
            Restarts the underlying C++ server, which may prove useful in diagnosing a low-level problem. The Webfront may become
            temporarily unavailable or unstable until the server comes back up. 
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
    </div>

    
    <h3>Manual UDP Test</h3>
    <div class="well">
        <p>
            For science you can send your own UDP test packets to the server. If
            it\'s configured properly, whatever you type here should appear in
            stdout.
        </p>
      
        <input type="text" id="UDPTestInput" />
        <br />
        <a href="#" class="btn btn-primary btn-small" id="SEND_UDP_TEST"
                onclick="sendTestUDPMessage(); return false;">
            Send
        </a>
        <br />
        <h4>History</h4>
        <p id="UDPTestInputList">
            ---
        </p>
       
    </div>
';
    }

}

?>
