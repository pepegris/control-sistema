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
    numero varchar (150),
    contadores INT

 );



-- insertando nombre de las tiendas
    INSERT INTO  sedes VALUES (null,'Sede Boleita',null,'Xerox WC3550 MFP','Xerox WC3325 MFP','Xerox WC3550 MFP',null,'VMA561539','LA8186562','MXX976931',null,'0212-2398595',0);
    INSERT INTO  sedes VALUES  ( null,'Sede Sabana Grande',null,'Xerox WC3315 MFP',null,null,null,'LA4229130',null,null,null,'0212-7621715',0);
    INSERT INTO  sedes VALUES(null,'Comercial Merina','j-31229838-3','XeroxPhaser3250 P',null,null,null,'MXX976815',null,null,null,'0212-761-72-92',150);
    INSERT INTO  sedes VALUES(null,'Comercial Merina III','j-31229838-3','Xerox WC3315 MFP',null,null,null,'LA4229669',null,null,null,'0212-7629860',150);
    INSERT INTO  sedes VALUES(null,'Comercial Corina I','J-29627311-1','XeroxPhaser3250 P',null,null,null,'MXX976594',null,null,null,'0268-2525824',150);
    INSERT INTO  sedes VALUES   (null,'Comercial Corina II','J-29627311-1','XeroxPhaser3250 P',null,null,null,'MXX976578',null,null,null,'0268-2525824',200);
    INSERT INTO  sedes VALUES(null,'Comercial Punto Fijo','J-31147182-0','XeroxPhaser3250 P',null,null,null,'MXX976597',null,null,null,'0269-2472235',150);
    INSERT INTO  sedes VALUES(null,'Comercial Matur','j-31139416-8','XeroxPhaser3250 P',null,null,null,'MXX976807',null,null,null,'0291-6435344',300);
    INSERT INTO  sedes VALUES(null,'Comercial Valena','J-31138937-7','XeroxPhaser3250 P',null,null,null,'MXX976584',null,null,null,'0241-8580823',200);
    INSERT INTO  sedes VALUES(null,'Comercial Trina','j-29512057-5','XeroxPhaser3250 P',null,null,null,'MXX976589',null,null,null,'0268-2521279',300);
    INSERT INTO  sedes VALUES(null,'Comercial Kagu','J-31139470-2','XeroxPhaser3250 P',null,null,null,'MXX976580',null,null,null,'0244-447-32-92',250);
    INSERT INTO  sedes VALUES(null,'Comercial Nachari','J-31025136-3','XeroxPhaser3250 P',null,null,null,'MXX976766',null,null,null,'0269-2201935',200);
    INSERT INTO  sedes VALUES(null,'Comercial Higue','J-311394168','XeroxPhaser3250 P',null,null,null,'MXX976581',null,null,null,'0234-3234909',200);
    INSERT INTO  sedes VALUES(null,'Comercial Turme','J-31139422-2','XeroxPhaser3250 P',null,null,null,'MXX976582',null,null,null,'0244-663-95-22',150);
    INSERT INTO  sedes VALUES(null,'Comercial Apura','J-29600629-6','XeroxPhaser3250 P',null,null,null,'MXX976567',null,null,null,'0245-5644768',150);
    INSERT INTO  sedes VALUES(null,'Comercial Vallepa','j-31139458-3','XeroxPhaser3250 P',null,null,null,'MXX976820',null,null,null,'0235-3415355',200);
    INSERT INTO  sedes VALUES(null,'Comercial Ojena','J-31139466-4','XeroxPhaser3250 P',null,null,null,'MXX510113',null,null,null,'0265-6312851',200);
    INSERT INTO  sedes VALUES(null,'Comercial Puecruz','J-31139419-2','XeroxPhaser3250 P',null,null,null,'MXX976602',null,null,null,'0281-2653442',400);
    INSERT INTO  sedes VALUES(null,'Comercial Acari','j-31147177-4','XeroxPhaser3250 P',null,null,null,'MXX9766102',null,null,null,'0255-6238590',250);
        INSERT INTO  sedes VALUES(null,'Comercial Catica II','J-30691040-9','XeroxPhaser3250 P',null,null,null,'LA4229121',null,null,null,'',150);


 

    
           


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
  serv_des VARCHAR(255),
  serv_img VARCHAR(255),
  serv_fecha DATE NOT NULL,
  fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP 
);

    INSERT INTO  servidor_auditoria VALUES (null,'Sede Boleita',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES  ( null,'Sede Sabana Grande',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Merina',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Merina III',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Corina I',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES   (null,'Comercial Corina II',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Punto Fijo',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Matur',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Valena',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Trina',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Kagu',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Nachari',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Higue',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Turme',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Apura',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Vallepa',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Ojena',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Puecruz',null,null,now(),now());
    INSERT INTO  servidor_auditoria VALUES(null,'Comercial Acari',null,null,now(),now());


    CREATE TABLE fiscal_auditoria (
  id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  tienda VARCHAR(85) NOT NULL,
  fis_des VARCHAR(255),
  fis_img VARCHAR(255),
  fis_fecha DATE NOT NULL,
  fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP 

    INSERT INTO  fiscal_auditoria VALUES (null,'Sede Boleita',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES  ( null,'Sede Sabana Grande',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Merina',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Merina III',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Corina I',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES   (null,'Comercial Corina II',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Punto Fijo',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Matur',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Valena',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Trina',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Kagu',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Nachari',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Higue',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Turme',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Apura',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Vallepa',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Ojena',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Puecruz',null,null,now(),now());
    INSERT INTO  fiscal_auditoria VALUES(null,'Comercial Acari',null,null,now(),now());
);

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







