<?php

/*
 * Project: enlight-webfront
 * File: hwbuffer.php
 * Author: Alex Kersten
 * 
 * Hardware buffer test to see if we can create and bind to a socket, send
 * something to it, and then call recvfrom to get the message we received (in
 * that order).
 */

function api_masterSend($payload) {
    //UDP setup
    $server_ip = '127.0.0.1';
    $server_port = 11211;

    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_sendto($socket, $payload, strlen($payload), 0, $server_ip, $server_port);
}

echo "Binding to socket 11911...<br />";





$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
if (socket_bind($socket, '127.0.0.1', 11911) == FALSE) {
    //Someone else is already listening. Guess we get to try again later
    echo "FAILURE - ALREADY BOUND";
    return;
}

echo "Sleeping...<br />";
sleep(3);


//Send a test packet with the dummy SMK and echo request.
$testPacket = pack("AN",
        "AF1993ADFE944E38FE8CED6E490D1BB16C6A20F7F36237753A2EAF5BF2503536",
        2) . "Test of echo capabilities!";
api_masterSend($testPacket);





$from = '';
$port = 0;
socket_recvfrom($socket, $buf, 12, 0, $from, $port);

socket_close($socket);

echo "RECEIVED: " . $buf;
?>
