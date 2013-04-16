<?php

/*
 * Project: CleanWebfront
 * File: act.php
 * Author: Alex Kersten
 * 
 * Ajax handler for API requests. GET parameters are:
 * * key - the API key assigned to the service
 * * request - the name of the request to invoke
 * * any additional parameters can be specified per request type and will be
 *   documented elsewhere...
 * 
 * The way priority will be enforceed is the following model:
 *  - For a request to be serviced by the native server, it will be prefixed
 *    with a service key (API key).
 *  - Service keys with higher priority will automatically take control, but
 *    only if their opcode action requires it (so the Webfront status update
 *    requests don't lock out anything else). Actually, it might be a good idea
 *    to have a request/relinquish control opcode.
 * 
 */

/**
 * The valve designator parameters will be shared between many requests - this
 * function will generate a bitmask of the active ones in any particular request. 
 */
function generateAffectedBitmaskFromValveParameters() {
    $mask = 0;

    if ($_POST['V1']) {
        $mask += 1;
    }
    if ($_POST['V2']) {
        $mask += 2;
    }
    if ($_POST['V3']) {
        $mask += 4;
    }
    if ($_POST['V4']) {
        $mask += 8;
    }
    if ($_POST['V5']) {
        $mask += 16;
    }
    if ($_POST['V6']) {
        $mask += 32;
    }
    if ($_POST['V7']) {
        $mask += 64;
    }
    if ($_POST['V8']) {
        $mask += 128;
    }
    if ($_POST['V9']) {
        $mask += 256;
    }
    if ($_POST['V10']) {
        $mask += 512;
    }
    if ($_POST['VC']) {
        $mask += 1024;
    }
    if ($_POST['VR']) {
        $mask += 2048;
    }


    if ($_POST['H1']) {
        $mask += 4096;
    }
    if ($_POST['H2']) {
        $mask += 8192;
    }
    if ($_POST['H3']) {
        $mask += 16384;
    }
    if ($_POST['H4']) {
        $mask += 32768;
    }
    if ($_POST['H5']) {
        $mask += 65536;
    }
    if ($_POST['H6']) {
        $mask += 131072;
    }
    if ($_POST['H7']) {
        $mask += 262144;
    }
    if ($_POST['H8']) {
        $mask += 524288;
    }
    if ($_POST['H9']) {
        $mask += 1048576;
    }
    if ($_POST['H10']) {
        $mask += 2097152;
    }
    if ($_POST['HC']) {
        $mask += 4194304;
    }
    if ($_POST['HR']) {
        $mask += 8388608;
    }

    return $mask;
}

require "api.php";

$fakeKey = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";

//FIXME: MVP implementation! Doesn't check the keys or anything, just does stuff
//because we just need things to work for expo right now.
if (!isset($_POST['request'])) {
    echo '{success:false;}';
    exit(0);
}

switch ($_POST['request']) {
    case 'setValveState':
        api_setValveState($fakeKey, generateAffectedBitmaskFromValveParameters());
        break;
    case 'toggleValveState':
        api_toggleValveState($fakeKey, generateAffectedBitmaskFromValveParameters());
        break;
}

echo '{success:true;}';
?>
