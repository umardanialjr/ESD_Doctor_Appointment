<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();
date_default_timezone_set ("Asia/Singapore");
//var_dump($_GET);
//var_dump($_SESSION);


if(isset($_GET['time'])){
    $time = $_GET['time'];
    unset($_SESSION['time']);
}
else{
    $_SESSION['error'] = "No time selected";
    header('location: book_appointment.php');
    exit();
}

if(isset($_SESSION['date'])){
    $date = $_SESSION['date'];
    $dateTime = $date." ".$time;
    $date_process = date('Y-m-d H:i',strtotime($dateTime));
    $dateNow = date('Y-m-d H:i');
//    var_dump($date_process);
//    var_dump( $dateNow);
//    var_dump($dateTime>$dateNow);
    if ($dateTime < $dateNow ){
        $_SESSION['error'] = "Cannot book an appointment for a past date time";
        header('location: book_appointment.php');
        exit();
    }
    unset($_SESSION['date']);
}
else{
    $_SESSION['error'] = "No date selected";
    header('location: book_appointment.php');
    exit();
}
if(isset($_SESSION['patient_id'])){
    $patient_id = $_SESSION['patient_id'];
}
else{
    header('location: home.php');
    exit();
}
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

    <nav class="navbar navbar-light bg-light">
        <img src="static/img/esd_logo1.png"/>
    </nav>
    <div class="card-body" style="position:relative;">
        <ol class="breadcrumb my-4 mt-0">
            <li class="breadcrumb-item active">My Appointments</li>
            <li class="breadcrumb-item"><a href="profile.php">Profile</a></li>
            <li class="breadcrumb-item active"><a href="logout.php">Logout</a></li>
        </ol>
        <div class="shadow p-4">
            <?php
            if (isset($_SESSION['error'])) {
                echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
                unset($_SESSION['error']);
            }

            if (isset($_SESSION['message'])){
                echo "<div class='alert alert-success'>".$_SESSION['message']."</div>";
                unset($_SESSION['message']);
            }
            ?>


            <form method="POST" action="book_appointment_process.php" style="width:100%;" class="mt-1 mb-0" name="form1">

                <input type="hidden" name="id" value="<?=$patient_id?>">
                <input type="hidden" name="time" value="<?=$time?>">
                <input type="hidden" name="dateString" value="<?=$date?>">
                <div>Appointment is booked for</div>
                <div>Date: <?=$date?></div>
                <div>Time: <?=$time?></div>
                <input type="submit" class="btn btn-success" value="Book Appointment">
            </form>
            <a href="book_appointment.php"><button class="btn btn-danger">Cancel</button></a>
        </div>
    </div>
    </body>