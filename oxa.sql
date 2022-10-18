CREATE DATABASE IF NOT EXISTS `oxa` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `oxa`;

CREATE TABLE `usuario` (
  
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `CORREO` varchar(255) DEFAULT NULL, 
   `NAME` varchar(255) DEFAULT NULL,  
   `LASTNAME` varchar(255) DEFAULT NULL,  
   `TELPPAL` varchar(255) DEFAULT NULL,  
   `TELSECOND` varchar(255) DEFAULT NULL,  
   `SALUDO` varchar(255) DEFAULT NULL,  
   `DESPEDIDA` varchar(255) DEFAULT NULL,  
   `ACCESSTOKEN` varchar(255) DEFAULT NULL,  
   `REFRESTOKEN` varchar(255) DEFAULT NULL,  
   `CHATROBOT` BOOLEAN NULL DEFAULT FALSE,  
   `ULTIMAFECHA` varchar(255) DEFAULT NULL,
   
   PRIMARY KEY (id)

) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `enlace` (
  `IDENLACE` int(11) NOT NULL AUTO_INCREMENT,
  `IDSINAPSIS` int(11) NOT NULL,
  `IDPUBLICACION` int(11) NOT NULL,
  
   PRIMARY KEY (IDENLACE)
);

CREATE TABLE `publicacion` (
    IDPUBLICACION INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(25),
    CODIGO VARCHAR(25),
    CODIGOORIGINAL VARCHAR(25),
    NOMBRE VARCHAR(60),
    GANANCIA INT(4),	

    PRIMARY KEY (IDPUBLICACION),
    UNIQUE (CODIGO)
);

CREATE TABLE `sinapsis` (
  `IDSINAPSIS` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `INFO` text,
  `ESTIMULOS` text,
  `NUMPUBLICACIONES` int(11) DEFAULT NULL,
  
   PRIMARY KEY (IDSINAPSIS)
);

CREATE TABLE `suscripcion` (
  `IDSUSCRIPCION` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `CAPITAL` FLOAT DEFAULT 0.0,
  `FECHAREGISTRO` date DEFAULT NULL,
  `FECHAVENCIMIENTO` date DEFAULT NULL,
  `TIPOSUSCRIPCION` int(11) DEFAULT NULL,
  `ESTATUS` int(11) DEFAULT NULL,
  
	PRIMARY KEY (IDSUSCRIPCION)
);

CREATE TABLE `questions` (
  `user_id` int(11) NOT NULL,
  `RECURSO` TEXT
);

CREATE TABLE `mensajes` (
  `IDMENSAJE` int NOT NULL AUTO_INCREMENT,
  `HORA` int(8) DEFAULT NULL,
  `DIAS` varchar(15) DEFAULT NULL,
  `MENSAJE` text DEFAULT NULL,
  `USUARIOID` int(11) DEFAULT NULL,
  `ESTATUS` int(5) DEFAULT NULL,

  PRIMARY KEY (IDMENSAJE)
);

CREATE TABLE `formulario` (
  `IDFORM` int(11) NOT NULL AUTO_INCREMENT,
  `ORDENID` varchar(50) NOT NULL,
  `CIBUYER` varchar(10) DEFAULT NULL,
  `TELEFONOBUYER` varchar(11) DEFAULT NULL,
  `CORREOBUYER` varchar(11) DEFAULT NULL,
  `BANKEMISOR` varchar(30) DEFAULT NULL,
  `BANKRECEPTOR` varchar(30) DEFAULT NULL,
  `PAGO` varchar(25) DEFAULT NULL,
  `FECHAPAGO` varchar(8) DEFAULT NULL,
  `REFERENCIA` varchar(50) DEFAULT NULL,
  `AGENCIA` varchar(50) DEFAULT NULL,
  `ESTADOENVIO` varchar(25) DEFAULT NULL,
  `MUNICIPIOENVIO` varchar(50) DEFAULT NULL,
  `CODIGOAGENCIA` varchar(25) DEFAULT NULL,
  `DIRECCIONENVIO` varchar(255) DEFAULT NULL,
  `TRANSACCION` varchar(25) DEFAULT NULL,

   PRIMARY KEY (IDFORM)
);
/* Nuevas Tablas */
CREATE TABLE `plan`(
  `IDPLAN` int NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(30) DEFAULT NULL,
  `DESCRIPCION` varchar(255) DEFAULT NULL,
  `TIEMPO` INT,
  `MONTO` FLOAT DEFAULT 0.0 ,

  PRIMARY KEY (IDPLAN)
);

