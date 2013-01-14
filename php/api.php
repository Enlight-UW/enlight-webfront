<?php

/*
 * Project: CleanWebfront
 * File: api.php
 * Author: Alex Kersten
 * 
 * This file will take parameters from the user and pass them along to our C++
 * program, assuming the parameters are of the proper format. This is the
 * front-end for ALL API activity, either from the website, or from a mobile
 * device, or from our own programs (kiosk, etc.). This means the webserver will
 * need to be running in order for the fountain server to accept API commands.
 * 
 * The reasoning behind this is that it is easier to verify commands here.
 * 
 * Requests will be checked against their parameter count and format before
 * being passed to the fountain server via UDP.
 * 
 * See the Wiki documentation (once I write it).
 * 
 * Considerations: Please use the functions here when accessing this file from
 * the Webfront. Don't do a api.php?API_KEY=...
 */

/**
 * The master function responsible for creating text-based packets to the local
 * server.
 * 
 * @param int $opcode Opcode for this request. 4-byte unsigned integer.
 * @param string $payload What's wrong with a string based protocol anyway?
 */
function api_masterSend($opcode, $payload) {
    //UDP setup
    $server_ip = '127.0.0.1';
    $server_port = 11211;

    //The second argument here is the SMK, change it from the default (AF199...)
    //before deploying!
    //Don't include $payload in this pack - presumably it's been packed before
    //from whatever called this function and will be formatted accordingly -
    //just cat it to the end of the resulting data string.
    $data = "AF1993ADFE944E38FE8CED6E490D1BB16C6A20F7F36237753A2EAF5BF2503536" .
            pack("N", $opcode) . $payload;

    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_sendto($socket, $data, strlen($data), 0, $server_ip, $server_port);
    socket_close($socket);
}

/**
 * Sends a test message over UDP to the server which should appear on standard
 * out.
 * @param string $message String to send.
 */
function api_sendTestMessage($message) {
    //STDEcho opcode is 0x4
    api_masterSend(0x4, $message);
}

?>
