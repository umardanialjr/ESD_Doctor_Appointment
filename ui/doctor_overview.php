<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();

$doctorid = $_SESSION['doctor_id'];

//$doctorid = 1;

$get_avail = callAPI("GET", $urls->getSchedulingURL()."/doctor/".$doctorid, False);
$view_avail = json_decode($get_avail, True);
//var_dump($view_avail);
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
                <li class="breadcrumb-item"><a href="doctor_shift.php">Add Availability</a></li>
                <li class="breadcrumb-item"><a href="doctor_schedule.php">Schedule</a></li>
                <li class="breadcrumb-item active">View Availability</li>
                <li class="breadcrumb-item"><a href="logout.php">Logout</a></li>
<!--                <li class="breadcrumb-item"><a href="doctor_insert_desc.php">Add Description</a></li>-->
            </ol>
            <div class="shadow p-4">
                <!-- <div class="container"> -->
                    <table class="table table-bordered" style="width:100%; table-layout:fixed;">
                        <tbody>
                            <tr>
                                <th class="">Date</th>
                                <th class="">Availability ID</th>
                                <th class="">Start</th>
                                <th class="">End</th>
                            </tr>

                            <?php
                            if (empty($view_avail['data'])){
                                echo"<tr><td colspan='4'>Not Available</td></tr>";
                            }
                            else {
                                foreach ($view_avail['data'] as $row) {

                                    $date = datetime::createfromformat('D, d M Y H:i:s e',$row['calendar_date']);
                                    $date = $date->format('D, d M Y');

                                    $startTime = datetime::createfromformat('D, d M Y H:i:s e',$row['availability_datetime_start']);
                                    $startTime = $startTime->format('H:i');

                                    $endTime = datetime::createfromformat('D, d M Y H:i:s e',$row['availability_datetime_end']);
                                    $endTime = $endTime->format('H:i');

                                    echo "<tr>";
                                    echo "<td>" . $date . "</td>";
                                    echo "<td>" . $row['availability_id'] . "</td>";
                                    echo "<td>" . $startTime . "</td>";
                                    echo "<td>" . $endTime . "</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>

                        </tbody>
                    </table>
                <!-- </div> -->
            </div>
        </div>
    </body>
</html>