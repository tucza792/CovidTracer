CREATE TABLE contact (
       fname VARCHAR(20) NOT NULL,
       lname VARCHAR(20) NOT NULL,
       email VARCHAR(50) PRIMARY KEY,
       time_of_visit DATETIME NOT NULL
);

INSERT INTO contact VALUES ('Testy', 'McTestface', 'mctestface@gmail.com', '1999-12-18 13:17:17');
