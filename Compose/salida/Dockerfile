FROM python:3.9-slim

RUN apt-get update && \
    apt-get install -y --no-install-recommends openjdk-17-jre-headless procps && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

COPY spark-3.5.3-bin-hadoop3 /opt/spark

ENV JAVA_HOME=/usr/lib/jvm/java-17-openjdk-amd64
ENV PATH=$PATH:/opt/spark/bin

COPY requirements.txt /app/requirements.txt
WORKDIR /app
RUN pip install --no-cache-dir -r requirements.txt

COPY app.py /app/app.py
COPY dashboard.py /app/dashboard.py
COPY templates /app/templates
COPY airbnb_sales_data.csv /app/airbnb_sales_data.csv

RUN mkdir -p /app/output

CMD ["python", "app.py"]
