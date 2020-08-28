# Interface between the Apache2 web server and app.py
from app import app

if __name__ == '__main__':
    app.run()