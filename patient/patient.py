from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy import extract, and_
import datetime, json
from os import environ
from flask_cors import CORS

# app initialisation and configuration

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = environ.get('dbURL') or 'mysql+mysqlconnector://admin:esdDBpass1@database-1.ckxrhyi6ulnv.ap-southeast-1.rds.amazonaws.com:3306/patient_service'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)
CORS(app)


class Patient(db.Model):
    # can be used to change table name from class name, in this case it is the same
    __tablename__ = 'patient_info'

    patient_id = db.Column(db.Integer, primary_key=True)
    patient_fname = db.Column(db.String(255), primary_key=False)
    patient_lname = db.Column(db.String(255), primary_key=False)
    patient_phone = db.Column(db.String(8), primary_key=False)
    patient_telehandle = db.Column(db.String(255), primary_key=False)
    patient_email = db.Column(db.String(255), primary_key=False)
    chat_id = db.Column(db.String(64), primary_key=False)
    pass

    def __init__(self, patient_id, patient_fname, patient_lname, patient_phone, patient_telehandle, patient_email,chat_id):
        self.patient_id = patient_id
        self.patient_fname = patient_fname
        self.patient_lname = patient_lname
        self.patient_email = patient_email
        self.patient_phone = patient_phone
        self.patient_telehandle = patient_telehandle
        self.chat_id = chat_id

    def json(self):
        return {"patient_id": self.patient_id,
                "patient_fname": self.patient_fname,
                "patient_lname": self.patient_lname,
                "patient_email": self.patient_email,
                "patient_phone": self.patient_phone,
                "patient_telehandle": self.patient_telehandle,
                "chat_id": self.chat_id
                }


class Appointment(db.Model):
    # can be used to change table name from class name, in this case it is the same
    __tablename__ = 'patient_appointment'

    appointment_id = db.Column(db.Integer, primary_key=True)
    patient_id = db.Column(db.Integer, primary_key=False)
    appointment_datetime = db.Column(db.DateTime, primary_key=False)
    status = db.Column(db.String(255), primary_key=False)

    def __init__(self, appointment_id ,patient_id, appointment_datetime, status):
        self.patient_id = patient_id
        self.appointment_id = appointment_id
        self.appointment_datetime = appointment_datetime
        self.status = status

    def json(self):
        return {"patient_id": self.patient_id,
                "appointment_id": self.appointment_id,
                "appointment_datetime": self.appointment_datetime,
                "status": self.status
                }


class Medical(db.Model):
    # can be used to change table name from class name, in this case it is the same
    __tablename__ = 'patient_medical'

    medical_record_id = db.Column(db.Integer, primary_key=True)
    patient_id = db.Column(db.Integer, primary_key=False)
    appointment_id = db.Column(db.Integer, primary_key=False)
    description = db.Column(db.String(4096), primary_key=False)

    def __init__(self, medical_record_id, patient_id, appointment_id, description):
        self.patient_id = patient_id
        self.appointment_id = appointment_id
        self.medical_record_id = medical_record_id
        self.description = description

    def json(self):
        return {"patient_id": self.patient_id,
                "appointment_id": self.appointment_id,
                "medical_record_id": self.medical_record_id,
                "description": self.description
                }


################### Patient Class #############################
@app.route("/authenticate_patient", methods = ["POST"])
def authenticate():
    # Obtain the parameters sent to the function
    patient_login = request.get_json()
    patient_phone = patient_login["patient_phone"]
    patient_fname = patient_login["patient_fname"]

    # Query the username posted to check if it exists
    try:
        patient = Patient.query.filter(and_(Patient.patient_phone == patient_phone, Patient.patient_fname == patient_fname)).first()
        if patient:
            return jsonify(
                {
                    "code": 200,
                    "data": patient.json(),
                    "message": "User exists"
                }
            ), 200
        return jsonify(
            {
                "code": 404,
                "message": "User does not exist within the database",
                "data": []
            }
        ), 404

    except Exception as e:
        print(str(e))
        return jsonify(
            {
                "code": 500,
                "message": "Server Error",
                "data": []
            }
        ), 500


# Retrieving all patients
@app.route("/patient")
def get_all_patients():
    patient_list = Patient.query.all()
    if len(patient_list):
        return jsonify(
            {
                "code": 200,
                "message": "Patient list found.",
                "data": {
                    "patients": [patient.json() for patient in patient_list]
                }
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "There are no patients in the list.",
            "data": []
        }
    ), 404


