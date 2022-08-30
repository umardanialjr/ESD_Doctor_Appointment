<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();

    if (isset($_GET['date'])){
        $query_date = $_GET['date'];
        $_SESSION['date'] = $_GET['date'];
        $query_date = date("d-m-Y", strtotime($query_date));
    //    var_dump($_GET);
    }
    elseif (isset($_SESSION['date'])){
        $query_date = $_SESSION['date'];
        $query_date =date("d-m-Y", strtotime($query_date));
    }
    else {
        $query_date = date("d-m-Y");
    }

    $all_appointment = callAPI("GET",$urls->getSchedulingDisplayURL()."/staff/appointment/".$query_date,false);
    //$medical_list = callAPI("GET","localhost:5001/medical/patientid/".$patientid,false);

    // json_decode transforms it into an associative array; $userr_data is now an associative array
    $all_appointments = json_decode($all_appointment,True);
//    var_dump($all_appointments);
//    var_dump($_SESSION);
?>
<html>
    <style>
        .cancel-btn:hover {
            cursor: pointer;
            color:black;
        }
    </style>
    <head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/v4-shims.css">
        <link rel= "stylesheet" type= "text/css" href= "static/css/styles.css">
    </head>
    <body>
        <nav class="navbar navbar-light bg-light">
        <img src="static/img/esd_logo1.png"/>
        </nav>
        <div class="card-body" style="position:relative;">
            <ol class="breadcrumb my-4 mt-0">
                <li class="breadcrumb-item active">Overall Schedule</li>
                <li class="breadcrumb-item active"><a href="logout.php">Logout</a></li>
            </ol>
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
            <form method="GET" action="staff_home.php">
                <input type="date" class="date-class d-flex" id="date-id" name="date">
                <input type="submit" class="btn btn-primary" name="Retrieve">
                <br>
                    <h3>Date: <?=$query_date?></h3>
                    <h4>Doctor Availability</h4>
                    <table class="table table-bordered" style="width:100%; padding:10px;";>
                        <tbody>
                            <tr>
                                <th>DoctorID</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                            </tr>
                        <?php
                        if (empty($all_appointments['doctordata'])){
                            echo"<tr><td colspan='3'>No doctors available</td></tr>";
                        }

                        else {
                            foreach ($all_appointments['doctordata'] as $row) {
                                $dateTimeStart = datetime::createfromformat('D, d M Y H:i:s e', $row['availability_datetime_start']);
                                $starttime = $dateTimeStart->format('H:i:s');

                                $dateTimeEnd = datetime::createfromformat('D, d M Y H:i:s e', $row['availability_datetime_end']);
                                $endtime = $dateTimeEnd->format('H:i:s');

                                echo "<tr>";
                                echo "<td>" . $row['doctor_id'] . "</td>";
                                echo "<td>" . $starttime . "</td>";
                                echo "<td>" . $endtime . "</td>";
                            }
                        }
                        ?>


                        </tbody>
                    </table>
                    <h4>Patient Information</h4>
                    <table class="table table-bordered" style="width:100%; padding:10px;";>
                        <tbody>
                            <tr>
                                <th>Time</th>
                                <th>Appointment ID</th>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                            <?php

                                if (empty($all_appointments['patientdata'])){
                                    echo"<tr><td colspan='6'>No appointments</td></tr>";
                                }
                                else {
                                    foreach ($all_appointments['patientdata'] as $row) {
                                        $dateTime = datetime::createfromformat('D, d M Y H:i:s e',$row['appointment_datetime']);
                                        $time = $dateTime->format('H:i:s');
                                        $href_cancel = "staff_process.php?status=cancel&appointment_id=".$row['appointment_id'];
                                        $href_success = "staff_process.php?status=confirm&appointment_id=".$row['appointment_id'];
                                        echo "<tr>";
                                        echo "<td>" . $time . "</td>";
                                        echo "<td>" . $row['appointment_id'] . "</td>";
                                        echo "<td>" . $row['patient_id'] . "</td>";
                                        echo "<td>" . $row['patient_fname'] . "</td>";
                                        echo "<td>" . $row['status'] . "</td>";
                                        if ($row['status'] == "Pending"){
                                            echo"<td>
                                                    <a href=".$href_cancel."><button type='button' class='btn btn-danger' id='statusid' >Cancel</button></a>
                                                    <a href=".$href_success."><button type='button' class='btn btn-success' id='statusid' >Confirm</button></a>
                                                 </td>";
                                        }
                                        else{
                                            echo"<td></td>";
                                        }
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </body>

    <script>

        function changeStatus(){
            var status = document.getElementById('statusid')
            status.setAttribute("class" , "btn btn-success");
            status.innerHTML = 'Confirmed';
            var cancel = document.getElementById('cancel');
            cancel.setAttribute("class", "fas fa-window-close btn cancel-btn fa-lg disabled")
        }
    </script>
</html>
