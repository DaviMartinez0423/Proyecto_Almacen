CREATE DATABASE ventasproyec;

USE ventasproyec;

CREATE TABLE ventas (
id INT AUTO_INCREMENT PRIMARY KEY,
car_id INT NOT NULL,
customer_id INT NOT NULL,
color VARCHAR(20),
price INT NOT NULL,
used TINYINT(1)
);

CREATE DATABASE carproyec;

USE carproyec;

CREATE TABLE car (
id INT AUTO_INCREMENT PRIMARY KEY,
company VARCHAR(50),
model VARCHAR(50),
transmisssion VARCHAR(50),
body_style varchar(50),
aprox_price INT NOT NULL
);


CREATE DATABASE usuariosproyec;

USE usuariosproyec;

CREATE TABLE usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(20),
gender VARCHAR(20),
phone INT NOT NULL,
rol VARCHAR(20),
password VARCHAR(20)
);

INSERT INTO usuarios (nombre, gender, phone, rol, password) VALUES 
    ('Juan Perez', 'Male', 123456789, 'Admin', 'password123'),
    ('Maria Gomez', 'Female', 987654321, 'User', 'password456');
