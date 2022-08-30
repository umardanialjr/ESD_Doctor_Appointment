<?php
include '../stripe-php-master/init.php';
include_once "include/common.php";
include_once "include/curl.php";
include_once "include/urls.php";

$urls = new Urls();

$patientid = $_SESSION['patient_id'];

//$patientid = 20;

// callAPI returns a string in JSON format
$user = callAPI("GET",$urls->getSchedulingDisplayURL()."/patient/".$patientid,false);

// json_decode transforms it into an associative array; $user_data is now an associative array
$user_data = json_decode($user,True);

// Json format returns code,message and data. Right here we only want to extracrt the things inside 'data'
//$medical_info = $medical_list_data['data'];
// $appt_info

//var_dump($user_data);
$medical_info = $user_data['medicaldata'];
$appointment_info = $user_data['appointmentdata'];
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
            <li class="breadcrumb-item active">Profile</li>
            <li class="breadcrumb-item "><a href="book_appointment.php">Book Appointments</a></li>
            <li class="breadcrumb-item active"><a href="logout.php">Logout</a></li>
        </ol>

        <div id="details" class="shadow mr-4 pt-3" style="position:relative; height:150px;width:100%; background-color:white;">
        
            <img src="static/img/profile_image.png" alt="profile icon" align="left" style="width:120">

            <!--Name, Patient ID, Phone Number -->
            <div style="margin: 4px; width:130px; height: 120px; float:left;" align="left" >
                    <p style="text-align: right">Name:</p>
                    <p style="text-align: right">Patient ID:</p>
                    <p style="text-align: right">Phone Number:</p>
            </div>
            <!--Retrieve data from DB-->
            <div style="margin: 4px; margin-left: 15px; width:350px; height: 120px; float:left;" align="left" >
                    <p><?=$user_data['patientdata']['patient_fname'].$user_data['patientdata']['patient_lname'] ?> </p>
                    <p><?=$user_data['patientdata']['patient_id']?></p>
                    <p><?=$user_data['patientdata']['patient_phone']?></p>
            </div>

            <!--Email, Telegram Handle -->
            <div style="margin: 4px; width: 130px; height: 120px; float:left;" align="right" >
                <p style="text-align: right">Email: </p>
                <p style="text-align: right">Telegram Handle: </p>
            </div>
            <!--Retrieve data from DB-->
            <div style="margin: 4px; margin-left: 15px; width: 350px; height: 120px; float:left;" align="left" >
                <p><?=$user_data['patientdata']['patient_email']?></p>
                <p><?=$user_data['patientdata']['patient_telehandle']?></p>
            </div>
        
        </div>
    <!-- </div> -->
        <br>
        <button type="button" class="btn btn-primary" onclick="location.href = 'book_appointment.php';">Book an appointment</button>

        <div id="med_record" class="shadow mr-4" style="position:relative; width:100%; margin-top:25px; padding:15px;">
            <div><h4>Upcoming appointments</h4></div>
            <table class="table table-bordered" style="width:100%;";>
                <!-- <table style="border: 5px solid #990000;"> -->

                <tbody>
                <tr>
                    <th width="15%">Appointment ID</th>
                    <th width="25%">Date and Time</th>
                    <th width="15%">Status</th>
                </tr>

                <?php
                foreach ($appointment_info as $row){
                    $dateTimeNow = new DateTime('now');
                    $dateTime = datetime::createfromformat('D, d M Y H:i:s e',$row['appointment_datetime']);
                    $time = $dateTime->format('D, d M Y H:i');
                    if ($dateTime > $dateTimeNow){
                        echo "<tr>";
                        echo "<td>". $row['appointment_id'] ."</td>";
                        echo "<td>". $time ."</td>";
                        echo "<td>". $row['status'] ."</td>";
                        echo"</tr>";
                    }
                }
                ?>
                </tbody>

            </table>



            <!-- </div> End of my div id=details -->
        </div>


        <!-- My Medical Records -->
        <div id="med_record" class="shadow mr-4" style="position:relative; width:100%; margin-top:25px; padding:15px;">
            <div><h4>Completed appointments</h4></div>
        <table class="table table-bordered" style="width:100%;";>
        <!-- <table style="border: 5px solid #990000;"> -->

            <tbody>
            <tr>
                <th width="15%">Appointment ID</th>
                <th width="25%">Date and Time</th>
                <th width="30%">Description Notes</th>
                <th width="15%">Status</th>
                <th width="15%">Payment</th>
            </tr>

            <?php 
                foreach ($medical_info as $row){
                    $dateTime = datetime::createfromformat('D, d M Y H:i:s e',$row['appointment_datetime']);
                    $time = $dateTime->format('D, d M Y H:i');
                    echo "<tr>";
                    echo "<td>". $row['appointment_id'] ."</td>";
                    echo "<td>". $time ."</td>";
                    echo "<td>". $row['description'] ."</td>";
                    echo "<td>". $row['status'] ."</td>";
                    echo "<td><button id='checkout-button' value='".$row['appointment_id']."'>Make Payment</button></td>"; #must be a href to Stripe url
                    echo"</tr>";
                }
            ?>
            </tbody>
     
        </table>


        


        <!-- </div> End of my div id=details -->
    </div>

        <div id="med_record" class="shadow mr-4" style="position:relative; width:100%; margin-top:25px; padding:15px;">
            <div><h4>All appointments</h4></div>
            <table class="table table-bordered" style="width:100%;";>
                <!-- <table style="border: 5px solid #990000;"> -->

                <tbody>
                <tr>
                    <th width="15%">Appointment ID</th>
                    <th width="25%">Date and Time</th>
                    <th width="15%">Status</th>
                </tr>

                <?php
                foreach ($appointment_info as $row){
                    $dateTime = datetime::createfromformat('D, d M Y H:i:s e',$row['appointment_datetime']);
                    $time = $dateTime->format('D, d M Y H:i');
                    echo "<tr>";
                    echo "<td>". $row['appointment_id']."</td>";
                    echo "<td>". $time ."</td>";
                    echo "<td>". $row['status'] ."</td>";
                    echo"</tr>";
                }
                ?>
                </tbody>

            </table>



            <!-- </div> End of my div id=details -->
        </div>
    <br><br><br>
    <div>

    </div>
    
    <!--<script type="text/javascript">
    // Create an instance of the Stripe object with your publishable API key
    // Click on 'Make Payment' Button will link to Stripe's checkout page
    var stripe = Stripe("pk_test_51IXmFGAB2qhMc6CzsR23S3wE7B48Ic2OgtD69vmDuJVrMGpIrr9mKCqbWK8aTEBaNCTwPKmiPIvLrlpVEEDWfLK600Myf4NPbw");
    var checkoutButton = document.getElementById("checkout-button");
    checkoutButton.addEventListener("click", function () {
      fetch(, {'/create-checkout-session.php', {
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
        console.log(session);
    });
    </script>
    !-->

    
    <script type="text/javascript">
        // Create an instance of the Stripe object with your publishable API key
        var stripe = Stripe('pk_test_51IXmFGAB2qhMc6CzsR23S3wE7B48Ic2OgtD69vmDuJVrMGpIrr9mKCqbWK8aTEBaNCTwPKmiPIvLrlpVEEDWfLK600Myf4NPbw');
        var checkoutButton = document.getElementById('checkout-button');
        // var appointment_id = checkoutButton.getAttribute('value').toString();
        // console.log(appointment_id)
  
        checkoutButton.addEventListener('click', function() {
          // Create a new Checkout Session using the server-side endpoint you
          // created in step 3.
          fetch('http://localhost:5002/create-checkout-session', {
            method: 'POST',
          })
          .then(function(response) {
            return response.json();
          })
          .then(function(session) {
            return stripe.redirectToCheckout({ sessionId: session.id});
          })
          .then(function(result) {
            // If `redirectToCheckout` fails due to a browser or network
            // error, you should display the localized error message to your
            // customer using `error.message`.
            if (result.error) {
              alert(result.error.message);
            }
          })
          .catch(function(error) {
            console.error('Error:', error);
          });
        });
    </script>

</body>
    

</html>