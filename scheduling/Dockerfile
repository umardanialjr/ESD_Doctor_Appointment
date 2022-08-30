FROM python:3-slim
WORKDIR /usr/src/app
COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt
COPY ./scheduling.py ./amqp_setup.py ./invokes.py ./
CMD [ "python", "./scheduling.py" ]