# ESD_Doctor_Patient_appointment
 Microservice Architecture

Application Setup

1. Additional python modules installed
The solution proposed uses additional python modules

Below are all the additional python modules used:

Flask==1.1.2
Flask-Cors==3.0.10
Flask-SQLAlchemy==2.4.4
mysql-connector-python==8.0.23
requests==2.25.1
SQLAlchemy==1.3.22
pyTelegramBotAPI==3.7.6
jsonify==0.5
pika==1.2.0

If running the application with docker, there will be no need to manually install the modules as docker compose will
install the required modules.

========================================================================================================================

2. Microservice and Docker container set up

The application is designed to run with docker. A docker-compose file is created for convenience.
To further speed up the process of building the images and deployment of containers, a batch file for Windows machines
and a shell script for MacOs and Linux machines has been written.

Prerequisite:

    The microservices are running on the following port numbers.

        Services    | Port
        ============|=======
        doctor      | 5000
        patient     | 5001
        payment     | 5002
        scheduling  | 5003
        staff       | 5004
        telegram    | 5009
        rabbitmq    | 5672 and 15672

    Ensure no other services are using the same port numbers.
    If there are additional containers running on the ports, ensure that the containers are stopped.

    You may use the following command to stop all containers:
        $docker ps -q


2.1 Docker-compose Database
IMPORTANT NOTE: Team will take down the RDS server on 23rd April

The application is built with AmazonRDS MySQL Database. By default, if the dbURL in docker-compose.yml is not specified,
the application will connect to:

    Endpoint URL: database-1.ckxrhyi6ulnv.ap-southeast-1.rds.amazonaws.com
    Username: admin
    Password: esdDBpass1
    Port: 3306

The RDS server has sample data inserted
The SQL scripts have been included in the SQL folder with the relevant test data.
The SQL scripts can be executed in any order


2.2.1 Setup using bat or sh file
    Steps:
    
        1. Navigate to the directory containing run.bat or run.sh
        2. Change permissions for file to ensure user can execute file

            Execute the command:
            For Windows
            $chmod 700 run.bat
            -or-
            For Linux/MacOs
            $chmod 700 run.sh

        3. To start the containers execute the file.

            Execute the script with:
            For Windows
            $./run.bat
            -or-
            For Linux/MacOs
            $./run.sh

        4. Wait for 30 seconds for the containers to start
        NOTE: The rabbitMQ container takes awhile to start up and the Scheduling and notification microservices depend
        on it the batch/shell script will automatically start the 2 containers after a 30 second delay

        5. Ensure that there are 8 containers running

            To view all running containers use:
            $docker compose ps

        NOTE: If it is the first setup, ensure that the exchange and queues are setup in rabbitMQ before proceeding,
        you may refer to section 2.2 RabbitMQ. After setting up the exchanges and queues

            Execute the following commands to start the remaining 2 containers:

            Method 1:
            $docker start esd_doctor_patient_appointment_Scheduling_1
            $docker start esd_doctor_patient_appointment_notification_1

            -or-

            Method2:
            For Windows
            $./run.bat
            -or-
            For Linux/MacOs
            $./run.sh

Alternatively, you may start the containers manually

2.2.2 Setup using docker compose manually
    Steps:
    
        1. Navigate to the directory containing docker-compose.yml
        2. Build the docker images

            Execute the command:
            $docker compose build

        3. Start the containers

            Execute the command:
            $docker compose up -d

        4. Wait for 30 seconds for the containers to start

            NOTE: The rabbitMQ container takes awhile to start up and the Scheduling and notification microservices
            depend on it. A 30 second delay will ensure that the rabbitMQ container is running before the other 2
            services are started.

        5. To view all running containers

            Execute the command:
            $docker compose ps

        6. Ensure that there are 6 containers running
        7. To start the remaining 2 containers that depend on RabbitMQ
            NOTE: If it is the first setup, ensure that the exchange and queues are setup before proceeding
            Execute the following commands:
            $docker start esd_doctor_patient_appointment_Scheduling_1
            $docker start esd_doctor_patient_appointment_notification_1


Should the containers esd_doctor_patient_appointment_Scheduling_1 and esd_doctor_patient_appointment_notification_1 not
start, it is due to rabbitMQ not having the correct exchanges and queues.


2.3 RabbitMQ setup
    Steps:
    
        1. Ensure the rabbitMQ container is running
        2. Access the UI with localhost:15762
        3. Login with the following credentials
            username:guest
            password:guest
        4. Create a queue with the following attributes
            Name: notification
            Type: Classic
            Durability: Durable
        5. Create an exchange with the following attributes
            Name: notification_direct
            Type: Direct
            Durability: Durable
        6. Bind the queue to the exchange with the following attributes:
            To queue: notification
            Routing Key: notification.rk

========================================================================================================================

3. Web application setup

The UI side of the application is hosted on a local web server. It is built with a WAMP/MAMP stack in mind.
Copy the files and folders in the ui folder to the webroot of WAMP or MAMP.

    In a WAMP stack, the webroot folder is by default www
    In a MAMP stack, the webroot directory is by default htdocs

Access the application via localhost/home.php or localhost:<port_number>/home.php if a custom port number is used.

========================================================================================================================

4. User Credentials

For login for the various entities:

    Patient:
        Patient Name: c
        Phone: 999

        Patient Name: shah
        Phone: 97399245
        NOTE: Email and telegram works/ Real email addresses and telegram handle added.

        Patient Name: Markus
        Phone: 888
        NOTE: Email and telegram works/ Real email addresses and telegram handle added.

        Alternatively to test telegram and email notifications, you may register for a new patient account.

    Doctor:
        DoctorId: 1
        Phone: 98520390

        DoctorId: 2
        Phone: 87769898

    Staff:
        StaffId: 1
        Phone: 98765432

========================================================================================================================
