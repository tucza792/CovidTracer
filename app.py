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
    msg = "Test!"

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

    query = ("SELECT email FROM contact")
    cursor.execute(query)

    for email in cursor:
        server.sendmail("cosc349covidtracer@gmail.com", email, msg)

    server.quit()
    return ("Emails sent")

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)