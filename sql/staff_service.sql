DROP DATABASE IF EXISTS staff_serivce;
CREATE DATABASE staff_service;
USE staff_service;
CREATE TABLE staff_info(
    staff_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_phone VARCHAR(8)
);

INSERT INTO staff_info(staff_phone) VALUES('98765432');
DELETE FROM staff_info where staff_id=2;
