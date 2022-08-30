<?php

include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();

// date_default_timezone_set ("Asia/Singapore");

if(isset($_POST['id']) && isset($_POST['time']) && isset($_POST['dateString'])){
    //==========================This path is to Create the appointment. ==================================================

    $dateString = $_POST['dateString'];
    $id = $_POST['id'];
    $time = $_POST['time'].":00";

    #Reformatting date for the database
    $date = strtotime($dateString);
    $formatedDate = date('Y-m-d', $date);
    $datefinal = $formatedDate . " " . $time;

    #Create a default pending status for the appointment
    $status = "Pending";

    $appointmentdata = json_encode(array("patient_id" => $id, "appointment_datetime" => $datefinal, "status" => $status));
    $response = callAPI('POST', $urls->getAppointmentURL() , $appointmentdata );
    $reply = json_decode($response, True);

    #Show the sucess or error message.
    if ($reply['code'] == 201 ) {
        $_SESSION['success'] =  $reply['message'];

    } else {
        $_SESSION['error'] = $reply['message'];
    
    }

    header('location: book_appointment.php');
    exit();

} else {
    //==========================This path is to get the appointments on that day==================================================
       # Formatting the date for the API endpoint. Change of DD-MM-YYYY to MM-DD-YYYY
    var_dump($_GET);
    $date = $_GET['date'];
    $newDate = date("d-m-Y", strtotime($date));


    #Call the API endpoint to retrieve all the appointments of that day.
    $response = callAPI('GET', $urls->getAppointmentURL().'/date/'. $newDate , null );
    $userdata = json_decode($response, True);

    $booked_timeslot = [];
    #If there are no timeslow, return empty book_timeslot
    $appointment_dates = $userdata['data'];
    foreach ($appointment_dates as $appointments ) {
        #Only take the confirmed or Pending appointemnnts to block a new appointment
        if ($appointments['status'] == "Confirmed"  ||  $appointments['status'] == "Pending") {
            $appointment_time =  $appointments['appointment_datetime'];
            $time = date("H:i:s",strtotime($appointment_time));
            array_push($booked_timeslot, $time);
        }
    }

    
    $_SESSION['data'] = $booked_timeslot;
    $_SESSION['date'] = $date; // Return the original datetime format.
    header("location: book_appointment.php");
    exit();

   }

    



?>