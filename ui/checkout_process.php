<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$appointment_id = $_GET['appointment_id'];
$patient_id = $_SESSION['patient_id'];

callAPI("POST",'http://localhost:5002/create-checkout-session');
