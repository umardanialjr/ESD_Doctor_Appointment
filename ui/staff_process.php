<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();
var_dump($_GET);

if (!isset($_GET['status'])){
    $_SESSION['error'] = 'Status not set';
    header('location: staff_home.php');
}

if (!isset($_GET['appointment_id'])){
    $_SESSION['error'] = 'appointment id not set';
    header('location: staff_home.php');
}

if ($_GET['status'] == 'cancel'){
    $status_to_update = "Cancelled";
}
elseif ($_GET['status'] == 'confirm'){
    $status_to_update = "Confirmed";
}
else{
    $_SESSION['error'] = 'Status has error';
    header('location: staff_home.php');
}


$data_insert = json_encode(array("status"=>$status_to_update, "appointment_id"=>$_GET['appointment_id']));
//$response = callAPI("PUT","localhost:5001/appointment/".$_GET['appointment_id'],$data_insert);
$response = callAPI("PUT",$urls->getSchedulingUpdateURL()."/staff/appointment/",$data_insert);

$return = json_decode($response,True);
//var_dump($response);

if ($return['code'] == 200){
    $_SESSION['message'] = "Appointment id: ".$_GET['appointment_id']." updated status to:".$status_to_update;
    header("location: staff_home.php");
}
else{
    $_SESSION['error'] = $return['message'];
    header("location: staff_home.php");
}
//var_dump($response);


?>