-- Crear la base de datos 
CREATE DATABASE central_console;

-- Sequencias 
CREATE SEQUENCE usuarios_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE grupos_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE acl_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE aliases_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE target_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE interfaces_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE protocolo_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE nat_one_to_one_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE nat_port_forward_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE informacion_ip_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE firewall_lan_id_seq INCREMENT BY 1 MINVALUE 1 START 1;

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

INSERT INTO usuarios VALUES(nextval('usuarios_id_seq'), 'admin', '', 'admin@warriorslabs.com', '$2a$04$z3Okjv7YTmOKn.OFky3Z7Ozdj.NtPyB1po9A7GSRHtnXxmpD4wXh2', 'ROLE_SUPERUSER', 't', 0, 'NULL');

-- Tabla de grupos 
CREATE TABLE grupos(
	id INT PRIMARY KEY NOT NULL,
	primer_octeto INT NOT NULL,
	segundo_octeto INT NOT NULL, 
	tercer_octeto INT NOT NULL,
	cuarto_octeto INT NOT NULL,
	mascara INT NOT NULL,
	interfaz VARCHAR(20) NOT NULL,
	nombre VARCHAR(50) NOT NULL,
	descripcion TEXT DEFAULT NULL
);

-- Tabla de grupos 
CREATE TABLE grupos(
	id INT PRIMARY KEY NOT NULL,
	ip VARCHAR(15) NOT NULL,
	primer_octeto INT NOT NULL,
	segundo_octeto INT NOT NULL, 
	tercer_octeto INT NOT NULL,
	cuarto_octeto INT NOT NULL,
	mascara INT NOT NULL,
	interfaz VARCHAR(20) NOT NULL,
	nombre VARCHAR(50) NOT NULL,
	descripcion TEXT DEFAULT NULL
);

CREATE TABLE usuarios(
	id INT PRIMARY KEY NOT NULL,
	nombre VARCHAR(20) NOT NULL,
	apellidos VARCHAR(50) DEFAULT NULL,
	email VARCHAR(50) NOT NULL,
	password VARCHAR(255) NOT NULL,
	role VARCHAR(18) NOT NULL,
	estado BOOLEAN DEFAULT TRUE,
	intentos INT DEFAULT 0,
	id_grupo INT DEFAULT NULL,
	FOREIGN KEY(id_grupo) REFERENCES grupos(ID) ON UPDATE CASCADE ON DELETE RESTRICT 
);

-- Acl groups
CREATE TABLE acl(
	id INT PRIMARY KEY NOT NULL,
	estatus BOOLEAN DEFAULT FALSE,
	nombre VARCHAR(20) NOT NULL,
	cliente TEXT NOT NULL,
	target_rule TEXT NOT NULL,
	target_rules_list TEXT NOT NULL,
	not_allow_ip BOOLEAN DEFAULT FALSE,
	redirectMode VARCHAR(50) NOT NULL,
	redirect VARCHAR(50) DEFAULT NULL,
	descripcion VARCHAR(50) DEFAULT NULL,
	log BOOLEAN DEFAULT FALSE,
	grupo VARCHAR(50) NOT NULL,
	ubicacion VARCHAR(50) NOT NULL
);

-- Aliases
CREATE TABLE aliases(
	id INT PRIMARY KEY NOT NULL,
	nombre VARCHAR(20) NOT NULL,
	descripcion VARCHAR(50) DEFAULT NULL,
	tipo VARCHAR(50) NOT NULL,
	ip_port TEXT NOT NULL,
	descripcion_ip_port TEXT DEFAULT NULL,
	grupo VARCHAR(50) NOT NULL,
	ubicacion VARCHAR(50) NOT NULL
);

-- Targets categories
CREATE TABLE target(
	id INT PRIMARY KEY NOT NULL,
	nombre VARCHAR(20) NOT NULL,
	domain_list TEXT NOT NULL,
	url_list TEXT NOT NULL,
	regular_expression TEXT NOT NULL,
	redirect_mode VARCHAR(50) NOT NULL,
	redirect VARCHAR(50) NOT NULL,
	descripcion VARCHAR(50) DEFAULT NULL,
	log BOOLEAN DEFAULT TRUE,
	grupo VARCHAR(50) NOT NULL,
	ubicacion VARCHAR(50) NOT NULL
);

--interfaces
CREATE TABLE interfaces(
	id INT PRIMARY KEY NOT NULL,
	nombre VARCHAR(20) NOT NULL,
	grupo VARCHAR(50) NOT NULL
);

--Protocol
CREATE TABLE protocolo(
	id INT PRIMARY KEY NOT NULL,
	nombre VARCHAR(20) NOT NULL,
	valor VARCHAR(50) NOT NULL
);

INSERT INTO protocolo VALUES(NEXTVAL('protocolo_id_seq'),'TCP','tcp');
INSERT INTO protocolo VALUES(NEXTVAL('protocolo_id_seq'),'UDP','udp');
INSERT INTO protocolo VALUES(NEXTVAL('protocolo_id_seq'),'TCP/UDP','tcp/udp');
INSERT INTO protocolo VALUES(NEXTVAL('protocolo_id_seq'),'ICMP','icmp');

