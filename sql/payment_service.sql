DROP DATABASE IF EXISTS payment_serivce;
CREATE DATABASE payment_service;
USE payment_service;

CREATE TABLE payment(
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    stripe_id VARCHAR(255),
    appointment_id INT,
    patient_id INT,
    amount FLOAT,
    inv_datetime DATETIME,
    payment_datetime DATETIME
);
