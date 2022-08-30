<?php
include_once "include/common.php";
include_once "include/curl.php";


?>

<html>
    <head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel= "stylesheet" type= "text/css" href= "static/css/styles.css">
    </head>
    <body>
    <div class="container">
        <img src="static/img/esd_logo1.png"/>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<div>".$_SESSION['error']."</div>";
            unset($_SESSION['error']);
        } ?>
        <div class="login-container">
            <form method="POST" action="staff_login_process.php" class="login-form">
                <div class="login-input">
                    <label for="username">
                        Staff ID
                    </label>
                    <input placeholder="Staff ID" name="staff_id" type="text">
                </div>
                <div class="login-input">
                    <label for="password">
                        Phone Number
                    </label>
                    <input placeholder="Phone Number" name="staff_phone" type= 'text'>
                </div>

                <input type='submit' class="btn-login lg-btn">
            </form>
        </div>
    </div>
    </body>
</html>