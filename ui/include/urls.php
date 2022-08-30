<?php
class Urls
{
    //Patient Microservice
    public $patientURL = "http://localhost:5001/patient";
    public $appointmentURL = "http://localhost:5001/appointment";
    public $medicalURL = "http://localhost:5001/medical";
    public $authenticatePatientURL = "http://localhost:5001/authenticate_patient";

    //Doctor Microservice
    public $doctorURL = "http://localhost:5000/doctor";
    public $availabilityURL = "http://localhost:5000/availability";
    public $authenticateDoctorURL = "http://localhost:5000/authenticate_doctor";

    //Staff Microservice
    public $authenticateStaffURL = "http://localhost:5004/authenticate_staff";

    //Scheduling Microservice
    public $schedulingURL = "http://localhost:5003/scheduling";
    public $schedulingDisplayURL = "http://localhost:5003/display";
    public $schedulingUpdateURL = "http://localhost:5003/update";
//    public $schedulingURL = "http://localhost:5003/scheduling";

    /**
     * @return string
     */
    public function getSchedulingDisplayURL()
    {
        return $this->schedulingDisplayURL;
    }

    /**
     * @return string
     */
    public function getSchedulingUpdateURL()
    {
        return $this->schedulingUpdateURL;
    }

    /**
     * @return string
     */
    public function getAuthenticatePatientURL()
    {
        return $this->authenticatePatientURL;
    }

    /**
     * @return string
     */
    public function getAuthenticateDoctorURL()
    {
        return $this->authenticateDoctorURL;
    }

    /**
     * @return string
     */
    public function getAuthenticateStaffURL()
    {
        return $this->authenticateStaffURL;
    }

    /**
     * @return string
     */
    public function getPatientURL()
    {
        return $this->patientURL;
    }

    /**
     * @return string
     */
    public function getAppointmentURL()
    {
        return $this->appointmentURL;
    }

    /**
     * @return string
     */
    public function getMedicalURL()
    {
        return $this->medicalURL;
    }

    /**
     * @return string
     */
    public function getDoctorURL()
    {
        return $this->doctorURL;
    }

    /**
     * @return string
     */
    public function getAvailabilityURL()
    {
        return $this->availabilityURL;
    }

    /**
     * @return string
     */
    public function getSchedulingURL()
    {
        return $this->schedulingURL;
    }


}



