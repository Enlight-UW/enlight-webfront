<?php

/*
 * Project: enlight-webfront
 * File: api-actions.php
 * Author: Alex Kersten
 * 
 * API features go in this file - this file should be the only one that calls
 * things in the core like apiMasterSend().
 */


/**
 * Sends a test message over UDP to the server which should appear on standard
 * out.
 * @param string $message String to send.
 */
function api_sendTestMessage($message) {
    //STDEcho opcode is 0x4
    api_masterSend(0x4, $message);
}


function api_setValveState($key, $state) {
    api_masterSend(0x5, $key . $state);
}

function api_setRestrictState($key, $state) { 
    api_masterSend(0x6, $key . $state);
}

?>
