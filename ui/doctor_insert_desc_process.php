<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();

function add_desc($patient_id, $appointment_id, $description,$urls) {

    $medical_data = json_encode(array("patient_id" => $patient_id,
        "appointment_id" => $appointment_id,
        "description" => $description));

    $insert_data = callAPI('POST', $urls->getMedicalURL(), $medical_data);

    $response = json_decode($insert_data, True);
    var_dump($response);
    if ($response['code'] == 201) {
        header('location: doctor_schedule.php');
        exit();
    }
    else{
        $_SESSION['error'] = $response['message'];
        header('location: doctor_insert_desc.php');
        exit();
    }
}

var_dump($_POST);

$appointment_id = trim($_POST['appointment_id']);
$patient_id = trim($_POST['patient_id']);
$description = trim($_POST['description']);

add_desc($patient_id,$appointment_id,$description,$urls);



?>