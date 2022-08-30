from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy import and_
from os import environ

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = environ.get('dbURL') or 'mysql+mysqlconnector://admin:esdDBpass1@database-1.ckxrhyi6ulnv.ap-southeast-1.rds.amazonaws.com:3306/staff_service'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)


class Staff(db.Model):
    __tablename__ = 'staff_info'

    staff_id = db.Column(db.Integer, primary_key=True)
    staff_phone = db.Column(db.String(255), primary_key=False)

    def __init__(self, staff_id, staff_phone):
        self.staff_id = staff_id
        self.staff_phone = staff_phone

    def json(self):
        return {"staff_id": self.staff_id, "staff_phone": self.staff_phone}




@app.route("/staff")
# get all staff
def get_all_staff():
    staff_list = Staff.query.all()
    if len(staff_list):
        return jsonify(
            {
                "code": 200,
                "data": {
                    "doctors": [staff.json() for staff in staff_list]
                }
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "There are no Staffs in the list.",

        }
    ), 404


#get staff details
@app.route("/staff/<string:staff_id>")
def find_by_staff_id(staff_id):
    staff = Staff.query.filter_by(staff_id=staff_id).first()
    if staff:
        return jsonify(
            {
                "code": 200,
                "message": "Staff found.",
                "data": staff.json()
            }
        ), 200
    return jsonify(
        {
            "code": 404,
            "message": "Staff not found.",
            "data": []
        }
    ), 404

# Authenticate staff
@app.route("/authenticate_staff", methods=['POST'])
def authenticate():
    staff_login = request.get_json()
    staff_phone = staff_login["staff_phone"]
    staff_id = staff_login["staff_id"]

    try:
        staff = Staff.query.filter(and_(Staff.staff_phone == staff_phone, Staff.staff_id == staff_id)).first()
        if staff:
            return jsonify(
                {
                    "code": 200,
                    "data": staff.json(),
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
    app.run(host='0.0.0.0', port=5004, debug=True)