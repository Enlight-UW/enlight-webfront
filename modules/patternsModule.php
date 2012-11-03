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

                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <a class="btn" href="#"><i class="icon-asterisk"></i> New Pattern</a>
                        </div>
                        <div class="btn-group">
                            <div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="icon-hdd"></i> Load Pattern For Editing <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Jacob\'s Ladder</a></li>
                                    <li><a href="#">Spin Cycle</a></li>
                                    <li><a href="#">Another Cutesy Name</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="btn-group">
                            <a class="btn" href="#"><i class="icon-heart"></i> Save As:</a>
                            <a class="btn" href="#"><i class="icon-pencil"></i> Untitled Pattern</a>
                        </div>
                        
                        <div class="btn-group">
                            <a class="btn" href="#" onclick="return false;"><i class="icon-fire"></i> Delete Pattern File</a>
                        </div>
                    </div>
     
                    <!-- Editor canvas -->
                    <canvas id="patternEditorCanvas" style="border:1px solid #CFCCCD; width: 100%; height: 300px;">
                    </canvas>
 


         </div>
        ';
    }

}

?>
