<?php
/*
 * Project: CleanWebfront
 * File: index.php
 * Author: Alex Kersten
 * 
 * The landing page for our Webfront. We'll need to check user credentials here
 * to see if they're allowed to log in, and if so, modify the headers and send
 * them to the Webfront proper.
 * 
 * This file doesn't include globalHeader (Bootstrap, jQuery, anything) because
 * it's pretty minimal already and those don't serve any extra purpose here.
 * 
 * The password is SHA512'd once on the clientside (JS) to mitigate MITM attacks
 */

require "php/features.php";

if (isset($_POST['PASSWORD'])) {
    if (userLoginValid($_POST['USERNAME'], $_POST['PASSWORD'])) {
        session_start();

        $_SESSION['AUTHORIZED'] = true;

        //Any HTML in the username left un-escaped on purpose (since it gets
        //saved in the logs and we don't need it to look funny. If a user wants
        //to XSS their own session that's fine by me).
        $_SESSION['USERNAME'] = $_POST['USERNAME'];

        logEvent("Login successful.", 4);

        //Authorization should now pass.
        header('Location: webfront.php');

        exit();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link rel="stylesheet" type="text/css" href="css/login.css" />
        <title>Máquina Webfront - Log in</title>

        <script type="text/javascript" src="js/crypto.js"></script>
        <script type="text/javascript">
            function secureSubmit(){
                document.getElementById("passwordField").value =
                    hex_sha512(
                document.getElementById("usernameField").value.toUpperCase() + 
                    document.getElementById("plaintextField").value);
                
                //Clear the plaintext so it doesn't get sent in the clear
                document.getElementById("plaintextField").value = "";             
            }
        </script>

    </head>
    <body>
        <h1 id="maquinaBanner">M&aacute;quina</h1>

        <form name="passwordForm" id="passwordForm" class="landing"
              action="index.php" method="post" onsubmit="secureSubmit();">

            <input type="text" name ="USERNAME" id="usernameField"
                   class="textInput" style="display: none;" value="" />
            <input type="password" name="PLAINTEXT" id="plaintextField"
                   class="textInput" style="display: none;" value="" />

            <p id="noJS" class="landing" style="color:#E00;">
                Passwords are securely hashed clientside
                to mitigate MITM attacks.<br />
                <br />You'll need to enable JavaScript to log in.</p>

            <input type="submit" name="LOGINBUTTON" id="loginButton"
                   style="position: relative; left: 316px; top: -40px;
                   display: none;" value="Login" />

            <!-- This is the field we'll populate with Javascript to
                 send the hashed password in. -->

            <input type="password" name="PASSWORD" id="passwordField"
                   style="display: none;" value="NOJS" />
        </form>

        <!-- Make things good if the user has JS enabled. -->
        <script type="text/javascript">
            document.getElementById("noJS").style.display = "none";
            document.getElementById("usernameField").style.display = "block";
            document.getElementById("plaintextField").style.display = "block";
            document.getElementById("loginButton").style.display = "inline";
        </script>

        <?php
        if (isset($_POST['PASSWORD'])) {
            //The only way we can get here is if a password was sent that did
            //not redirect us (aka, the wrong password). Tell the user bad user!
            ?>
            <p class="landing" style="color:#E00;">Invalid credentials.</p>
            <?php
        }
        ?>

    </body>
</html>
