<?php

if (isset($_POST['DROP']))
    require "sqliteDrop.php";

if (isset($_POST['REGEN']))
    require "sqliteInit.php";
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<!DOCTYPE html>
<html>
    <body>
        <form method="POST" action="sqlite.php">
            <button name="DROP">Drop tables</button>
            <button name="REGEN">Regen tables</button>
        </form>
    </body>
</html>