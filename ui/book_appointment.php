<?php
    include_once "include/common.php";
    include_once "include/curl.php";
    include_once "include/urls.php";

    $urls = new Urls();

    date_default_timezone_set ("Asia/Singapore");
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel= "stylesheet" type= "text/css" href= "static/css/styles.css">
        
    </head>

    <?php
        $blocked_dates = [];

        if (isset($_SESSION['data'])) {
           $blocked_dates = $_SESSION['data'];
        }

        if (isset($_SESSION['date'])){
            $date = $_SESSION['date'];
        }
        else{
            $date = date("Y-m-d");
            $_SESSION['date'] = $date;            
        }

        $patient_id = $_SESSION['patient_id'];

    ?>
    <body>
    
        <nav class="navbar navbar-light bg-light">
            <img src="static/img/esd_logo1.png"/>
        </nav>
        <div class="card-body" style="position:relative;">
            <ol class="breadcrumb my-4 mt-0">
                <li class="breadcrumb-item"><a href="profile.php">Profile</a></li>
                <li class="breadcrumb-item active">Book Appointments</li>
                <li class="breadcrumb-item"><a href="logout.php">Logout</a></li>
            </ol>
            <div class="shadow p-4">
                <?php
                if (isset($_SESSION['success'])) {
                    echo "<div class='alert alert-success'>".$_SESSION['success']."</div>";
                    unset($_SESSION['success']);
                }

                if (isset($_SESSION['error'])) {
                    echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
                    unset($_SESSION['error']);
                }
                ?>


            <form method="GET" action="book_appointment_process.php" style="width:100%;" class="mt-1 mb-0" name="form1">
                <div class="avail-container d-flex flex-column" style="position: relative; width:100%;">
                    <input style=" d-flex justify-content-center ;width:50%;"type="date" class="date-class" id="date-id" name="date">
                    <button type="submit"class="btn btn-success btn-lg btn-block mt-4" onclick="document.forms[0].submit()">Refresh Date</button>
            </form>
                    <div class="container-fluid">
                    <div class="card mt-3 overflow-auto" style="width:100%;">
                        <div class="card-body d-flex flex-row">
                            <div class="appointment-table" id="populate_button">
                                <!-- Populate this with buttons of available dates. If they are blocked, disable the button. -->
                                <h5>Book Appointment for: <?=$date?></h5>
                                <table class="card-table table table-borderless mt-2 mb-2" style="width:100%; display:flex; justify-content: space-evenly; padding:10px;">
                                    <tr>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="10:00:00" value="10:00:00" onclick="bookAppointment(this.value)">10:00</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="10:15:00" value="10:15:00" onclick="bookAppointment(this.value)">10:15</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="10:30:00" value="10:30:00" onclick="bookAppointment(this.value)">10:30</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="10:45:00" value="10:45:00" onclick="bookAppointment(this.value)">10:45</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="11:00:00" value="11:00:00" onclick="bookAppointment(this.value)">11:00</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="11:15:00" value="11:15:00" onclick="bookAppointment(this.value)">11:15</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="11:30:00" value="11:30:00" onclick="bookAppointment(this.value)">11:30</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="11:45:00" value="11:45:00" onclick="bookAppointment(this.value)">11:45</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="12:00:00" value="12:00:00" onclick="bookAppointment(this.value)">12:00</button></a></td>
                                    </tr>
                                    <tr>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="12:15:00" value="12:15:00" onclick="bookAppointment(this.value)">12:15</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="12:30:00" value="12:30:00" onclick="bookAppointment(this.value)">12:30</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="12:45:00" value="12:45:00" onclick="bookAppointment(this.value)">12:45</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="14:00:00" value="14:00:00" onclick="bookAppointment(this.value)">14:00</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="14:15:00" value="14:15:00" onclick="bookAppointment(this.value)">14:15</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="14:30:00" value="14:30:00" onclick="bookAppointment(this.value)">14:30</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="14:45:00" value="14:45:00" onclick="bookAppointment(this.value)">14:45</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="15:00:00" value="15:00:00" onclick="bookAppointment(this.value)">15:00</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="15:15:00" value="15:15:00" onclick="bookAppointment(this.value)">15:15</button></a></td>
                                    </tr>
                                    <tr>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="15:30:00" value="15:30:00" onclick="bookAppointment(this.value)">15:30</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="15:45:00" value="15:45:00" onclick="bookAppointment(this.value)">15:45</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="16:00:00" value="16:00:00" onclick="bookAppointment(this.value)">16:00</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="16:15:00" value="16:15:00" onclick="bookAppointment(this.value)">16:15</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="16:30:00" value="16:30:00" onclick="bookAppointment(this.value)">16:30</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="16:45:00" value="16:45:00" onclick="bookAppointment(this.value)">16:45</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="17:00:00" value="17:00:00" onclick="bookAppointment(this.value)">17:00</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="17:15:00" value="17:15:00" onclick="bookAppointment(this.value)">17:15</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="18:30:00" value="18:30:00" onclick="bookAppointment(this.value)">18:30</button></a></td>
                                    </tr>
                                    <tr>
                                        <!-- Original -->
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="18:45:00" value="18:45:00" onclick="bookAppointment(this.value)">18:45</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="19:00:00" value="19:00:00" onclick="bookAppointment(this.value)">19:00</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="19:15:00" value="19:15:00" onclick="bookAppointment(this.value)">19:15</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="19:30:00" value="19:30:00" onclick="bookAppointment(this.value)">19:30</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="19:45:00" value="19:45:00" onclick="bookAppointment(this.value)">19:45</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="20:00:00" value="20:00:00" onclick="bookAppointment(this.value)">20:00</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="20:15:00" value="20:15:00" onclick="bookAppointment(this.value)">20:15</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="20:30:00" value="20:30:00" onclick="bookAppointment(this.value)">20:30</button></a></td>
                                        <td><button type="button" class="btn btn-primary btn-time" type="button" id="20:45:00" value="20:45:00" onclick="bookAppointment(this.value)">20:45</button></a></td>
                                       

                                    </tr>
                                </table>


                            </div>
                        </div>

                        
                    </div>
                    <div>
                </div>
                
        </div>





        <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Book appointment
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm appointment booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="book_appointment_process.php" >
                    <div class="modal-body" id="addModelContentHere">
                        <p>Opps. Looks like you haven't selected a date on the calendar or a appointment timeslot..</p>
                    </div>

                    
                </form>
                

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
            </div>
            


        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
       
    </body>

    <script>
        
        
        var dataBlocked = <?php echo json_encode($blocked_dates);  ?>;
        

        var dateobject = new Date(<?php echo strtotime($date) * 1000 ?>)
        var dateFormated = dateobject.toDateString()

        
        var patientId = <?php echo $patient_id  ?>;

        


        blockDates();
        function blockDates(){

            // Make a button for all timeslots. Unless the timeslot is in the array, you disable the button.
            var button_html = "";

            for (var date of dataBlocked ){
                var button = document.getElementById(date);
                button.setAttribute("class", "btn btn-danger disabled");

            }
        }

        function bookAppointment(time){


            var modelContent = document.getElementsByClassName("btn-time");
            for (let i = 0; i < modelContent.length; i++) {
                // Change all buttons to primary
                var button = modelContent[i];
                //console.log(button);
                var att = document.createAttribute("class");
                att.value = "btn btn-primary btn-time ";
                button.setAttributeNode(att);
            }

            //Button selected is now green
            var button = document.getElementById(time);
            button.setAttribute("class" , "btn btn-success btn-time");

            // Block the dates that are booked
            blockDates();

            // var hrefURL = 'process_booking.php?patient_id='+patientId+"&date="+dateString+"&time="+time

            var modelContent = document.getElementById("addModelContentHere");
            s= `
                <h1>Booking Confirmation</h1>
                <br>
                <hr>
                    <p>The time stated is here ${time}. Confirm your appointment?</p>
                    <p>${patientId}</p>
                    <p>${dateFormated}</p>
                    <p>${time}</p>


                    <div class="form-group">
                        <input type="hidden" class="form-control" name="id" value="${patientId}">
                    </div>

                    <div class="form-group">
                        <input type="hidden" class="form-control" name="time" value="${time}">
                    </div>

                    <div class="form-group">
                        <input type="hidden" class="form-control" name="dateString" value="${dateFormated}">
                    </div>

                    <button type="submit" class="btn btn-success btn-lg btn-block mt-4" onclick="document.forms[1].submit()" >Confirm Booking</button>

                `;
            //<input type="hidden" name="date" value="${dateobject}">
            modelContent.innerHTML= s;


        }

        var myModal = document.getElementById('myModal');
        var myInput = document.getElementById('myInput');

        myModal.addEventListener('shown.bs.modal', function () {
        myInput.focus()
        })




    </script>
    
</html>