# Retreving patient based on patient_id entered
@app.route("/patient/<string:patient_id>")
def find_by_patient_id(patient_id):
    patient = Patient.query.filter_by(patient_id=patient_id).first()
    if patient:
        return jsonify(
            {
                "code": 200,
                "message": "Patient found.",
                "data": patient.json()
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "Patient not found.",
            "data": []
        }
    ), 404


# Update patient based on telehandle entered
@app.route("/patient/tele/<string:patient_telehandle>", methods=['PUT'])
def update_by_patient_telehandle(patient_telehandle):
    patient = Patient.query.filter_by(patient_telehandle=patient_telehandle).first()
    if patient:
        data = request.get_json()
        data = json.loads(data)

        if data['chat_id']:
            patient.chat_id = data['chat_id']
        try:
            db.session.commit()
            return jsonify(
                {
                    "code": 200,
                    "message": "Patient chat data updated.",
                    "data": patient.json()
                }
            ), 200
        except:
            return jsonify(
                {
                    "code": 500,
                    "message": "Server error try again later"
                }
            ), 500

    return jsonify(
        {
            "code": 404,
            "message": "Patient not found.",
            "data": []
        }
    ), 404

#Retrieving patient based on patient_telegrandle
@app.route("/patient/tele/<string:patient_telehandle>")
def find_by_patient_telehandle(patient_telehandle):
    patient = Patient.query.filter_by(patient_telehandle=patient_telehandle).first()
    if patient:
        return jsonify(
            {
                "code": 200,
                "message": "Patient found.",
                "data": patient.json()
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "Patient not found.",
            "data": []
        }
    ), 404



# Creating/Inserting patient into DB
@app.route("/patient", methods=['POST'])
def create_patient():
    data = request.get_json()

    patient = Patient(patient_id=None, **data)

    try:
        db.session.add(patient)
        db.session.commit()

    except:
        return jsonify(
            {
                "code": 500,
                "message": "An error occurred creating the patient. Please try again later.",
                "data": data
            }
        ), 500
    return jsonify(
        {
            "code": 201,
            "message": "Patient created.",
            "data": patient.json()
        }
    ), 201


################### Appointment Class #############################

# Retrieving all Appointment records 
@app.route("/appointment")
def get_all_appointments():
    appointment_list = Appointment.query.all()
    if len(appointment_list):
        return jsonify(
            {
                "code": 200,
                "message": "Appointment list found.",
                "data": {
                    "appointments": [appointment.json() for appointment in appointment_list]
                }
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "There are no appointments in the list.",
            "data": []
        }
    ), 404


# Retrieving appointment based on appointment_id entered
@app.route("/appointment/<string:appointment_id>")
def find_by_appointment_id(appointment_id):
    appointment = Appointment.query.filter_by(appointment_id=appointment_id).first()
    if appointment:
        return jsonify(
            {
                "code": 200,
                "message": "Appointment found.",
                "data": appointment.json()
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "Appointment not found.",
            "data": []
        }
    ), 404


# Retrieving appointment based on appointment_datetime entered | BY RIGHT NOT DATETIME JUST DATE
@app.route("/appointment/date/<string:appointment_datetime>")
def find_appointment_by_datetime(appointment_datetime):

    query_datetime = datetime.datetime.strptime(appointment_datetime, '%d-%m-%Y')

    appointment_list = Appointment.query.filter(and_(extract('month', Appointment.appointment_datetime) == query_datetime.month,extract('year', Appointment.appointment_datetime) == query_datetime.year,extract('day', Appointment.appointment_datetime) == query_datetime.day)).order_by(Appointment.appointment_datetime.desc())
    if appointment_list:
        return jsonify(
            {
                "code": 200,
                "message": "Appointments found.",
                "data": [appointment.json() for appointment in appointment_list]
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "Appointments not found.",
            "data": []
        }
    ), 404


# Retrieving appointment based on patient_id entered
@app.route("/appointment/patientid/<string:patient_id>")
def find_appointment_by_patient_id(patient_id):

    # Query for appointments and orders in descending order.
    appointment_list = Appointment.query.filter_by(patient_id=patient_id).order_by(Appointment.appointment_datetime.desc())

    if appointment_list:
        return jsonify(
            {
                "code": 200,
                "message": "Appointments found.",
                "data": [appointment.json() for appointment in appointment_list]
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "Appointments not found.",
            "data": []
        }
    ), 404


