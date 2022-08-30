import telebot
import json

from flask import Flask
from invokes import invoke_http
import requests

bot_token = "1700690132:AAHssXsWox8GXobDP96bon5FekVi2f0ooc0"
bot_user_name = "ESD_Bookingbot"
URL = "https://api.telegram.org/bot1700690132:AAHssXsWox8GXobDP96bon5FekVi2f0ooc0/"

bot = telebot.TeleBot(token=bot_token)
app = Flask(__name__)

@bot.message_handler(commands=['start'])
def display_hello(message):
     
    chat_id = message.from_user.id
    telegram_handle = "@" + message.from_user.username
    s ="Hello, welcome to the Doctor appointment application. Below are a list of functions that the telebot provides.\n\nUpcoming Appt : View your confirmed appointments.\nPending Appt: View your appointments that are pending approval.\nView Profile: View your registered profile details.\n\nThank you for choosing us."    
    bot.send_message(chat_id, text=s)

    generateFunction(chat_id)
    data = json.dumps({'chat_id': chat_id})
    #Save the chat.id to the database.
    http_result = requests.put("http://patient:5001/patient/tele/"+telegram_handle, json=data)


#   When a button in the inline keyboard is pressed, it replies with the respective details requested.
@bot.callback_query_handler(func=lambda call:True)
def callback_inline(call):

    username = call.from_user.username
    telegram_handle = "@" + username
    
    #Show pending appointments.
    if call.data =="view_pending":
        # Completed pending confirmed
        user_telegram_handle= "@" + username
        patient_URL = "http://patient:5001/patient/tele/" + str(user_telegram_handle)
        http_result = invoke_http(patient_URL , method = "GET")

        patient_dictionary = http_result["data"]
        patient_id = patient_dictionary["patient_id"]

        patient_appointment_URL = "http://patient:5001/appointment/patientid/" + str(patient_id)
        request_appointment = invoke_http(patient_appointment_URL , method = "GET")

        patient_appointment = request_appointment["data"]
        s= "Hello, the following are your appointment schedules. \n\n"

        for appointments in patient_appointment:
            if (appointments['status'] == "Pending"):
                s+= appointments["appointment_datetime"] + " is pending approval." + "\n\n"

        bot.send_message(call.from_user.id, "====================================")
        bot.send_message(call.from_user.id, text=s)
        
        generateFunction(call.from_user.id)
    # Show confirmed appointments
    if call.data =="view_appointment":
        
        user_telegram_handle= "@" + username
        patient_URL = "http://patient:5001/patient/tele/" + str(user_telegram_handle)
        http_result = invoke_http(patient_URL , method = "GET")

        patient_dictionary = http_result["data"]
        patient_id = patient_dictionary["patient_id"]

        patient_appointment_URL = "http://patient:5001/appointment/patientid/" + str(patient_id)
        request_appointment = invoke_http(patient_appointment_URL , method = "GET")
                
        patient_appointment = request_appointment["data"]
        s= "Hello, the following are your appointment schedules. \n\n"

        for appointments in patient_appointment:
            if (appointments['status'] == "Confirmed"):
                s+= appointments["appointment_datetime"] + " is confirmed" + "\n\n"
                
        bot.send_message(call.from_user.id, "====================================")
        bot.send_message(call.from_user.id, text=s)        
        generateFunction(call.from_user.id)
    # show profile details.
    if call.data =="view_profile":
        user_telegram_handle= "@" + username
        patient_URL = "http://patient:5001/patient/tele/" + user_telegram_handle
        http_result = invoke_http(patient_URL , method = "GET")
        patient_dictionary = http_result["data"]
        s = f"""This is your profile information.\n        
    Email:   {patient_dictionary['patient_email']}.
    Name:   {patient_dictionary['patient_fname']} {patient_dictionary['patient_lname']}
    PhoneNumber:   {patient_dictionary['patient_phone']}
    TelegramHandle:   {patient_dictionary['patient_telehandle']}
            
        """ 
        bot.send_message(call.from_user.id, "====================================")
        bot.send_message(call.from_user.id, text=s)
        generateFunction(call.from_user.id)


# To test if bot is functioning. Type 'hi' for bot to echo reply
@bot.message_handler(content_types=['text'])
def message_received(message):
    if message.text == "hi":
        bot.send_message(chat_id=message.from_user.id, text=message.text)


# Generates inline keyboard for the 3 functions. This function is called at the end of every command.
def generateFunction(chat_id):
    keyboardmain = telebot.types.InlineKeyboardMarkup()
    first_button = telebot.types.InlineKeyboardButton(text="Upcoming Appt", callback_data="view_appointment")
    second_button = telebot.types.InlineKeyboardButton(text="Pending Appt", callback_data="view_pending")
    third_button = telebot.types.InlineKeyboardButton(text="View Profile Details", callback_data="view_profile")
    keyboardmain.add(first_button, second_button)
    keyboardmain.add(third_button)
    bot.send_message(chat_id, "====================================")
    bot.send_message(chat_id, "What would you like to view?", reply_markup=keyboardmain)
    

bot.polling(True)   

if __name__ == "__main__":
    app.run(host='0.0.0.0', port=5009, debug=True)
