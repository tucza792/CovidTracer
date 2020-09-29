# Created with reference to https://medium.com/@thishantha17/build-a-simple-python-rest-api-with-apache2-gunicorn-and-flask-on-ubuntu-18-04-c9d47639139b
from flask import Flask, request, abort, jsonify
import smtplib
import mysql.connector as mysql

app = Flask(__name__)


@app.route('/sendEmail', methods=['GET'])
def sendEmail():
    # Connect to gmail SMTP server and login
    server = smtplib.SMTP('smtp.gmail.com', 587)
    server.starttls()
    server.login("cosc349covidtracer@gmail.com", "88QAe5jEju")

    emails = []
    msgs = []

    # Establish connection with the dbserver
    HOST = "covid-tracer-db-instance.c3v1xy5xbrif.us-east-1.rds.amazonaws.com"
    PORT = "3306"
    DATABASE = "sample"
    USER = "admin"
    PASSWORD = "a1b2c3d4"
    db_connection = mysql.connect(host=HOST, port=PORT, database=DATABASE, user=USER, password=PASSWORD)
    cursor = db_connection.cursor()

    query = ("SELECT fname, email, time_of_visit FROM contact")
    cursor.execute(query)

    # Create list of email addresses with corresponding list of personalised messages
    for (fname, email, time_of_visit) in cursor:
        emails.append(email)
        msgs.append("Subject: COVID-19 Tracer\nHi " + fname + ",\n\nYou were visited by someone who has tested positive for Covid-19 at: " + time_of_visit.strftime("%m/%d/%Y, %H:%M:%S") + "\n\nYou should isolate yourself immediately and get a Covid-19 test as soon as possible. \n\n(FYI: This is not a real alert, do not take this information seriously)")

    # Send each of the emails
    for email, msg in zip(emails, msgs):
        server.sendmail("cosc349covidtracer@gmail.com", email, msg)

    server.quit()
    cursor.close()
    return "Emails sent"

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)