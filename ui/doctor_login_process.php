<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();

$doctor_phone = trim($_POST['doctor_phone']);
$doctor_id = trim($_POST['doctor_id']);

if ($doctor_phone == "" or $doctor_phone == null) {
    $_SESSION['error'] = "No doctor phone set";
    header('location: staff_login.php');
    exit();
}

if ($doctor_id == "" or $doctor_id == null) {
    $_SESSION['error'] = "No doctor ID set";
    header('location: staff_login.php');
    exit();
}

//compile data into a associative array and encode as a json
$doctordata = json_encode(array("doctor_id" => $doctor_id, "doctor_phone" => $doctor_phone));

//Post request
$doctoruserdata = callAPI('POST', $urls->getAuthenticateDoctorURL(), $doctordata);

//return and format json in associative array
$response = json_decode($doctoruserdata,True);

//echo $response;

//checks response code
if ($response['code'] == 200) {
    $_SESSION['doctor_id'] = $response['data']['doctor_id'];
//    var_dump($response);
    header('location: doctor_overview.php');
    exit();
}
else{
    $_SESSION['error'] = $response['message'];
    header('location: doctor_login.php');
    exit();
}
