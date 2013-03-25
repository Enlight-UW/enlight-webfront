<?php
/*
 * Error page
 */
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link href="css/notInventedHere/bootstrap.min.css" rel="stylesheet" />
        <link href="css/webfront.css" rel="stylesheet" />
        <title>Error - Máquina Webfront</title>
    </head>
    <body>
        <script src="js/notInventedHere/bootstrap.min.js"></script>
        <div class="hero-unit">
            <h1>Error</h1>
            <p><?php echo htmlentities($_GET['id']) ?></p>
            <p><a class="btn btn-primary btn-large" href="webfront.php">Reconnect</a></p>
        </div>
    </body>
</html>