# Retrieving appointment based on status entered
@app.route("/appointment/status/<string:status>")
def find_appointment_by_status(status):

    # Query for appointments and orders in descending order.
    appointment_list = Appointment.query.filter(Appointment.status == status).order_by(Appointment.appointment_datetime.asc())

    if appointment_list:
        return jsonify(
            {
                "code": 200,
                "message": "Appointments found.",
                "data": [appointment.json() for appointment in appointment_list]
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "Appointments not found.",
            "data": []
        }
    ), 404


# Creating/Inserting appointment into DB
@app.route("/appointment", methods=['POST'])
def create_appointment():

    data = request.get_json()
    appointment = Appointment(appointment_id=None, **data)

    try:
        db.session.add(appointment)
        db.session.commit()

    except:
        return jsonify(
            {
                "code": 500,
                "message": "An error occurred creating the appointment. Please try again later.",
                "data": data
            }
        ), 500
    return jsonify(
        {
            "code": 201,
            "message": "Appointment created.",
            "data": appointment.json()
        }
    ), 201


@app.route("/appointment/<string:appointment_id>", methods=['PUT'])
def update_appointment(appointment_id):

    appointment= Appointment.query.filter_by(appointment_id=appointment_id).first()

    if appointment:
        data = request.get_json()
        appointment.status = data['status']

        try:
            db.session.commit()

        except:
            return jsonify(
                {
                    "code": 500,
                    "message": "An error occurred updating the appointment. Please try again later.",
                    "data": data
                }
            ), 500

        return jsonify(
            {
                "code": 200,
                "message": "Appointment updated.",
                "data": appointment.json()
            }
        ), 201

    return jsonify({
            "code": 404,
            "message": "Appointment not found."
        }
    ), 404

@app.route("/appointment/day/<string:day>")
def find_medical_and_appointment_by_day(day):

    query_date = datetime.datetime.strptime(day, '%d-%m-%Y')

    # Query for appointments and orders in descending order.
    appointment_list = Appointment.query.filter(and_(extract('month', Appointment.appointment_datetime) == query_date.month,extract('year', Appointment.appointment_datetime) == query_date.year,extract('day', Appointment.appointment_datetime) == query_date.day)).order_by(Appointment.appointment_datetime.asc())
    patient_list = Patient.query.all()

    to_return = []
    for appointment in appointment_list:
        for patient in patient_list:
            if appointment.patient_id == patient.patient_id:
                new_entity = {
                    "patient_id": patient.patient_id,
                    "appointment_id": appointment.appointment_id,
                    "appointment_datetime": appointment.appointment_datetime,
                    "patient_fname": patient.patient_fname,
                    "status" : appointment.status
                }

                to_return.append(new_entity)

    if to_return:
        return jsonify(
            {
                "code": 200,
                "message": "Appointments found.",
                "data": to_return
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "Appointments not found.",
            "data": []
        }
    ), 404


################### Medical Class #############################

# Retrieving all Medical records 
@app.route("/medical")
def get_all_medical_record():
    medical_list = Medical.query.all()
    if len(medical_list):
        return jsonify(
            {
                "code": 200,
                "message": "Medical Record list found.",
                "data": {
                    "medical": [medical.json() for medical in medical_list]
                }
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "There are no medical records in the list.",
            "data": []
        }
    ), 404


# Retrieve by patientid
@app.route("/medical/patientid/<string:patient_id>")
def find_medical_record_by_patient_id(patient_id):
    appointment_list = Appointment.query.filter_by(patient_id=patient_id)
    medical_list = Medical.query.filter_by(patient_id=patient_id)
    medical_listed = []
    if medical_list:

        for medical in medical_list:
            for appointment in appointment_list:
                if appointment.appointment_id == medical.appointment_id and appointment.patient_id == medical.patient_id:
                    medical_listed.append({
                        "appointment_id": medical.appointment_id,
                        "appointment_datetime": appointment.appointment_datetime,
                        "description": medical.description,
                        "status": appointment.status
                    })

        return jsonify(
            {
                "code": 200,
                "message": "Medical Record found.",
                "data": medical_listed
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "Medical Record not found.",
            "data": []
        }
    ), 404


# Creating/Inserting medical record into DB
@app.route("/medical", methods=['POST'])
def create_medical_record():

    data = request.get_json()
    medical = Medical(medical_record_id=None, **data)

    try:
        db.session.add(medical)
        db.session.commit()

    except:
        return jsonify(
            {
                "code": 500,
                "message": "An error occurred creating the medical record. Please try again later.",
                "data": data
            }
        ), 500
    return jsonify(
        {
            "code": 201,
            "message": "Medical Record created.",
            "data": medical.json()
        }
    ), 201


if __name__ == "__main__":
    app.run(host='0.0.0.0', port=5001, debug=True)