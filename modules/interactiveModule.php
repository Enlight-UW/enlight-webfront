<?php

/*
 * Project: CleanWebfront
 * File: interactiveModule.php
 * Author: Alex Kersten
 * 
 * "Interactive" is the new name for LMOC, except this one is web-based.
 * Basically controls jets and things and is highest priority connection to the
 * server (kiosk, default patterns, etc. all ignored while this is in use).
 */

class interactiveModule extends module {

    function __construct() {
        parent::__construct("Interactive");
    }

    function getInnerContent() {
        return '<h2>Enjoy The Fountain</h2>
  <p>The (future) home of LMOC 2.0, the "Local" Maintenance and Override Console. Version 2.0 means web-based control!</p><p class="muted">Working on it...</p>

';
    }

}

?>
