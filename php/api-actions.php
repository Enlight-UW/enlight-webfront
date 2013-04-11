<?php

/*
 * Project: enlight-webfront
 * File: api-actions.php
 * Author: Alex Kersten
 * 
 * API features go in this file - this file should be the only one that calls
 * things in the core like apiMasterSend().
 * 
 * Some actions require a $key parameter, these are the ones that should be
 * available to the 'public' API - others are just accessible through the 
 * Webfront interface.
 * 
 * See the GitHub Wiki for documentation on what the opcodes mean (they should
 * correspond to the function they're in, but in case you're curious).
 */

/**
 * Sends a test message over UDP to the server which should appear on standard
 * out.
 * @param string $message String to send.
 */
function api_sendTestMessage($message) {
    api_masterSend(0x4, $message);
}

function api_generateApiKeytuple($generatedkey, $user, $priority) {
    if (!is_numeric($priority)) {
        return;
    }
}

////////////////////////////////////////////////////////////////////////////////
// "Public" API calls below, which require an API key to invoke (ones above are
// just Webfront-only or observers).
////////////////////////////////////////////////////////////////////////////////

function api_setValveState($key, $state) {
    api_masterSend(0x5, $key . $state);
}

function api_setRestrictState($key, $state) {
    api_masterSend(0x6, $key . $state);
}

?>
