create database control_sistema;

use control_sistema;

-- creating a new table
CREATE TABLE sedes (

    id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    sedes_nom VARCHAR (85) NOT NULL unique,
    rif VARCHAR (255)

 );



-- insertando nombre de las tiendas
    INSERT INTO  sedes VALUES (null,'Sede Boleita',null);
    INSERT INTO  sedes VALUES  ( null,'Sede Sabana Grande',null);
    INSERT INTO  sedes VALUES(null,'Comercial Merina',null);
    INSERT INTO  sedes VALUES(null,'Comercial Merina III',null);
    INSERT INTO  sedes VALUES(null,'Comercial Corina I',null);
    INSERT INTO  sedes VALUES   (null,'Comercial Corina II',null);
    INSERT INTO  sedes VALUES(null,'Comercial Punto Fijo',null);
    INSERT INTO  sedes VALUES(null,'Comercial Matur',null);
    INSERT INTO  sedes VALUES(null,'Comercial Valena',null);
    INSERT INTO  sedes VALUES(null,'Comercial Trina',null);
    INSERT INTO  sedes VALUES(null,'Comercial Kagu',null);
    INSERT INTO  sedes VALUES(null,'Comercial Nachari',null);
    INSERT INTO  sedes VALUES(null,'Comercial Higue',null);
    INSERT INTO  sedes VALUES(null,'Comercial Turme',null);
    INSERT INTO  sedes VALUES(null,'Comercial Apura',null);
    INSERT INTO  sedes VALUES(null,'Comercial Vallepa',null);
    INSERT INTO  sedes VALUES(null,'Comercial Ojena',null);
    INSERT INTO  sedes VALUES(null,'Comercial Puecruz',null);
    INSERT INTO  sedes VALUES(null,'Comercial Acari',null);

   
                        


CREATE TABLE fiscal (
  id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  tienda VARCHAR(85) NOT NULL,
  fis_des VARCHAR(255),
  fis_img VARCHAR(255),
  fis_fecha DATE NOT NULL,
  fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE contador (

    id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    tienda VARCHAR(85) NOT NULL,
    con_des VARCHAR(255),
    con_img VARCHAR(255),
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









