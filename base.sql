-- Crear la base de datos 
CREATE DATABASE central_console;

-- Sequencias 
CREATE SEQUENCE usuarios_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE grupos_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE acl_groups_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE aliases_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE aliases_tipo_id_seq INCREMENT BY 1 MINVALUE 1 START 1;

-- Tabla usuarios 
CREATE TABLE usuarios(
	id INT PRIMARY KEY NOT NULL,
	nombre VARCHAR(20) NOT NULL,
	apellidos VARCHAR(50) DEFAULT NULL,
	email VARCHAR(50) NOT NULL,
	password VARCHAR(255) NOT NULL,
	role VARCHAR(18) NOT NULL,
	estado BOOLEAN DEFAULT TRUE,
	intentos INT DEFAULT 0,
	grupo VARCHAR(50) DEFAULT NULL
);

-- Tabla de grupos 
CREATE TABLE grupos(
	id INT PRIMARY KEY NOT NULL,
	ip VARCHAR(15) NOT NULL,
	nombre VARCHAR(50) NOT NULL,
	descripcion TEXT DEFAULT NULL
);

-- Acl groups
CREATE TABLE acl_groups(
	id INT PRIMARY KEY NOT NULL,
	estatus BOOLEAN DEFAULT FALSE,
	nombre VARCHAR(20) NOT NULL,
	cliente TEXT NOT NULL,
	not_allow_ip BOOLEAN DEFAULT FALSE,
	redirectMode VARCHAR(50) NOT NULL,
	redirect VARCHAR(50) NOT NULL,
	descripcion VARCHAR(50) NOT NULL,
	log BOOLEAN DEFAULT FALSE
);

-- Aliases tipo
CREATE TABLE aliases_tipo(
	id INT PRIMARY KEY NOT NULL,
	nombre VARCHAR(30) NOT NULL,
	valor VARCHAR(18) NOT NULL
);

INSERT INTO aliases_tipo VALUES(nextval('aliases_tipo_id_seq'),'Host(s)','host');
INSERT INTO aliases_tipo VALUES(nextval('aliases_tipo_id_seq'),'Network(s)','network');
INSERT INTO aliases_tipo VALUES(nextval('aliases_tipo_id_seq'),'Port(s)','port');
INSERT INTO aliases_tipo VALUES(nextval('aliases_tipo_id_seq'),'URL (Ips))','url');
INSERT INTO aliases_tipo VALUES(nextval('aliases_tipo_id_seq'),'URL (Ports)','url_ports');
INSERT INTO aliases_tipo VALUES(nextval('aliases_tipo_id_seq'),'URL Table (Ips)','urltable');
INSERT INTO aliases_tipo VALUES(nextval('aliases_tipo_id_seq'),'URL Table (Ports)','urltable_ports');

-- Aliases
CREATE TABLE aliases(
	id INT PRIMARY KEY NOT NULL,
	nombre VARCHAR(20) NOT NULL,
	descripcion VARCHAR(50) NOT NULL,
	id_aliases_tipo INT NOT NULL,
	ip_port TEXT NOT NULL,
	descripcion_ip_port TEXT DEFAULT NULL,
	grupo VARCHAR(50) NOT NULL,
	FOREIGN KEY(id_aliases_tipo) REFERENCES aliases_tipo(id)
);