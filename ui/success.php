<html>
    <head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel= "stylesheet" type= "text/css" href= "static/css/styles.css">

        <script>
            <?php
            include '../stripe-php-master/init.php';
            include_once "include/common.php";
            include_once "include/curl.php";
            \Stripe\Stripe::setApiKey('sk_test_51IXmFGAB2qhMc6CzNkZqLOFpfjVTuyenmW0V67nbedIIXMcBQIuAl2B8GnnmSxIuMDgOx3JS9S2tEOTC1jk8GS5500YaL9ssyi');
        
            //function to populate payment_service db's payment table {stripe_id, appointment_id, patient_id, amount}
            function insert_payment() {
//                admin:esdDBpass1@database-1.ckxrhyi6ulnv.ap-southeast-1.rds.amazonaws.com
                $servername = "database-1.ckxrhyi6ulnv.ap-southeast-1.rds.amazonaws.com";
                $username = "admin";
                $password = "esdDBpass1";
                $dbname = "payment_service";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check connection
                if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
                }
                
                //get payment intent id, insert into table as "stripe_id"
                $stripe = new \Stripe\StripeClient(
                'sk_test_51IXmFGAB2qhMc6CzNkZqLOFpfjVTuyenmW0V67nbedIIXMcBQIuAl2B8GnnmSxIuMDgOx3JS9S2tEOTC1jk8GS5500YaL9ssyi'
                );
                $latest_pi = $stripe->paymentIntents->all(['limit' => 1]);
                $piid = $latest_pi['data'][0]['id'];

                //Use sessions to get appointment_id and patient_id
//                $appointmentid = $_SESSION['appointment_id'];
                $patientid = $_SESSION['patient_id'];
                $appointmentid = 0;
//                $patientid = 2;

                //Inserting values into payment table
                $sql = "INSERT INTO payment (stripe_id,appointment_id,patient_id,amount) VALUES ('$piid', '$appointmentid' , '$patientid' , 20.0)";

                if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
                } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                }

                $conn->close();
            }
            ?>
        </script>

    </head>
    <body onload="<?php insert_payment() ?>">
        <nav class="navbar navbar-light bg-light">
        <img src="static/img/esd_logo1.png"/>
        </nav>
        <div class="card-body" style="position:relative;">
            <ol class="breadcrumb my-4 mt-0">
                <li class="breadcrumb-item active">Payment</li>
            </ol>
            <div class="payment-completion" style="display:flex; flex-direction:column; align-items: center;">
              <p class="pt-3" style="font-size: 35px;">Payment has been completed successfully!</p>
              <i class="fa fa-check-circle fa-5x" style="color: green;"aria-hidden="true"></i>
              <a href="profile.php" class="pt-3"><button class="btn btn-primary btn-lg">Go Back to Profile Page</button></a>
            </div>
        </div>
    </body>
</html>