--nats
CREATE TABLE nat_port_forward(
	id INT PRIMARY KEY NOT NULL,
	estatus BOOLEAN DEFAULT FALSE,
	interface VARCHAR(25) DEFAULT NULL,
	protocolo VARCHAR(25) DEFAULT NULL,
	source_advanced_invert_match BOOLEAN DEFAULT FALSE,
	source_advanced_type VARCHAR(25) DEFAULT NULL,
	source_advanced_adress_mask VARCHAR(25) DEFAULT NULL,
	source_advanced_adress_mask1 INT DEFAULT NULL,
	--source_advanced_mask INT DEFAULT NULL,
	source_advanced_from_port VARCHAR(20) DEFAULT NULL,
	source_advanced_custom VARCHAR(25) DEFAULT NULL,
	source_advanced_to_port VARCHAR(20) DEFAULT NULL,
	source_advanced_custom_to_port VARCHAR(25) DEFAULT NULL,
	destination_invert_match BOOLEAN DEFAULT FALSE,
	destination_type VARCHAR(25) DEFAULT NULL,
	destination_adress_mask VARCHAR(25) DEFAULT NULL,
	destination_adress_mask2 INT DEFAULT NULL,
	--destination_mask INT DEFAULT NULL,
	destination_range_from_port VARCHAR(20) DEFAULT NULL,
	destination_range_custom VARCHAR(25) DEFAULT NULL,
	destination_range_to_port VARCHAR(20) DEFAULT NULL,
	destination_range_custom_to_port VARCHAR(25) DEFAULT NULL,
	redirect_target_ip VARCHAR(15) DEFAULT NULL,
	redirect_target_port VARCHAR(25) DEFAULT NULL,
	redirect_target_port_custom VARCHAR(25) DEFAULT NULL,
	descripcion VARCHAR(25) DEFAULT NULL,
	nat_reflection VARCHAR(25) DEFAULT NULL,
	filter_rule_association VARCHAR(25) DEFAULT NULL,
	grupo VARCHAR(50) DEFAULT NULL,
	ubicacion VARCHAR(50) NOT NULL,
	posicion SERIAL 
);

--nat one to one 
CREATE TABLE nat_one_to_one(
	id INT PRIMARY KEY NOT NULL,
	estatus BOOLEAN DEFAULT FALSE,
	interface VARCHAR(25) DEFAULT NULL,
	external_subnet_ip VARCHAR(25) DEFAULT NULL,
	internal_ip BOOLEAN DEFAULT FALSE,
	internal_ip_type VARCHAR(25) DEFAULT NULL,
	internal_adress_mask VARCHAR(25) DEFAULT NULL,
	destination BOOLEAN DEFAULT FALSE,
	destination_type VARCHAR(25) DEFAULT NULL,
	destination_adress_mask VARCHAR(25) DEFAULT NULL,
	descripcion VARCHAR(25) DEFAULT NULL,
	nat_reflection VARCHAR(25) DEFAULT NULL,
	grupo VARCHAR(50) DEFAULT NULL,
	ubicacion VARCHAR(50) NOT NULL,
	posicion SERIAL	
);

--firewall wan
CREATE TABLE firewall_lan(
	id INT PRIMARY KEY NOT NULL,
	action VARCHAR(25) DEFAULT NULL,
	estatus BOOLEAN DEFAULT FALSE,
	interface VARCHAR(25) DEFAULT NULL,
	adress_family VARCHAR(25) DEFAULT NULL,
	protocolo VARCHAR(25) DEFAULT NULL,
	icm_subtypes TEXT DEFAULT NULL,
	--source
	source_invert_match BOOLEAN DEFAULT FALSE,
	source_type VARCHAR(25) DEFAULT NULL,
	source_addres_mask VARCHAR(20) DEFAULT NULL,
	--hide advanced
	source_port_range_from VARCHAR(20) DEFAULT NULL,
	source_port_range_custom VARCHAR(25) DEFAULT NULL,
	source_port_range_to VARCHAR(20) DEFAULT NULL,
	source_port_range_custom_to VARCHAR(20) DEFAULT NULL,
	--destination
	destination_invert_match BOOLEAN DEFAULT FALSE,
	destination_type VARCHAR(25) DEFAULT NULL,
	destination_addres_mask VARCHAR(20) DEFAULT NULL,
	destination_port_range_from VARCHAR(20) DEFAULT NULL,
	destination_port_range_custom VARCHAR(25) DEFAULT NULL,
	destination_port_range_to VARCHAR(20) DEFAULT NULL,
	destination_port_range_custom_to VARCHAR(20) DEFAULT NULL,
	log BOOLEAN DEFAULT TRUE,
	descripcion VARCHAR(40) DEFAULT NULL,
	grupo VARCHAR(50) DEFAULT NULL,
	ubicacion VARCHAR(50) NOT NULL,
	posicion SERIAL
);

CREATE TABLE informacion_ip(
	id INT PRIMARY KEY NOT NULL,
	primer_octeto INT NOT NULL,
	segundo_octeto INT NOT NULL, 
	tercer_octeto INT NOT NULL,
	cuarto_octeto INT NOT NULL,
	mascara INT NOT NULL,
);

-- Firewall --
CREATE SEQUENCE firewall_action_id_seq INCREMENT BY 1 MINVALUE 1 START 1;

CREATE TABLE firewall_action(
	id INT PRIMARY KEY NOT NULL,
	nombre_action VARCHAR(25) NOT NULL,
	valor_action VARCHAR(25) NOT NULL
);

INSERT INTO 