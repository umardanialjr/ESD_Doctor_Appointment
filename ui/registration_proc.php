<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();

if (!isset($_POST['patient_fname'])){
    $_SESSION['error'] = "No First Name Entered";
    header('location: registration.php');
}

if (!isset($_POST['patient_lname'])){
    $_SESSION['error'] = "No Last Name Entered";
    header('location: registration.php');
}

if (!isset($_POST['patient_phone'])){
    $_SESSION['error'] = "No Phone Entered";
    header('location: registration.php');
}

if (!isset($_POST['patient_telehandle'])){
    $_SESSION['error'] = "No telehandle Entered";
    header('location: registration.php');
}

if (!isset($_POST['patient_email'])){
    $_SESSION['error'] = "No Email Entered";
    header('location: registration.php');
}

$patient_fname = trim($_POST["patient_fname"]);
$patient_lname = trim($_POST["patient_lname"]);
$patient_phone = trim($_POST["patient_phone"]);
$patient_telehandle = trim($_POST["patient_telehandle"]);
$patient_email = trim($_POST["patient_email"]);

add_patient($patient_fname, $patient_lname, $patient_phone, $patient_telehandle, $patient_email, $urls);
function add_patient($patient_fname, $patient_lname, $patient_phone, $patient_telehandle, $patient_email, $urls) {

    $patient_data = json_encode(array("patient_fname" => $patient_fname, 
                                    "patient_lname" => $patient_lname,
                                    "patient_phone" => $patient_phone,
                                    "patient_telehandle" => $patient_telehandle,
                                    "patient_email" => $patient_email,
                                    "chat_id" => null));

    $insert_patient_data = callAPI('POST', $urls->getPatientURL(), $patient_data);

    $response = json_decode($insert_patient_data, True);

    if ($response['code'] == 201) {
        $_SESSION['message'] = $response['message'];
//        var_dump($response['message']);
         header('location: registration.php');
    }
    else{
        $_SESSION['error'] = $response['message'];
         header('location: registration.php');
//        var_dump($response['message']);
    }
}

?>