#Sourced from https://steemit.com/coding/@crispychris89/how-to-setup-an-automatic-mysql-email-script
# and https://medium.com/@thishantha17/build-a-simple-python-rest-api-with-apache2-gunicorn-and-flask-on-ubuntu-18-04-c9d47639139b
from flask import Flask, request, abort, jsonify
import smtplib

app = Flask(__name__)


@app.route('/sendEmail', methods=['GET'])
def sendEmail():
    server = smtplib.SMTP('smtp.gmail.com', 587)
    server.starttls()
    server.login("cosc349covidtracer@gmail.com", "88QAe5jEju")

    msg = "Test!"
    server.sendmail("cosc349covidtracer@gmail.com", "zachtuckernz@gmail.com", msg)
    server.quit()

    return 'Email sent!'


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
