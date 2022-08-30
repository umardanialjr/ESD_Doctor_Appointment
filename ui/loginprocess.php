<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();

//if (!isset())

$patient_phone = trim($_POST['patient_phone']);
$patient_fname = trim($_POST['patient_fname']);

if ($patient_phone == "" or $patient_phone == null) {
    $_SESSION['error'] = "No patient phone set";
    header('location: login.php');
    exit();
}

if ($patient_fname == "" or $patient_fname == null) {
    $_SESSION['error'] = "No patient name set";
    header('location: login.php');
    exit();
}

//compile data into a associative array and encode as a json
$patientdata = json_encode(array("patient_fname" => $patient_fname, "patient_phone" => $patient_phone));

//Post request
$userdata = callAPI('POST', 'localhost:5001/authenticate_patient', $patientdata);

//return and format json in associative array
$response = json_decode($userdata,True);

//echo $response;

//checks response code
if ($response['code'] == 200) {
    $_SESSION['patient_id'] = $response['data']['patient_id'];
    header('location: profile.php');
    exit();
}
else{
    $_SESSION['error'] = $response['message'];
    header('location: login.php');
    exit();
}
