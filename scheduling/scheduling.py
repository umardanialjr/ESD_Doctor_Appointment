from flask import Flask, request, jsonify
import requests, datetime,json
from os import environ
from flask_cors import CORS
from invokes import invoke_http
import amqp_setup
import pika


app = Flask(__name__)
CORS(app)


@app.route("/display/patient/<string:patient_id>")
def patient_display(patient_id):
    patient_url = environ.get('patientURL') + "/" + str(patient_id) or "http://localhost:5001/patient/" + str(patient_id)
    medical_url = environ.get('medicalURL') + "/patientid/" + str(patient_id) or "http://localhost:5001/medical/patientid/" + str(patient_id)
    appointment_url = environ.get('appointmentURL') + "/patientid/" + str(patient_id) or "http://localhost:5001/appointment/patientid/" + str(patient_id)

    patient_result = requests.get(patient_url).json()
    medical_result = requests.get(medical_url).json()
    appointment_result = requests.get(appointment_url).json()

    print(patient_result)
    print(medical_result)
    print(appointment_result)
    if patient_result['code'] == 200 and medical_result['code'] == 200:
        patientdata = patient_result['data']
        medicaldata = medical_result['data']
        appointmentdata = appointment_result['data']
        return jsonify({
            "code": 200,
            "message": "Data found",
            "patientdata": patientdata,
            "medicaldata": medicaldata,
            "appointmentdata": appointmentdata
        })

    else:
        return jsonify({
            "code": 404,
            "message": "Data not found",
            "patientdata": [],
            "medicaldata": []
        })

@app.route("/display/medical/patient/<string:patient_id>")
def display_medical_by_patient(patient_id):

    appointment_url = environ.get('appointmentURL') + "/patientid/" + str(patient_id) or "http://localhost:5001/appointment/patientid/" + str(patient_id)
    medical_url = environ.get('medicalURL') + "/patientid/" + str(patient_id) or "http://localhost:5001/medical/patientid/" + str(patient_id)

    appointment_result = requests.get(appointment_url).json()['data']
    medical_result = requests.get(medical_url).json()['data']

    medical_listed = []
    if medical_result:

        for medical in medical_result:
            for appointment in appointment_result:
                if appointment['appointment_id'] == medical['appointment_id']:
                    medical_listed.append({
                        "appointment_id": medical['appointment_id'],
                        "appointment_datetime": appointment['appointment_datetime'],
                        "description": medical['description'],
                        "status": appointment['status']
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

@app.route("/display/staff/appointment/<string:querydate>")
def staff_display_appointment(querydate):
    patient_url = environ.get('patientURL') or "http://localhost:5001/patient"
    appointment_url = environ.get('appointmentURL') + "/status/Pending" or "http://localhost:5001/appointment/status/Pending"
    availability_url = environ.get('doctorAvailabilityURL') or "http://localhost:5000/availability"
    appointment_result = requests.get(appointment_url).json()['data']
    patient_result = requests.get(patient_url).json()['data']['patients']
    availability_result = requests.get(availability_url).json()['data']['availability']

    to_return = []
    doctor_to_return = []

    for appointment in appointment_result:
        date = datetime.datetime.strptime(appointment['appointment_datetime'],'%a, %d %b %Y %H:%M:%S GMT')
        date = date.strftime("%d-%m-%Y")
        for patient in patient_result:
            if appointment['patient_id'] == patient['patient_id'] and querydate == date :
                to_return.append({
                    "appointment_id": appointment['appointment_id'],
                    "appointment_datetime": appointment['appointment_datetime'],
                    "patient_id": patient['patient_id'],
                    "patient_fname": patient['patient_fname'],
                    "status": appointment['status'],
                    "formatteddate": date
                })

    for availability in availability_result:
        date = datetime.datetime.strptime(availability['calendar_date'], '%a, %d %b %Y %H:%M:%S GMT')
        date = date.strftime("%d-%m-%Y")
        if querydate == date:
            doctor_to_return.append(availability)


    if to_return or doctor_to_return:
        return jsonify(
            {
                "code": 200,
                "message": "Data found.",
                "patientdata": to_return,
                "doctordata": doctor_to_return
            }
        ), 200

    else:
        return jsonify(
            {
                "code": 404,
                "message": "Data not found.",
                "data": []
            }
        ), 404

@app.route("/update/staff/appointment/")
def staff_retrieve_update_appointment():
    appointment_url = environ.get('appointmentURL') + "/status/Pending" or "http://localhost:5001/appointment/status/Pending"
    appointment_result = requests.get(appointment_url).json()['data']

    if appointment_result:
        return jsonify({
            "code": 200,
            "message": "Appointments found.",
            "data": appointment_result
        }), 200

    else:
        return jsonify({
            "code": 404,
            "message": "Appointment not found.",
            "data": []
        }), 404

@app.route("/update/staff/appointment/", methods=['PUT'])
def staff_update_appointment_status():

    data = request.get_json()
    update_appointment_url = environ.get('appointmentURL') + "/" + str(data['appointment_id']) or "http://localhost:5001/appointment/" + str(data['appointment_id'])
    patient_url = environ.get('patientURL') + '/' or "http://localhost:5001/patient/"

    try:

        appointment_result = requests.put(update_appointment_url, json=data)
        updated_appointment_result = requests.get(update_appointment_url).json()['data']
        patient_id = updated_appointment_result['patient_id']
        patient_info_result = requests.get(patient_url+str(patient_id)).json()['data']

        new_message = {
                    "appointment_datetime": updated_appointment_result['appointment_datetime'],
                    "appointment_id": updated_appointment_result['appointment_id'],
                    "patient_id": updated_appointment_result['patient_id'],
                    "status": updated_appointment_result['status'],
                    "patient_email": patient_info_result['patient_email'],
                    "patient_fname": patient_info_result['patient_fname']
        }

        new_message = json.dumps(new_message, default=str)

        if appointment_result.status_code != 201:
            return jsonify({
                "code": 500,
                "message": "Error updating database",
            }), 500

        else:
            new_data = appointment_result.json()['data']
            amqp_setup.check_setup()
            amqp_setup.channel.basic_publish(exchange=amqp_setup.exchangename, routing_key="notification.rk",
                                             body=new_message, properties=pika.BasicProperties(delivery_mode=2))

        print('Message added to queue')
        return jsonify({
            "code": 200,
            "message": "Message added",
        }), 200


    except Exception as e:
        return jsonify({
            "code": 500,
            "message": str(e),
        }), 500


@app.route("/scheduling/doctor/<string:doctor_id>")
def retrieve_doctor_scheduling(doctor_id):
    doctor_URL = environ.get('doctorAvailabilityURL') + "/" or "http://localhost:5000/availability/"
    doctor_result = invoke_http(doctor_URL+doctor_id, method="GET")
    to_return = []
    for scheduling in doctor_result['data']:
        if (datetime.datetime.strptime(scheduling['calendar_date'],'%a, %d %b %Y %H:%M:%S GMT') > datetime.datetime.today()):
            to_return.append(scheduling)
    if to_return:
        return jsonify({
            "code": 200,
            "message": "Data found",
            "data": to_return
            }
        )
    else:
        return jsonify({
            "code": 404,
            "message": "No data found",
        })

if __name__ == "__main__":
    app.run(host='0.0.0.0', port=5003, debug=True)
    