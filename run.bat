docker compose down
docker compose build
docker compose up -d
timeout 30 /nobreak
docker start esd_doctor_patient_appointment_Scheduling_1
docker start esd_doctor_patient_appointment_notification_1