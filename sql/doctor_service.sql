DROP DATABASE IF EXISTS doctor_serivce;
CREATE DATABASE doctor_service;
USE doctor_service;

CREATE TABLE doctor_info(
    doctor_id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_fname VARCHAR(255),
    doctor_lname VARCHAR(255),
    doctor_phone VARCHAR(8),
    doctor_email VARCHAR(255)
);
DROP TABLE doctor_availability
CREATE TABLE doctor_availability(
    availability_id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_id INT,
    calendar_date DATE,
    availability_datetime_start DATETIME,
    availability_datetime_end DATETIME
);

INSERT INTO doctor_info(doctor_fname,doctor_lname,doctor_phone,doctor_email) VALUES('Chow','Zheng Feng','98520390','hellothere@gmail.com');
INSERT INTO doctor_info(doctor_fname,doctor_lname,doctor_phone,doctor_email) VALUES('Shahrul','Abbas','87769898','hihixsdc@gmail.com');

INSERT INTO doctor_availability(doctor_id, calendar_date, availability_datetime_start, availability_datetime_end) VALUES (2 ,'2021-03-20', '2021-03-20 10:00:00', '2021-03-20 13:00:00');
INSERT INTO doctor_availability(doctor_id, calendar_date, availability_datetime_start, availability_datetime_end) VALUES (2 ,'2021-03-20', '2021-03-20 14:00:00', '2021-03-20 17:30:00');
INSERT INTO doctor_availability(doctor_id, calendar_date, availability_datetime_start, availability_datetime_end) VALUES (2 ,'2021-03-20', '2021-03-20 18:30:00', '2021-03-20 21:00:00');
INSERT INTO doctor_availability(doctor_id, calendar_date, availability_datetime_start, availability_datetime_end) VALUES (1 ,'2021-03-20', '2021-03-20 10:00:00', '2021-03-20 13:00:00');
INSERT INTO doctor_availability(doctor_id, calendar_date, availability_datetime_start, availability_datetime_end) VALUES (1 ,'2021-03-20', '2021-03-20 14:00:00', '2021-03-20 17:30:00');
INSERT INTO doctor_availability(doctor_id, calendar_date, availability_datetime_start, availability_datetime_end) VALUES (1 ,'2021-03-20', '2021-03-20 18:30:00', '2021-03-20 21:00:00');
INSERT INTO doctor_availability(doctor_id, calendar_date, availability_datetime_start, availability_datetime_end) VALUES (2 ,'2021-04-22', '2021-04-22 10:00:00', '2021-04-22 13:00:00');
INSERT INTO doctor_availability(doctor_id, calendar_date, availability_datetime_start, availability_datetime_end) VALUES (2 ,'2021-04-22', '2021-04-22 14:00:00', '2021-04-22 17:30:00');
INSERT INTO doctor_availability(doctor_id, calendar_date, availability_datetime_start, availability_datetime_end) VALUES (2 ,'2021-04-22', '2021-04-22 18:30:00', '2021-04-22 21:00:00');