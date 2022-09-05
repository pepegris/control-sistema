create database control_sistema;

use control_sistema;

CREATE TABLE empresa(
  
  empresa VARCHAR(100) not null ,
  rif varchar (90) not null ,
  dept varchar (80),
  numero varchar (100) ,
  direccion varchar(240) 

 
);


INSERT INTO  empresa VALUES ('Inversiones Jorinacha, C.A','J-30787562-3','Departamento de Sistemas','0212-2398595','Distrito Capital, Boleita Norte, Edificio Orion');



-- creating a new table
CREATE TABLE sedes (

    id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    sedes_nom VARCHAR (85) NOT NULL unique,
    rif VARCHAR (255)  ,

    impresora1 varchar (150),
    impresora2 varchar (150),
    impresora3 varchar (150),
    impresora4 varchar (150),
    serial_imp1 varchar (150) unique,
    serial_imp2 varchar (150) unique,
    serial_imp3 varchar (150) unique,
    serial_imp4 varchar (150) unique,

    fiscal_marca1 varchar (150),
    fiscal_marca2 varchar (150),
    fiscal_marca3 varchar (150),
    fiscal_marca4 varchar (150),
    fiscal_modelo1 varchar (150),
    fiscal_modelo2 varchar (150),
    fiscal_modelo3 varchar (150),
    fiscal_modelo4 varchar (150),
    fiscal_serial1 varchar (150) unique,
    fiscal_serial2 varchar (150) unique,
    fiscal_serial3 varchar (150) unique,
    fiscal_serial4 varchar (150) unique,
    fiscal_nregistro1 varchar (150) unique,
    fiscal_nregistro2 varchar (150) unique,
    fiscal_nregistro3 varchar (150) unique,
    fiscal_nregistro4 varchar (150) unique,

    numero varchar (150),
    contadores INT,

    estado_sede ENUM('activo','inactivo') NOT NULL DEFAULT 'activo'

 );



