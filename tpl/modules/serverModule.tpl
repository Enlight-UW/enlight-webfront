<script src="js/modules/serverModule.js"></script>

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
            Check on the Status page to see the current state of the fountain. Keep in mind that, if the native server were to fail (even in a forced idle state), the cRIO may or may not
            default to its built-in pattern after a certain timeout period.
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


<h3>Server State Information</h3>
<p>Information about the server like uptime and # packets receieved.</p>


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