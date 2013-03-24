<?php

/*
 * Project: enlight-webfront
 * File: fountainState.php
 * Author: Alex Kersten
 * 
 * Object that handles state-tracking of the fountain. Created for each user and
 * will represent what that user sees as far as the Webfront is concerned.
 * 
 * Upon creation of this object and upon any request from the client, this
 * object's state is updated by opening a UDP receiving socket and requesting
 * the fountain server to send its state information over that connection.
 * 
 * But Alex, what happens if two+ sessions request an update at the same time?
 * Well... We just fail to bind the socket in this situation - but that's okay,
 * because due to the inevitable variance in the clientside timing of update
 * requests and network latency, the client will almost surely get updated the
 * next time it requests an update, considering the actual updates themselves
 * take < 1ms (localhost communication here between C++ and PHP) and hitting the
 * unfortunate overlap window multiple times is statistically impossible for
 * small numbers of clients. 
 * 
 * Obviously, this would be really bad practice if there were ever going to be
 * many people using the Webfront at once - you'd want to use a proper transport
 * layer between the C++ server and the Webfront, but for our small scale this
 * is excellent. I'd estimate it starts to choke around 500-1000 simultaneous
 * users, but considering that our scale is about 1 or 2 concurrent users we're
 * perfectly safe.
 */

class fountainState {

    private $latestState = array();

    function doStateUpdate() {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);


        if (socket_bind($socket, '127.0.0.1', 11911) == FALSE) {
            //Someone else is already listening. Guess we get to try again later
            return;
        }

        //If we're bound, we can tell the server to send information now, and
        //we'll read it from the "hardware" buffer (probably).
        //Form and send the state update request packet
        api_masterSend(3, "");


        //Receive state update datagram

        $from = '';
        $port = 0;
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec'=>1, 'usec'=>0));
        socket_recvfrom($socket, $buf, 1024, 0, $from, $port);

        socket_close($socket);


        //This buffer is one big string filled with <Name>Value</> "XML" text.
        //We need to parse each one of these out into an associative array so
        //that they can be easily accessed by the rest of the code.
        //First, separate all the key-value pairs.
        $pairs = explode("</>", $buf);

        foreach ($pairs as $pair) {
            //This looks like <Name>Value, so isolate the name part and the
            //value part...
            $isolator = explode(">", $pair);

            if (count($isolator) != 2) {
                //End of array or malformed keypair
                continue;
            }

            //isolator[0] contains "<Name" and isolator[1] has the value. Cut
            //the < off of the first bit and add it to our associative array.
            $key = substr($isolator[0], 1);
            $value = $isolator[1];

            $this->latestState[$key] = $value;
        }
    }

    /**
     * Returns the state of a variable as far as the Webfront is concerned.
     * 
     * @param $key Name of the state to get 
     */
    function getStateFromKey($key) {
        return $this->latestState[$key];
    }

    function getState() {
        $rtn = "";

        foreach ($this->latestState as $key => $value) {
            $rtn .= "<" . $key . ">" . $value . "</>";
        }

        return $rtn;
    }

    function __construct() {
        
    }

}

?>
