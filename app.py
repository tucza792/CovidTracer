# Sourced from https://steemit.com/coding/@crispychris89/how-to-setup-an-automatic-mysql-email-script
# and https://medium.com/@thishantha17/build-a-simple-python-rest-api-with-apache2-gunicorn-and-flask-on-ubuntu-18-04-c9d47639139b
from flask import Flask, request, abort, jsonify
import smtplib
import mysql.connector as mysql

app = Flask(__name__)


@app.route('/sendEmail', methods=['GET'])
def sendEmail():
    server = smtplib.SMTP('smtp.gmail.com', 587)
    server.starttls()
    server.login("cosc349covidtracer@gmail.com", "88QAe5jEju")
    emails = []
    msgs = []

    # enter your server IP address/domain name
    HOST = "192.168.2.12" # or "domain.com"
    # database name, if you want just to connect to MySQL server, leave it empty
    DATABASE = "fvision"
    # this is the user you create
    USER = "webuser"
    # user password
    PASSWORD = "insecure_db_pw"
    # connect to MySQL server
    db_connection = mysql.connect(host=HOST, database=DATABASE, user=USER, password=PASSWORD)
    cursor = db_connection.cursor()

    query = ("SELECT fname, email, time_of_visit FROM contact")
    cursor.execute(query)

    for (fname, email, time_of_visit) in cursor:
        emails.append(email)
        msgs.append("Subject: COVID-19 Tracer\nHi " + fname + ",\n\nYou were visited by someone who has tested positive for Covid-19 at: " + time_of_visit.strftime("%m/%d/%Y, %H:%M:%S") + "\n\nYou should isolate yourself immediately and get a Covid-19 test as soon as possible. \n\n(FYI: This is not a real alert, do not take this information seriously)")

    for email, msg in zip(emails, msgs):
        server.sendmail("cosc349covidtracer@gmail.com", email, msg)

    server.quit()
    cursor.close()
    return "Emails sent"

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)