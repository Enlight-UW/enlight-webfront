<?php

/*
  Project: CleanWebfront
  File: templateModule.php
  Author: Alex Kersten
 */

class templateModule extends module {

    function __construct() {
        parent::__construct("Title of the module goes here.");
    }

    function getInnerContent() {
        return '<h2>Attention grabber</h2>';
    }

}

?>
