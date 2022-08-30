<?php
include_once "include/common.php";
include_once "include/curl.php";


?>
<html>
    <style>
       
        .input-check {
            position: absolute;
            visibility: hidden;
        }
        .checkbox_custom input:checked + div {
            background: #f75829;
            color: white; 
        }

    </style>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel= "stylesheet" type= "text/css" href= "static/css/styles.css">
    </head>
    <body>
        <nav class="navbar navbar-light bg-light">
        <img src="static/img/esd_logo1.png"/>
        </nav>
        <div class="card-body" style="position:relative;">
            <ol class="breadcrumb my-4 mt-0">
                <li class="breadcrumb-item active">Add Availability</li>
                <li class="breadcrumb-item"><a href="doctor_schedule.php">Schedule</a></li>
                <li class="breadcrumb-item"><a href="doctor_overview.php">View Availability</a></li>
<!--                <li class="breadcrumb-item"><a href="doctor_insert_desc.php">Add Description</a></li>-->
            </ol>
            <div class="shadow p-4">
                <?php if (isset($_SESSION['error'])){
                    echo ("<div class = 'alert alert-danger'>".$_SESSION['error']."</div>");
                    unset($_SESSION['error']);
                }

                if(isset($_SESSION['message'])){
                    echo ("<div class='alert alert-success'>".$_SESSION['message']."</div>");
                    unset($_SESSION['message']);
                }

                ?>
                <form method="POST" action="doctor_shift_process.php" style="width:100%;" class="mt-1 mb-0">
                    <div class="avail-container d-flex flex-column" style="postition: relative; width:100%;">
                        <input style=" d-flex justify-content-center width:50%;"type="date" class="date-class" id="date-id" name="date">
                        <div class="checkbox_custom d-flex p-3 mt-3" style="width:100%;">
                            <label class="col-sm">
                                <input class="input-check" type="checkbox" name='morning' value="morning">
                                <div class="avail-btn btn btn-lg" style="width:80%;">
                                    <i class="fa fa-sun-o"></i>
                                    <span>Morning<span>
                                </div>
                            </label>
                            <label class="col-sm">
                                <input class="input-check" type="checkbox" name='afternoon' value="afternoon">
                                <div class="avail-btn btn btn-lg" style="width:80%;">
                                    <i class="fa fa-cloud"></i>
                                    <span>Afternoon</span>
                                </div>  
                            </label>
                            <label class="col-sm">
                                <input class="input-check" type="checkbox" name='night' value="night">
                                <div class="avail-btn btn btn-lg" style="width:80%;">
                                    <i class="fa fa-moon-o"></i>    
                                    <span>Night</span>
                                </div>  
                            </label>
                            
                            <!-- <label class="col-sm"><p style="width:80%" class="avail-btn btn btn-lg"><i class="fa fa-moon-o"></i><input class="input-check" type="checkbox" name='night' value="night"><span class="ml-2">Night</span></p></label> -->
                        </div>
                    </div>
                    <div class="">
                    <button type="submit"class="btn btn-success btn-lg btn-block">Confirm Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>