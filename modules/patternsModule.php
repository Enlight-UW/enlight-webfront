<?php

/*
 * Project: enlight-webfront
 * File: patternsModule.php
 * Author: Alex Kersten
 * 
 * The module that controls pattern selection, editing, and creation.
 */

class patternsModule extends module {

    function __construct() {
        parent::__construct("Patterns");
    }

    function getInnerContent() {
        return '
            <h2>Pattern Perfection</h2>
            <p>The current pattern is</p>
        ';
    }

}

?>
