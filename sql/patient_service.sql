# patient_service to be deployed on AWS RDS
# db endpoint: database-1.ckxrhyi6ulnv.ap-southeast-1.rds.amazonaws.com
# port: 3306
# username: admin
# password: esdDBpass1

DROP SCHEMA IF EXISTS patient_serivce;
CREATE DATABASE patient_service;
USE patient_service;

DROP TABLE IF EXISTS patient_info;
CREATE TABLE patient_info(
    patient_id INT PRIMARY KEY AUTO_INCREMENT,
    patient_fname VARCHAR(255),
    patient_lname VARCHAR(255),
    patient_phone VARCHAR(8),
    patient_telehandle VARCHAR(255),
    patient_email VARCHAR(255),
    chat_id VARCHAR(64)
);

CREATE TABLE patient_medical(
    medical_record_id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT,
    appointment_id INT,
    description VARCHAR(4096)
);
DROP TABLE IF EXISTS patient_appointment;
CREATE TABLE patient_appointment(
    appointment_id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT,
    appointment_datetime DATETIME,
    status VARCHAR(255)
);

TRUNCATE patient_info;

TRUNCATE patient_medical;

#  Sample Data for Patient Info
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('TAN XIAO HUI','TAN XIAO HUI','97399245','@TANXIAOHUI','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('FREYA LIM GUO EN','FREYA LIM GUO EN','97399245','@FREYALIMGUOEN','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('DEWANARA VANASAMIN','DEWANARA VANASAMIN','97399245','@DEWANARAVANASAMIN','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('HOWARD CHEN YU SHENG','HOWARD CHEN YU SHENG','','@HOWARDCHENYUSHENG','');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('CHENG QI FA','CHENG QI FA','97399245','@CHENGQIFA','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('FATIMAH','FATIMAH','','@FATIMAH','');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('MARISOL NALDO','MARISOL NALDO','97399245','@MARISOLNALDO','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('ELIZABERTH PIERCE JOHNSON','ELIZABERTH PIERCE JOHNSON','','@ELIZABERTHPIERCEJOHNSON','');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('TOMMY S/O BALA','TOMMY S/O BALA','97399245','@TOMMYS/OBALA','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('TIMOTHY TAN CHENG GUAN','TIMOTHY TAN CHENG GUAN','97399245','@TIMOTHYTANCHENGGUAN','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('MY,INFO%BB','MY,INFO%BB','','@MY,INFO%BB','');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('MY.INFO:CC','MY.INFO:CC','97399245','@MY.INFO:CC','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('LIM YONG XIANG','LIM YONG XIANG','97399245','@LIMYONGXIANG','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('TAN AH HONG','TAN AH HONG','97399245','@TANAHHONG','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('ALFONSO CRUZ','ALFONSO CRUZ','97399245','@ALFONSOCRUZ','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('ALFRED CHONG BOON HAO','ALFRED CHONG BOON HAO','97399245','@ALFREDCHONGBOONHAO','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('JENNY LIM WAI FOOK','JENNY LIM WAI FOOK','','@JENNYLIMWAIFOOK','');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('AMY CHONG GAN CHENG','AMY CHONG GAN CHENG','97399245','@AMYCHONGGANCHENG','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('CHEN WANFU','CHEN WANFU','97399245','@CHENWANFU','myinfotesting@gmail.com');
INSERT INTO patient_info(patient_fname,patient_lname,patient_phone,patient_telehandle,patient_email) VALUES('shah','shah','97399245','@imShahs','shahrulabbas@hotmail.com');



#  Sample Data for Patient Medical
INSERT INTO patient_medical(patient_id, appointment_id, description) VALUES(1,1,'Allergy to panadol');
INSERT INTO patient_medical(patient_id, appointment_id, description) VALUES(2,2,'Allergy to cough syrup');
INSERT INTO patient_medical(patient_id, appointment_id, description) VALUES(3,3,'Allergy to peanuts');
INSERT INTO patient_medical(patient_id, appointment_id, description) VALUES(4,4,'Lactose Intolerance');
INSERT INTO patient_medical(patient_id, appointment_id, description) VALUES(5,5,'Cannot take tablets');

TRUNCATE patient_appointment;
#  Sample Data for Patient Appointment
INSERT INTO patient_appointment(patient_id, appointment_datetime, status) VALUES(1,'2021-02-22 12:00:00','Confirmed');
INSERT INTO patient_appointment(patient_id, appointment_datetime, status) VALUES(2,'2021-02-12 11:00:00','Confirmed');
INSERT INTO patient_appointment(patient_id, appointment_datetime, status) VALUES(3,'2021-03-18 18:45:00','Confirmed');
INSERT INTO patient_appointment(patient_id, appointment_datetime, status) VALUES(3,'2021-03-19 19:15:00','Confirmed');
INSERT INTO patient_appointment(patient_id, appointment_datetime, status) VALUES(4,'2021-03-25 12:00:00','Cancelled');
INSERT INTO patient_appointment(patient_id, appointment_datetime, status) VALUES(5,'2021-04-22 15:15:00','Pending');
INSERT INTO patient_appointment(patient_id, appointment_datetime, status) VALUES(4,'2021-03-19 19:00:00','Confirmed');
INSERT INTO patient_appointment(patient_id, appointment_datetime, status) VALUES(20,'2021-03-19 19:15:00','Confirmed');
INSERT INTO patient_appointment(patient_id, appointment_datetime, status) VALUES(20,'2021-03-19 19:00:00','Confirmed');
INSERT INTO patient_appointment(patient_id, appointment_datetime, status) VALUES(20,'2021-03-20 19:00:00','Pending');
INSERT INTO patient_appointment(patient_id, appointment_datetime, status) VALUES(20,'2021-03-21 19:00:00','Cancelled');

