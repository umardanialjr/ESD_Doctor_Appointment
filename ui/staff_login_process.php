<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();

//var_dump($_SESSION);
$staff_phone = trim($_POST['staff_phone']);
$staff_id = trim($_POST['staff_id']);

if ($staff_phone == "" or $staff_phone == null) {
    $_SESSION['error'] = "No staff phone set";
    header('location: staff_login.php');
    exit();
}

if ($staff_id == "" or $staff_id == null) {
    $_SESSION['error'] = "No staff ID set";
    header('location: staff_login.php');
    exit();
}

//compile data into a associative array and encode as a json
$staffdata = json_encode(array("staff_id" => $staff_id, "staff_phone" => $staff_phone));

//Post request
$staffuserdata = callAPI('POST', $urls->getAuthenticateStaffURL(), $staffdata);

//return and format json in associative array
$response = json_decode($staffuserdata,True);

//echo $response;

//checks response code
if ($response['code'] == 200) {
    $_SESSION['staff_id'] = $response['data']['staff_id'];
//    var_dump($response);
    header('location: staff_home.php');
}
else{
    $_SESSION['error'] = $response['message'];
    header('location: staff_login.php');
}
