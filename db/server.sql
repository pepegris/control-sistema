create database control_sistema;

use control_sistema;

-- creating a new table
CREATE TABLE sedes (

    id INT  IDENTITY(1,1) NOT NULL PRIMARY KEY,
    sedes_nom VARCHAR (85) NOT NULL unique,
    rif VARCHAR (255)

 );



-- insertando nombre de las tiendas
    INSERT INTO  sedes (sedes_nom,rif) VALUES ('Sede Boleita',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES  ( 'Sede Sabana Grande',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Merina',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Merina III',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Corina I',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES   ('Comercial Corina II',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Punto Fijo',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Matur',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Valena',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Trina',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Kagu',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Nachari',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Higue',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Turme',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Apura',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Vallepa',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Ojena',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Puecruz',null);
    INSERT INTO  sedes (sedes_nom,rif) VALUES('Comercial Acari',null);

   
                        


CREATE TABLE fiscal (
  id INT  IDENTITY(1,1) NOT NULL PRIMARY KEY,
  tienda VARCHAR(85) NOT NULL,
  fis_des VARCHAR(255),
  fis_img VARCHAR(255),
  fis_fecha DATE NOT NULL,
  fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE contador (

    id INT  IDENTITY(1,1) NOT NULL PRIMARY KEY,
    tienda VARCHAR(85) NOT NULL,
    con_des VARCHAR(255),
    con_img VARCHAR(255),
    con_fecha DATE NOT NULL,
    fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP 


);

CREATE TABLE equipos (
    id INT  IDENTITY(1,1) NOT NULL PRIMARY KEY,
    usuario VARCHAR (85) not null,
    equipo VARCHAR (85) not null,
    eq_des VARCHAR(255),
    estado VARCHAR(85) ,
    eq_fecha DATE NOT NULL,
    fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP 

);









