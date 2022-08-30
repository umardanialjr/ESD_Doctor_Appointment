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
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/v4-shims.css">
    </head>
    <body>
        <nav class="navbar navbar-light bg-light">
        <img src="static/img/esd_logo1.png"/>
        </nav>
        <div class="card-body" style="position:relative;">
            <ol class="breadcrumb my-4 mt-0">
                <li class="breadcrumb-item active">Register</li>
            </ol>
            <div class="card bg-light">
                <h4 class="mt-3 text-center pb-3">Create Account</h4>
                <?php
                    if(isset($_SESSION['error'])){
                        echo"<div class='alert alert-danger'>".$_SESSION['error']."</div>";
                        unset($_SESSION['error']);
                    }

                    if(isset($_SESSION['message'])){
                        echo"<div class='alert alert-success'>".$_SESSION['message']."</div>";
                        unset($_SESSION['message']);
                    }
                ?>
                <div class="d-flex align-items-center justify-content-center">
                    <form method="POST" action="registration_proc.php">
                        <!-- <div class="row"> -->
                            <div class="">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                    </div>
                                    <input name="patient_fname" class="form-control" placeholder="First Name" type="text" autocomplete="off">
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                    </div>
                                    <input name="patient_lname" class="form-control" placeholder="Last Name" type="text" autocomplete="off">
                                </div> 
                            </div>
                        <!-- </div> -->
                        <div class="">
                            <div class="form-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
                                </div>
                                <input name="patient_phone" class="form-control" placeholder="Phone number" type="text" autocomplete="off">
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-telegram"></i> </span>
                                </div>
                                <input name="patient_telehandle" class="form-control" placeholder="Telehandle" type="text" autocomplete="off">
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                                </div>
                                <input name="patient_email" class="form-control" placeholder="Email address" type="email" autocomplete="off">
                            </div> 
                        </div>                            
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block"> Create Account  </button>
                        </div>    
                        <p class="text-center">Have an account? <a href="login.php">Log In</a> </p>                                                                 
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
