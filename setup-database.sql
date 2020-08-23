CREATE TABLE contact (
       fname VARCHAR(20) NOT NULL,
       lname VARCHAR(20) NOT NULL,
       email VARCHAR(50) PRIMARY KEY,
       time_of_visit DATE NOT NULL
);

INSERT INTO contact VALUES ('Testy', 'McTestface', 'mctestface@gmail.com', TO_DATE('25-01-1999:20:30', 'DD-MM-YY:HH24:MI');
