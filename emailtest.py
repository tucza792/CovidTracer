#Sourced from https://steemit.com/coding/@crispychris89/how-to-setup-an-automatic-mysql-email-script
import smtplib

server = smtplib.SMTP('smtp.gmail.com', 587)
server.starttls()
server.login("cosc349covidtracer@gmail.com", "88QAe5jEju")

msg = "Test!"
server.sendmail("cosc349covidtracer@gmail.com", "zachtuckernz@gmail.com", msg)
server.quit()