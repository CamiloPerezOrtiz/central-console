# Crear la base de datos #
CREATE DATABASE central_console;

# Sequencias #
CREATE SEQUENCE usuarios_id_seq INCREMENT BY 1 MINVALUE 1 START 1;

# Tabla usuarios #
CREATE TABLE usuarios(
	id INT PRIMARY KEY NOT NULL,
	nombre VARCHAR(20) NOT NULL,
	apellidos VARCHAR(50) DEFAULT NULL,
	email VARCHAR(50) NOT NULL,
	password VARCHAR(255) NOT NULL,
	role VARCHAR(18) NOT NULL,
	estado BOOLEAN DEFAULT TRUE,
	intentos INT DEFAULT 0
);