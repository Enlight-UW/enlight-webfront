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
            <p>This module controls the current pattern playing on the fountain as well as enables you to create and edit the patterns.</p>
            <h3>Select Pattern</h3>
            <div class="well">
            <p>The current pattern is:</p>
            <div class="btn-group">
                <a class="btn btn-primary btn-large dropdown-toggle" data-toggle="dropdown" href="#">
                        Default Pattern <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#">Jacob\'s Ladder</a></li>
                    <li><a href="#">Spin Cycle</a></li>
                    <li><a href="#">Another Cutesy Name</a></li>
                </ul>
            </div>
            </div>

         <h3>Create &amp; Modify Patterns</h3>
         <div class="well">
            

            
            <!-- Editor and sidebar -->
            <div class="row">
                <div class="span2">
                    <!-- TODO: Page can get messy if the names of patterns are too long -->
                    <div class="btn-group btn-group-vertical">
                        <a class="btn btn-mini" href="#">Default Pattern</a>
                        <a class="btn btn-mini" href="#">Slightly Longer Pattern</a>
                        <a class="btn btn-mini" href="#">Summer Pattern</a>
                        <a class="btn btn-mini" href="#">Awesome Pattern</a>
                    </div>
                </div>
                <div class="span10">
                    <!-- Editor buttons -->
                    <div class="btn-group">
                        <a class="btn btn-primary" href="#"><i class="icon-heart"></i> Save As:</a>
                        <a class="btn" href="#"><i class="icon-pencil"></i> Untitled Pattern</a>
                    </div>
                    
                    <!-- Editor canvas -->
                </div>
            </div>
         </div>
        ';
    }

}

?>
