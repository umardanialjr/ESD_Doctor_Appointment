<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();


$_SESSION['doctor_id'] = 1;

if (!isset($_POST['date'])){
    $_SESSION['date'] = date('d-m-Y');
    header('location: doctor_schedule.php');
    exit();
}
elseif ($_POST['date'] == ""){
    $_SESSION['date'] = date('d-m-Y');
    header('location: doctor_schedule.php');
    exit();
}
else {
    $doctor_query_date = trim($_POST['date']);
    $formatDate = date("d-m-Y", strtotime($doctor_query_date));
    $_SESSION['query_date'] = $formatDate;
    header('location: doctor_schedule.php');
    exit();
}


?>