-- insertando nombre de las tiendas
    INSERT INTO  sedes VALUES (null,'Sede Boleita',null,'Xerox WC3550 MFP','Xerox WC3325 MFP','Xerox WC3550 MFP',null,'VMA561539','LA8186562','MXX976931',null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0212-2398595',0);
    INSERT INTO  sedes VALUES  ( null,'Sede Sabana Grande',null,'Xerox WC3315 MFP',null,null,null,'LA4229130',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0212-7621715',0);
    INSERT INTO  sedes VALUES(null,'Comercial Merina','j-31229838-3','XeroxPhaser3250 P',null,null,null,'MXX976815',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0212-761-72-92',150);
    INSERT INTO  sedes VALUES(null,'Comercial Merina III','j-31229838-3','Xerox WC3315 MFP',null,null,null,'LA4229669',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0212-7629860',150);
    INSERT INTO  sedes VALUES(null,'Comercial Corina I','J-29627311-1','XeroxPhaser3250 P',null,null,null,'MXX976594',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0268-2525824',150);
    INSERT INTO  sedes VALUES   (null,'Comercial Corina II','J-29627311-1','XeroxPhaser3250 P',null,null,null,'MXX976578',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0268-2525824',200);
    INSERT INTO  sedes VALUES(null,'Comercial Punto Fijo','J-31147182-0','XeroxPhaser3250 P',null,null,null,'MXX976597',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0269-2472235',150);
    INSERT INTO  sedes VALUES(null,'Comercial Matur','j-31139416-8','XeroxPhaser3250 P',null,null,null,'MXX976807',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0291-6435344',300);
    INSERT INTO  sedes VALUES(null,'Comercial Valena','J-31138937-7','XeroxPhaser3250 P',null,null,null,'MXX976584',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0241-8580823',200);
    INSERT INTO  sedes VALUES(null,'Comercial Trina','j-29512057-5','XeroxPhaser3250 P',null,null,null,'MXX976589',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0268-2521279',300);
    INSERT INTO  sedes VALUES(null,'Comercial Kagu','J-31139470-2','XeroxPhaser3250 P',null,null,null,'MXX976580',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0244-447-32-92',250);
    INSERT INTO  sedes VALUES(null,'Comercial Nachari','J-31025136-3','XeroxPhaser3250 P',null,null,null,'MXX976766',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0269-2201935',200);
    INSERT INTO  sedes VALUES(null,'Comercial Higue','J-311394168','XeroxPhaser3250 P',null,null,null,'MXX976581',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0234-3234909',200);
    INSERT INTO  sedes VALUES(null,'Comercial Turme','J-31139422-2','XeroxPhaser3250 P',null,null,null,'MXX976582',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0244-663-95-22',150);
    INSERT INTO  sedes VALUES(null,'Comercial Apura','J-29600629-6','XeroxPhaser3250 P',null,null,null,'MXX976567',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0245-5644768',150);
    INSERT INTO  sedes VALUES(null,'Comercial Vallepa','j-31139458-3','XeroxPhaser3250 P',null,null,null,'MXX976820',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0235-3415355',200);
    INSERT INTO  sedes VALUES(null,'Comercial Ojena','J-31139466-4','XeroxPhaser3250 P',null,null,null,'MXX510113',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0265-6312851',200);
    INSERT INTO  sedes VALUES(null,'Comercial Puecruz','J-31139419-2','XeroxPhaser3250 P',null,null,null,'MXX976602',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0281-2653442',400);
    INSERT INTO  sedes VALUES(null,'Comercial Acari','j-31147177-4','XeroxPhaser3250 P',null,null,null,'MXX9766102',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'0255-6238590',250);
        INSERT INTO  sedes VALUES(null,'Comercial Catica II','J-30691040-9','XeroxPhaser3250 P',null,null,null,'LA4229121',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'',150);

        INSERT INTO  sedes VALUES(null,'Comercial Catica I','J-31451524-1','XeroxPhaser3250 P',null,null,null,'MXX9765821',null,null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,'',150);

 

    
           


CREATE TABLE fiscal (
  id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  tienda VARCHAR(85) NOT NULL,
  fis_des VARCHAR(255),
  fis_img VARCHAR(255),
  fis_fecha DATE NOT NULL,
  fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP 
);


CREATE TABLE servidor_auditoria (
  id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  tienda VARCHAR(85) NOT NULL,
  serv_mac VARCHAR(85) NOT NULL,
  serv_proc VARCHAR(85) NOT NULL,
  serv_ram VARCHAR(85) NOT NULL,
  serv_disc VARCHAR(85) NOT NULL,
  serv_vid VARCHAR(85) NOT NULL,
  serv_red VARCHAR(85),
  serv_des VARCHAR(255),
  serv_img VARCHAR(255),
  serv_fecha DATE NOT NULL,
  fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP 
);

    INSERT INTO  servidor_auditoria VALUES (null,'Sede Boleita','dir mac','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES  ( null,'Sede Sabana Grande','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Merina','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Merina III','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Corina I','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES   (null,'Comercial Corina II','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Punto Fijo','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Matur','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Valena','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Trina','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Kagu','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Nachari','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Higue','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Turme','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Apura','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Vallepa','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Ojena','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Puecruz','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Acari','direccion mac','procesador','memoria ram','disco duro','tarjeta de video','tarjeta de red',null,null,now(),now());


    CREATE TABLE fiscal_auditoria (
  id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  tienda VARCHAR(85) NOT NULL,
  fis_marca1 varchar (150),
  fis_marca2 varchar (150),
  fis_marca3 varchar (150),
  fis_marca4 varchar (150),
  fis_modelo1 varchar (150),
  fis_modelo2 varchar (150),
  fis_modelo3 varchar (150),
  fis_modelo4 varchar (150),
  fis_serial1 varchar (150) unique,
  fis_serial2 varchar (150) unique,
  fis_serial3 varchar (150) unique,
  fis_serial4 varchar (150) unique,
  fis_nregistro1 varchar (150) unique,
  fis_nregistro2 varchar (150) unique,
  fis_nregistro3 varchar (150) unique,
  fis_nregistro4 varchar (150) unique,
  fis_des VARCHAR(255),
  fis_img VARCHAR(255),
  fis_fecha DATE NOT NULL,
  fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  estado1 VARCHAR(25) NOT NULL,
  estado2 VARCHAR(25) NOT NULL,
  estado3 VARCHAR(25) NOT NULL,
  estado4 VARCHAR(25) NOT NULL


);

/*     INSERT INTO  fiscal_auditoria VALUES (null,'Sede Boleita',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES  ( null,'Sede Sabana Grande',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO'); */
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Merina',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Merina III',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Corina I',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES   (null,'Comercial Corina II',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Punto Fijo',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Matur',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Valena',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Trina',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Kagu',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Nachari',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Higue',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Turme',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Apura',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Vallepa',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Ojena',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Puecruz',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Acari',null,null,null,null,null,null, null,null,null,null,null,null,null,null, null,null,null,null,now(),now(),'OPERATIVO','DAÑADO','OPERATIVO','DAÑADO');


CREATE TABLE contador (

    id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    tienda VARCHAR(85) NOT NULL,
    con_des VARCHAR(255),
    con_img VARCHAR(255),
    con_img2 VARCHAR(255),
    con_img3 VARCHAR(255),
    impresora1 varchar (150) not null,
    impresora2 varchar (150),
    impresora3 varchar (150),
    serial_imp1 varchar (150) not null,
    serial_imp2 varchar (150),
    serial_imp3 varchar (150),
    inicial1 INT (90) NOT NULL,
    inicial2 INT (90) ,
    inicial3 INT (90) ,
    con_fecha DATE NOT NULL,
    fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP 


);

CREATE TABLE equipos (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    usuario VARCHAR (85) not null,
    equipo VARCHAR (85) not null,
    eq_des VARCHAR(255),
    estado VARCHAR(85) ,
    eq_fecha DATE NOT NULL,
    fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP 

);

--login 
create table usuario(  
  id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  usuario VARCHAR(150) UNIQUE NOT NULL,
  clave VARCHAR(200) NOT NULL,
  telefono VARCHAR(150),
  fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
  );

create table reporte (

    id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,  
    titulo VARCHAR (85) not null,
    descrip VARCHAR(255) not null,
    estado VARCHAR(85) ,
    fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP

);


create table bolsas (
  id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY, 
  tienda VARCHAR(85) NOT NULL,
  bolsa_1 VARCHAR (85) not null,
  bolsa_2 VARCHAR (85) not null,
  bolsa_3 VARCHAR (85) not null,
  bolsa_4 VARCHAR (85) ,
  bolsa_5 VARCHAR (85) ,
  cantidad_1 VARCHAR (85) not null,
  cantidad_2 VARCHAR (85) not null,
  cantidad_3 VARCHAR (85) not null,
  cantidad_4 VARCHAR (85) not null,
  cantidad_5 VARCHAR (85) not null,
  fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP

  
);

    INSERT INTO  sedes VALUES(null,'Comercial Merina', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Merina III', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Corina I', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Corina II', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Punto Fijo', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Matur', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Valena', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Trina', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Kagu', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Nachari', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Higue', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Turme', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Apura', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Vallepa', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Ojena', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Puecruz', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Acari', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);
    INSERT INTO  sedes VALUES(null,'Comercial Catica II', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0); 
    INSERT INTO  sedes VALUES(null,'Comercial Catica I', 'Grande','Mediana','Pequeña',null,null,0,0,0,0,0);

