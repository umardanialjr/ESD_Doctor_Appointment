<?php
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();

$patientid = $_GET['patient_id'];
$appointmentid = $_GET['appointment_id'];

//// FOr testing
//$patientid = 2;
//$appointmentid = 2;

// callAPI returns a string in JSON format
$user = callAPI("GET",$urls->getPatientURL()."/".$patientid,false);
$appointment = callAPI("GET",$urls->getAppointmentURL()."/".$appointmentid,false);
//$medical_list = callAPI("GET","localhost:5001/medical/patientid/".$patientid,false);

// json_decode transforms it into an associative array; $user_data is now an associative array
$user_data = json_decode($user,True);
$appointment_data = json_decode($appointment,True);
//$medical_list_data = json_decode($medical_list,True);

// Json format returns code,message and data. Right here we only want to extracrt the things inside 'data'
//$medical_info = $medical_list_data['data'];
// echo $appt_info

?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel= "stylesheet" type= "text/css" href= "{{ url_for('static',filename='css/styles.css') }}">
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <nav class="navbar navbar-light bg-light">
        <img src="static/img/esd_logo1.png" alt="esd logo">
    </nav>


    <div class="card-body" style="position:relative;">

        <ol class="breadcrumb my-4 mt-0">
          <li class="breadcrumb-item"><a href="doctor_shift.php">Add Availability</a></li>
          <li class="breadcrumb-item"><a href="doctor_schedule.php">Schedule</a></li>
          <li class="breadcrumb-item"><a href="doctor_overview.php">View Availability</a></li>
          <li class="breadcrumb-item active">Add Description</li>
            <li class="breadcrumb-item"><a href="logout.php">Logout</a></li>
        </ol>

        <div id="details" class="shadow mr-4" style="position:relative; height:150px;width:100%;">

        <form method="POST" action="doctor_insert_desc_process.php" class="insert-desc-form" id="desc_form">

        
            <img src="static/img/profile_image.png" alt="profile icon" align="left" style="width:120">

            <!-- Appointment DateTime, Appointment ID -->
            <div style="margin: 4px; width:200px; height: 120px; float:left;" align="left" >
                    <p style="text-align: right">Appointment Date & Time:</p>
                    <p style="text-align: right">Appointment ID:</p>
            </div>
            <!--Retrieve data from DB-->
            <div style="margin: 4px; margin-left: 15px; width:350px; height: 120px; float:left;" align="left" >
                    <p><?=$appointment_data['data']['appointment_datetime']?> </p>
                    <p><?=$appointment_data['data']['appointment_id']?> </p>
            </div>

            <!-- Name, Patient ID -->
            <div style="margin: 4px; width: 130px; height: 120px; float:left;" align="right" >
                <p style="text-align: right">Name: </p>
                <p style="text-align: right">Patient ID: </p>
            </div>
            <!--Retrieve data from DB-->
            <div style="margin: 4px; margin-left: 15px; width: 350px; height: 120px; float:left;" align="left" >
                <p><?=$user_data['data']['patient_fname']." ".$user_data['data']['patient_lname'] ?> </p>
                <p><?=$user_data['data']['patient_id']?></p>
            </div>
        
        </div> <!-- End of id=details -->

        <input type="hidden" id="patient_id" name="patient_id" value=<?=$patientid?>>
        <input type="hidden" id="appointment_id" name="appointment_id" value=<?=$appointmentid?>>

        <!-- Doctor's Description to insert -->
        <div id="doc_desc" class="shadow mr-4" style="position:relative; width:100%; margin-top:25px">
          <div class="form-group pl-4 pt-3 pb-3" style="width:60%;">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" form="desc_form" rows="6"></textarea>
            <button class="btn btn-primary" style="margin-top: 10px;"type="submit">Submit</button>
          </div>
      </div>

    </form>
        
           
    </div> <!-- end of class=card-body -->
    
    <script type="text/javascript">
    // Create an instance of the Stripe object with your publishable API key
    // Click on 'Make Payment' Button will link to Stripe's checkout page
    var stripe = Stripe("pk_test_51IXmFGAB2qhMc6CzsR23S3wE7B48Ic2OgtD69vmDuJVrMGpIrr9mKCqbWK8aTEBaNCTwPKmiPIvLrlpVEEDWfLK600Myf4NPbw");
    var checkoutButton = document.getElementById("checkout-button");

    checkoutButton.addEventListener("click", function () {
      fetch("http://localhost/ESD_Doctor_Patient_appointment/ui/create-checkout-session.php", {
        method: "POST",
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (session) {
          return stripe.redirectToCheckout({ sessionId: session.id });
        })
        .then(function (result) {
          // If redirectToCheckout fails due to a browser or network
          // error, you should display the localized error message to your
          // customer using error.message.
          if (result.error) {
            alert(result.error.message);
          }
        })
        .catch(function (error) {
          console.error("Error:", error);
        });
    });
    </script>

</body>
    

</html>