CREATE TABLE `pagos`(
  `IDPAGO` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `TITULAR` varchar(100) DEFAULT NULL,
  `DOCID` varchar(100) DEFAULT NULL,
  `BANCOE` varchar(100) DEFAULT NULL,
  `BANCOR` varchar(100) DEFAULT NULL,
  `FECHA` DATE,
  `MONTO` FLOAT DEFAULT 0.0 ,
  `REFERENCIA` varchar(100) DEFAULT NULL,
  `ESTATUS` INT DEFAULT 0,

  PRIMARY KEY (IDPAGO)
);

CREATE TABLE `facturas`(
  `IDFACTURA` int(11) NOT NULL AUTO_INCREMENT,
  `user_id`int(11) NOT NULL,
  `FECHAEMISION` DATETIME,
  `MONTO` FLOAT DEFAULT 0.0  ,
  `DESCRIPCION` varchar(255) DEFAULT NULL,

  PRIMARY KEY (IDFACTURA)
);

CREATE TABLE `servicios`(
  `IDSERVICIOS` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `IDPLAN` int NOT NULL  DEFAULT 1,

  PRIMARY KEY (IDSERVICIOS),
  FOREIGN KEY (user_id) REFERENCES `usuario`(id)
  ON DELETE CASCADE
);

INSERT INTO `plan` (`IDPLAN`, `NOMBRE`, `DESCRIPCION`, `TIEMPO`, `MONTO`) VALUES ('-1', 'Membrecia Gratuita', 'EL cliente tiene acceso a todos los modulos de Oxas App durante un periodo de 21 dias.', '21', '0');

CREATE TABLE `municipios` (
  `IDMUNICIPIO` int(11) NOT NULL AUTO_INCREMENT,
  `MUNICIPIO` varchar(255) NOT NULL,
  `ESTADO` varchar(255) NOT NULL,

   PRIMARY KEY (IDMUNICIPIO)
);

CREATE TABLE `settings` (
	`field` VARCHAR(255) NULL DEFAULT NULL,
	`value` VARCHAR(255) NULL DEFAULT NULL
);

/*
Fin de Nuevas Tablas
*/

ALTER TABLE `enlace`
  ADD KEY `IDPUBLICACION` (`IDPUBLICACION`),
  ADD KEY `IDSINAPSIS` (`IDSINAPSIS`);

ALTER TABLE `sinapsis`
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `suscripcion`
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `enlace`
  ADD CONSTRAINT `enlace_ibfk_1` FOREIGN KEY (`IDPUBLICACION`) REFERENCES `publicacion` (`IDPUBLICACION`) ON DELETE CASCADE,
  ADD CONSTRAINT `enlace_ibfk_2` FOREIGN KEY (`IDSINAPSIS`) REFERENCES `sinapsis` (`IDSINAPSIS`) ON DELETE CASCADE;

ALTER TABLE `publicacion`
  ADD CONSTRAINT `publicacion_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

ALTER TABLE `sinapsis`
  ADD CONSTRAINT `sinapsis_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

ALTER TABLE `suscripcion`
  ADD CONSTRAINT `suscripcion_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;
COMMIT;

/* TABLA VENDEDORES MERCADO LIBRE TOTAL */
CREATE TABLE `sellers`(
  `id` int NOT NULL AUTO_INCREMENT,
  `CATEGORIA`     varchar(255),
  `NICKNAME`      varchar(255),
  `user_id`       varchar(255),
  `FECHAINICIOML` varchar(255),
  `TRANSACCIONES` varchar(255),
  `CONCRETADAS`   varchar(255),
  `REPUTACION`    varchar(255),
  `MLIDER`        varchar(255),
  `PUBLICACIONES` varchar(255),
  `ESTADO`        varchar(255),
  `CIUDAD`        varchar(255),
  `PERFILML`      varchar(255),

  PRIMARY KEY (id)
);

-- INSERTAR VALORES
INSERT INTO `settings` (`field`, `value`) 
       VALUES ('https_url_app', 'https://asvzla.ml'),
              ('app_id_vzla',      '7998065374551239'),
              ('secret_key_vzla',  'jj1SMfGUjsW7ooY9IgsmXGDNEmcgxPdC'),
              -- ('app_id_vzla',      '6021623127840893'),
              -- ('secret_key_vzla',  'esZADLxWSqBWYf7gf0Fcr9JSCzYpR3OR'),
              ('redirect_url',     'https://asvzla.ml/administracion/common/redirect.php');
