<?php

/*
 * Project: CleanWebfront
 * File: features.php
 * Author: Alex Kersten
 * 
 * The features file contains all the fuctions that might reasonable be called
 * features of the Webfront, including things like writing to the API log file
 * and changing user passwords.
 */

$ALLOWED_LOGS = array('API', 'Fountain', 'Email', 'Error', 'Webfront');
$LOG_DIRECTORY = "SHADOW/LOGS/";
$SALT_FILE = "SHADOW/Salt.txt";
$SALT_ITERATIONS = 1000;

/**
 * Generate the current prefix for logfiles based on the month and year.
 * 
 * @return string The current date prefix in the form YYYY_MM_
 */
function genCurrentDatePrefix() {
    return date("Y_m_");
}

/**
 * Generate a timestamp to put before every log entry. Also includes user's name
 * and IP.
 * 
 * @return string The text to prepend to all log entries.
 */
function genLogPrefix() {
    return "[" . date("D d h:i:sa") . "]["
            . $_SESSION['USERNAME'] . "@" . $_SERVER["REMOTE_ADDR"] . "]";
}

/**
 * Write a line to the current log.
 * 
 * Typical log IDs:
 * 0 - API log
 * 4 - Webfront log
 * 
 * @param string $event The line to write to the API log.
 * @param int $type The log ID to write to.
 */
function logEvent($event, $type) {
    global $LOG_DIRECTORY, $ALLOWED_LOGS;

    $type = (int) $type;
    if ($type < 0 || $type >= count($ALLOWED_LOGS)) {
        //Could be dangerous if the function breaks, so don't break it.
        logEvent("Attempted to log to an invalid log type (" . $type . ")", 4);
        return;
    }

    //Open file in append mode
    $logFile = fopen($LOG_DIRECTORY . genCurrentDatePrefix()
            . $ALLOWED_LOGS[$type] . ".txt", 'a');

    fwrite($logFile, "\n" . genLogPrefix() . $event);
    fclose($logFile);
}

/**
 * Returns the hash of the password salted with the user's name interleaved with
 * our own secret salt value.
 * 
 * We'll use SHA-512 with a thousand rounds, which should be relatively slow.
 * 
 * Even rounds will append the user's CAPS name to the beginning of the string,
 * odd rounds will append our secret value to the end of the string.
 * 
 * The secret salt value will be loaded from the 1st line in the file specified
 * by $SALT_FILE.
 * 
 * @param string $user The name of the user.
 * @param string $pass The password to be hashed.
 * @return string The resulting hash.
 */
function hashPass($user, $pass) {
    global $SALT_FILE, $SALT_ITERATIONS;

    $secretFile = file($SALT_FILE);
    $saltPrivate = $secretFile[0];

    $saltPublic = strtoupper($user);

    for ($i = 0; $i < $SALT_ITERATIONS; $i++) {
        if ($i % 2 == 0) {
            $pass = hash("sha512", $saltPublic . $pass);
        } else {
            $pass = hash("sha512", $pass . $saltPrivate);
        }
    }

    return $pass;
}

/**
 * Use to emulate the hashing of a clientside password. Can't think of a use for
 * it yet, but good to have a serverside implementation. Maybe if we create a
 * user manually on the serverside, we might need this to hash the password.
 * 
 * @param string $plaintext The plaintext to hash.
 * @return string The hashed pre-password. 
 */
function simulateClientsideHash($user, $plaintext) {
    return hash("sha512", strtoupper($user) . $plaintext);
}

/**
 * Checks whether a login should grant full access to the Webfront.
 * 
 * @param string $username The username of the user, in plaintext.
 * @param string $userhash The clientside hash of the password. Run this through
 * our own hashing/salting here on the server and then compare it to the stored
 * record.
 * @return boolean If the login was a success or not.
 */
function userLoginValid($username, $userhash) {
    return true;
}

/**
 * Converts a string with spaces to one with underscores. Used it at one point
 * before converting to bootstrap but I don't think this is used anywhere now.
 * 
 * @param string $string The string to convert.
 * @return string The converted string.
 */
function spacesToUnderscores($string) {
    return str_replace(" ", "_", $string);
}


?>