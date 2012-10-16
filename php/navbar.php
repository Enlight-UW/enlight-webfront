<?php
/*
 * Project: CleanWebfront
 * File: navbar.php
 * Author: Alex Kersten
 * 
 * Read from the session which nav thing we're on and use this to generate the
 * markup for showing which one's active. Also keeps a list of possible navbar
 * locations...
 */
?>

<!-- Javascript for changing selected nav item and making visible the
corresponding module -->
<script type="text/javascript">
    function switchModule(newSelection) {
        //Go through all the modules and de-select them.
        var numItems = document.getElementById('mainNavbar').children.length;
        
        for (var i = 0; i < numItems; i++) {
            document.getElementById('navitem_' + i).className = '';
            
            document.getElementById('module_' + i).style.display = "none";
        }
        
        //Set the class of the now-active element.
        document.getElementById('navitem_' + newSelection).className = 'active';
        
        
        //Do the same for the actual module contents.
        document.getElementById('module_' + newSelection).style.display = "block";
    }
</script>


<!-- Beginning of navbar with maquina title. -->
<div class="navbar navbar-inverse">
    <div class="navbar-inner">
        <a class="brand" href="webfront.php">M&aacute;quina Webfront</a>
        <ul class="nav" id="mainNavbar">
            <?php
            //For every module in the session modules array, we'll echo it out
            //in the navbar (and make it selected if it's the currently selected
            //one).

            for ($i = 0; $i < sizeof($_SESSION['modules']); $i++) {
                if ($i == 0) {
                    //First module is the default module.
                    echo "<li class=\"active\"";
                } else {
                    echo "<li";
                }

                echo " id=\"navitem_" . $i . "\">";

                echo "<a href=\"#\" onclick=\"switchModule(" . $i . ")\">" .
                ( $_SESSION['modules'][$i]->getModuleName()) . "</a></li>";
            }
            ?>
        </ul>
        <ul class="nav pull-right">
            <li>
                <a href="logout.php"><strong>Log out</strong></a>        
            </li>
        </ul>

    </div>
</div>
