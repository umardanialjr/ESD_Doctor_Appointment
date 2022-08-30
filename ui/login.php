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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    </head>

    <style>
        

        body {
            background-image: url('static/img/background-image3.jpg');
            background-color: rgba(0,0,0, 0.6);
            background-size: auto;
        }
    </style>
   
    
    <body>
    <?php
        if (isset($_SESSION['error'])) {
            echo "<div>".$_SESSION['error']."</div>";
            unset($_SESSION['error']);
        } ?>

    <div class="overallBody">
    
        <div class="container border-primary" style="background:rgba(255,255,255, 0.7); ">
            <p class="h1 font-weight-bold">Welcome to the ESD clinic application</p>
            <br>
                    <img src="static/img/esd_logo1.png"/>
                    <div>
                        <br>
                        <br>
                    </div>

                    <form method="POST" action="loginprocess.php" class="form-signin">

                        <div class="">
                            <div class="form-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                </div>
                                <input name="patient_fname" class="form-control" placeholder="First Name" type="text">
                            </div>
                        </div>

                        <div class="">
                            <div class="form-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
                                </div>
                                <input name="patient_phone" class="form-control" placeholder="Phone number" type="number" autocomplete="off">
                            </div>
                        </div>                
                        <button type='submit' class="btn btn-primary btn-login">Login </button>
                        <a href="registration.php" style="margin-top:10px;" class="btn btn-secondary btn-login" >Sign Up</a>
                    
                    </form>
        </div>
    
    </div>
    
    <!-- <div class="container">

        
        <p class="h1">Welcome to the ESD clinic application</p>
        
            <img src="static/img/esd_logo1.png"/>
            <div>
                <br>
                
                <br>
            </div>

            <form method="POST" action="loginprocess.php" class="form-signin">

                <div class="">
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                        </div>
                        <input name="patient_fname" class="form-control" placeholder="First Name" type="text">
                    </div>
                </div>

                <div class="">
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
                        </div>
                        <input name="patient_phone" class="form-control" placeholder="Phone number" type="number" autocomplete="off">
                    </div>
                </div>                
                <button type='submit' class="btn btn-primary btn-login">Login </button>
                <a href="registration.php" style="margin-top:10px;" class="btn btn-secondary btn-login" >Sign Up</a>
               
            </form>
        
        </div>
        
        <div class="login-container">
            <form method="POST" action="loginprocess.php" class="login-form">
                
                <div class="form-group">
                    
                        <label for="password">
                            Phone Number
                        </label>
                        <input type= 'text' placeholder="Phone Number" name="patient_phone">
                    
                </div>  
                    <input type='submit' class="btn-login lg-btn">
                   <span style="color:white;">Login</span>
                
                <a href="registration.php" style="margin-top:10px;">Sign Up</a>
            </form>
        </div> 
    </div> -->
    </body>
</html>