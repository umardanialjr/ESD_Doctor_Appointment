from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
from os import environ
import stripe

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = environ.get('dbURL') or "mysql+mysqlconnector://admin:esdDBpass1@database-1.ckxrhyi6ulnv.ap-southeast-1.rds.amazonaws.com:3306/payment_service"
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
CORS(app)
stripe.api_key = 'sk_test_51IXmFGAB2qhMc6CzNkZqLOFpfjVTuyenmW0V67nbedIIXMcBQIuAl2B8GnnmSxIuMDgOx3JS9S2tEOTC1jk8GS5500YaL9ssyi'

db = SQLAlchemy(app)


class Payment(db.Model):
    __tablename__ = 'payment'

    payment_id = db.Column(db.Integer, primary_key=True)
    stripe_id = db.Column(db.Integer, primary_key=False)
    appointment_id = db.Column(db.Integer, primary_key=False)
    patient_id = db.Column(db.Integer, primary_key=False)
    amount = db.Column(db.Float, primary_key=False)
    inv_datetime = db.Column(db.DateTime,primary_key=False)
    payment_datetime = db.Column(db.DateTime,primary_key=False)

    def __init__(self, payment_id, strip_id, appointment_id, patient_id, amount, inv_datetime, payment_datetime):
        self.payment_id = payment_id
        self.stripe_id = strip_id
        self.appointment_id = appointment_id
        self.patient_id = patient_id
        self.amount = amount
        self.inv_datetime = inv_datetime
        self.payment_datetime = payment_datetime

    def json(self):
        return {"payment_id":self.payment_id,
                "stripe_id": self.stripe_id,
                "appointment_id":self.appointment_id,
                "patient_id":self.patient_id,
                "amount":self.amount,
                "inv_datetime":self.inv_datetime,
                "payment_datetime":self.payment_datetime}

# stripe payment
@app.route('/create-checkout-session', methods=['POST'])
def create_checkout_session():
  session = stripe.checkout.Session.create(
    payment_method_types=['card'],
    line_items=[{
        "price": "price_1IcomqAB2qhMc6Czmbh9Y480",
        "quantity": 1
    }],
    mode='payment',
    success_url='http://localhost/success.php',
    cancel_url='http://localhost/profile.php',
  )

  return jsonify(id=session.id)


# get all payments
@app.route("/payment")
def get_all_payment():
    payment_list = Payment.query.all()
    if len(payment_list):
        return jsonify(
            {
                "code": 200,
                "data": {
                    "payment": [payment.json() for payment in payment_list]
                }
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "There are no payments in the list."
        }
    ), 404


# 2)create new payment
@app.route("/payment", methods=["POST"])
def create_payment():

    data = request.get_json()
    payment = Payment(payment_id=None, **data)

    try:
        db.session.add(payment)
        db.session.commit()

    except:
        return jsonify(
            {
                "code": 500,
                "message": "An error occurred adding the payment.",
            }
        ), 500
    return jsonify(
        {
            "code": 201,
            "message": "Payment created.",
            "data": payment.json()
        }
    ), 201


if __name__ == "__main__":
    app.run(host='0.0.0.0', port=5002, debug=True)

