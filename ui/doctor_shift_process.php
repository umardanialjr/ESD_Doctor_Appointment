<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();

function add_availability($doctor_selected_date,$doctor_id,$start,$end,$urls){

    $start = date_format($start, "Y-m-d H:i:s");
    $end = date_format($end, "Y-m-d H:i:s");

    echo $start;
    echo $end;

    // // compile data into a associative array and encode as a json
    $doctor_shift_data = json_encode(array("calendar_date" => $doctor_selected_date, "doctor_id" => $doctor_id,"availability_datetime_start" => $start, "availability_datetime_end"=> $end));

    // // Post request
    $insert_shift_data = callAPI('POST',$urls->getAvailabilityURL(), $doctor_shift_data);
    // echo $insert_shift_data;
    // // return and format json in associative array
    $response = json_decode($insert_shift_data,True);
//    echo ($response);
    return $response;
}

// var_dump($_POST);
// var_dump($_SESSION);
if(!isset($_SESSION['doctor_id'])){
    session_destroy();
    header('location: home.php');
    exit();
}
//$_SESSION['doctor_id'] = 1;

if (!isset($_POST['date']) or $_POST['date'] == ""){
    
    $_SESSION['error'] = "No date is selected";
    header('location: doctor_shift.php');
    exit();
}
else{
    $doctor_selected_date = trim($_POST['date']);
}
$to_check = array();

if (isset($_POST['morning']) and $_POST['morning'] != null){
    $morning = trim($_POST['morning']);

    $startdatetime = new DateTime($doctor_selected_date.' 10:00:00');
    $enddatetime = new DateTime($doctor_selected_date.' 13:00:00');

    $response = add_availability($doctor_selected_date,$_SESSION['doctor_id'],$startdatetime,$enddatetime,$urls);
    array_push($to_check, $response['code']);
}

if (isset($_POST['afternoon']) and $_POST['afternoon'] != null){
    $afternoon = trim($_POST['afternoon']);

    $startdatetime = new DateTime($doctor_selected_date.' 14:00:00');
    $enddatetime = new DateTime($doctor_selected_date.' 17:30:00');

    $response = add_availability($doctor_selected_date,$_SESSION['doctor_id'],$startdatetime,$enddatetime,$urls);
    array_push($to_check, $response['code']);
}

if (isset($_POST['night']) and $_POST['night'] != null){
    $night = trim($_POST['night']);

    $startdatetime = new DateTime($doctor_selected_date.' 18:30:00');
    $enddatetime = new DateTime($doctor_selected_date.' 21:00:00');

    $response = add_availability($doctor_selected_date,$_SESSION['doctor_id'],$startdatetime,$enddatetime,$urls);
    array_push($to_check, $response['code']);
}


if ($to_check[0] != 201) {
    $_SESSION['error'] = "An error occurred updating availability";
    header('location: doctor_shift.php');
    exit();
}
else {
    $_SESSION['message'] = "Updated schedule";
    header('location: doctor_shift.php');
    exit();
}

//var_dump($_POST);
//var_dump($_SESSION);