from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy import and_
from os import environ

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = environ.get('dbURL') or 'mysql+mysqlconnector://admin:esdDBpass1@database-1.ckxrhyi6ulnv.ap-southeast-1.rds.amazonaws.com:3306/doctor_service'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)

class Doctor(db.Model):
    __tablename__ = 'doctor_info'

    doctor_id = db.Column(db.Integer, primary_key=True)
    doctor_fname = db.Column(db.String(255), primary_key=False)
    doctor_lname = db.Column(db.String(255), primary_key=False)
    doctor_phone = db.Column(db.String(8), primary_key=False)
    doctor_email = db.Column(db.String(255), primary_key=False)

    def __init__(self, doctor_id, doctor_fname, doctor_lname, doctor_phone,doctor_email):
        self.doctor_id = doctor_id
        self.doctor_fname = doctor_fname
        self.doctor_lname = doctor_lname
        self.doctor_phone = doctor_phone
        self.doctor_email = doctor_email

    
    def json(self):
        return {"doctor_id":self.doctor_id, "doctor_fname":self.doctor_fname, "doctor_lname":self.doctor_lname, "doctor_phone":self.doctor_phone, "doctor_email":self.doctor_email}


class Availability(db.Model):
    __tablename__ = 'doctor_availability'

    availability_id = db.Column(db.Integer, primary_key=True)
    doctor_id = db.Column(db.Integer,primary_key=False)
    calendar_date = db.Column(db.Date, primary_key=False)
    availability_datetime_start = db.Column(db.DateTime,primary_key=False)
    availability_datetime_end = db.Column(db.DateTime,primary_key=False) 

    def __init__(self,availability_id,doctor_id,calendar_date,availability_datetime_start,availability_datetime_end):
        self.availability_id = availability_id
        self.doctor_id = doctor_id
        self.calendar_date = calendar_date
        self.availability_datetime_start = availability_datetime_start
        self.availability_datetime_end = availability_datetime_end


    def json(self):
        return{"availability_id": self.availability_id,"doctor_id":self.doctor_id,"calendar_date": self.calendar_date,"availability_datetime_start":self.availability_datetime_start,"availability_datetime_end":self.availability_datetime_end}


@app.route("/doctor")
#get all doctor
def get_all():
    doctor_list = Doctor.query.all()
    if len(doctor_list):
        return jsonify(
            {
                "code": 200,
                "data": {
                    "doctors": [doctor.json() for doctor in doctor_list]
                }
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "There are no doctors in the list.",

        }
    ), 404


#1)get doctor details 
@app.route("/doctor/<string:doctor_id>")
def find_by_doctor_id(doctor_id):
    doctor = Doctor.query.filter_by(doctor_id=doctor_id).first()
    if doctor:
        return jsonify(
            {
                "code": 200,
                "message": "Doctor found.",
                "data": doctor.json()
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "Doctor not found.",
            "data": []
        }
    ), 404


#2)create new doctor. Currently not in use
@app.route("/doctor/<string:doctor_id>",  methods=["POST"])
def create_doctor(doctor_id):
    if (Doctor.query.filter_by(doctor_id=doctor_id).first()):
        return jsonify(
            {
                "code": 400,
                "message": "Doctor already exists.",
                "data": {
                    "doctor_id": doctor_id
                }
            }
        ), 400

    data = request.get_json()
    doctor = Doctor(doctor_id, **data)

    try:
        db.session.add(doctor)
        db.session.commit()

    except:
        return jsonify(
            {
                "code": 500,
                "message": "An error occurred adding the doctor.",
                "data": {
                    "doctor_id": doctor_id
                }
            }
        ), 500
    return jsonify(
        {
            "code": 201,
            "message": "Doctor created.",
            "data": doctor.json()
        }
    ), 201


#3 add new schedule
@app.route("/availability",  methods=["POST"])
def add_schedule():

    data = request.get_json()
    availability = Availability(availability_id = None, **data)

    try:
        db.session.add(availability)
        db.session.commit()

    except:
        return jsonify(
            {
                "code": 500,
                "message": "An error occurred loading the doctor's availability. Please try again later.",
                "data": data
            }
        ), 500
    return jsonify(
        {
            "code": 201,
            "message": "Doctor's availability added.",
            "data": availability.json()
        }
    ), 201


#4 get doctor schedule 
@app.route("/availability/<string:doctor_id>")
def get_doctor_schedule(doctor_id):
    availability_list = Availability.query.filter(Availability.doctor_id == doctor_id).order_by(Availability.availability_datetime_start.asc())
    if availability_list:
        return jsonify(
            {
                "code": 200,
                "message": "Schedule found.",
                "data": [availability.json() for availability in availability_list]
            }
        ), 200

    return jsonify(
        {
            "code": 404,
            "message": "Schedule not found.",
            "data": []
        }
    ), 404
    

@app.route("/availability")
#get all availability
def get_all_availability():
    availability_list = Availability.query.all()
    if len(availability_list):
        return jsonify(
            {
                "code": 200,
                "data": {
                    "availability": [availability.json() for availability in availability_list]
                }
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "There are no availabilities on the list."
        }
    ), 404


# Authenticate doctor
@app.route("/authenticate_doctor", methods=['POST'])
def authenticate():
    doctor_login = request.get_json()
    doctor_phone = doctor_login["doctor_phone"]
    doctor_id = doctor_login["doctor_id"]

    try:
        doctor = Doctor.query.filter(and_(Doctor.doctor_phone == doctor_phone, Doctor.doctor_id == doctor_id)).first()
        if doctor:
            return jsonify(
                {
                    "code": 200,
                    "data": doctor.json(),
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


if __name__ == "__main__":
    app.run(host='0.0.0.0', port=5000, debug=True)