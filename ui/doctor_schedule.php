<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();

//all doctor's appointments for the day
//$patientid = 2;
//$appointmentid = 2;

if (isset($_SESSION['query_date'])){
    $query_date = $_SESSION['query_date'];
}
else {
//    $query_date = date("d-m-Y");
//    used for testing
    $query_date = date('d-m-Y');
//    var_dump($query_date);
}

$all_appointment = callAPI("GET",$urls->getAppointmentURL()."/day/".$query_date,false);

// json_decode transforms it into an associative array; $user_data is now an associative array
$all_appointments = json_decode($all_appointment,True);

//var_dump($all_appointments);


$processed_data = [];

foreach ($all_appointments['data'] as $appointment) {

    if($appointment['status'] == "Confirmed"){
        array_push($processed_data, $appointment);
    }
}

//var_dump($processed_data);
//var_dump($_SESSION);
?>
<html>
    <style>
    </style>
    <head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link rel= "stylesheet" type= "text/css" href= "static/css/styles.css">
    </head>
    <body>
        <nav class="navbar navbar-light bg-light">
        <img src="static/img/esd_logo1.png"/>
        </nav>
        <div class="card-body" style="position:relative;">
            <ol class="breadcrumb my-4 mt-0">
                <li class="breadcrumb-item"><a href="doctor_shift.php">Add Availability</a></li>
                <li class="breadcrumb-item active">Schedule</li>
                <li class="breadcrumb-item"><a href="doctor_overview.php">View Availability</a></li>
                <li class="breadcrumb-item"><a href="logout.php">Logout</a></li>
            </ol>

            <form class="form-inline" action="doctor_schedule_process.php" method="post">
                <div>
                    <input style="display: flex; justify-content:flex-end;" type="date" class="date-class" id="date-id" name="date">
                </div>
                
                <div class="">
                    <button type="submit"class="btn btn-success">Refresh Date</button>
                </div>
            </form>


                    <h3>Selected date: <?=$query_date?> </h3>

                <div class="card mt-3 shadow" style="width:100%; height:100%;">
                    <table class="table table-bordered" style="width:100%;";>

                        <tbody>
                        <tr>
                            <th width="20%">Time</th>
                            <th width="15%">Appointment ID</th>
                            <th width="10%">Patient ID</th>
                            <th width="15%">Patient Name</th>
                            <th width="40%">Description</th>
                        </tr>

                        <?php

                            if (empty($processed_data)){
                                echo"<tr><td colspan='5'>No appointments</td></tr>";
                            }
                            else {
                                foreach ($processed_data as $row) {
                                    $dateTime = datetime::createfromformat('D, d M Y H:i:s e',$row['appointment_datetime']);
                                    $time = $dateTime->format('H:i:s');
                                    $href_url = "doctor_insert_desc.php?patient_id=".$row['patient_id']."&"."appointment_id=".$row['appointment_id'];
                                    echo "<tr>";
                                    echo "<td>" . $time . "</td>";
                                    echo "<td>" . $row['appointment_id'] . "</td>";
                                    echo "<td>" . $row['patient_id'] . "</td>";
                                    echo "<td>" . $row['patient_fname'] . "</td>";
                                    echo "<td><a target='new' href=".$href_url."><b>Add</b></a></td>";
                                    echo "</tr>";
                                }
                            }
                        ?>

                        </tbody>
                    </table>
                </div>
        </div>
    </body>
</html>