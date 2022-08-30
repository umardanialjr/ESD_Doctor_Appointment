from flask import Flask
from flask_cors import CORS
import smtplib, ssl, os
import amqp_setup, json

# app initialisation and configuration
app = Flask(__name__)
CORS(app)

monitorBindingKey = "notification.rk"

def receive_appointment():
    amqp_setup.check_setup()

    queue_name = 'notification'

    amqp_setup.channel.basic_consume(queue=queue_name, on_message_callback=callback_notify_email_1, auto_ack=True)
    amqp_setup.channel.start_consuming()


def callback_notify_email_1(channel, method, properties, body):
    port = 465  # For SSL
    smtp_server = "smtp.gmail.com"
    sender_email = "esdclinic@gmail.com"
    password = "esdClinic2021"

    data = json.loads(body)
    patient_id = data['patient_id']
    appointment_id = data['appointment_id']

    try:
        if data:
            appointmentid = str(data['appointment_id'])
            patient_name = data['patient_fname']
            patient_email = data['patient_email']
            status = data['status'].upper()
            appointmentdatetime = data['appointment_datetime'][:-4]

            message = """\
                    Subject: ESD Clinic Booking """ + appointmentid + """

                    Hi, """ + patient_name + """
                    Your booking at ESD Clinic is """ + status + """ for """ + appointmentdatetime + """

                    Thank you."""

            context = ssl.create_default_context()
            try:
                with smtplib.SMTP_SSL(smtp_server, port, context=context) as server:
                    server.login(sender_email, password)
                    server.sendmail(sender_email, patient_email, message)


                print("Email sent to patient_ID: " + str(data['patient_id']) + ' at ' + data['patient_email'])
            except Exception as e:
                print(e)
    except Exception as exception:
        print(exception)


if __name__ == "__main__":
    print("\nThis is " + os.path.basename(__file__), end='')
    print(": monitoring routing key '{}' in exchange '{}' ...".format(monitorBindingKey, amqp_setup.exchangename))
    receive_appointment()
