<?php

require_once '../includes/conexion_control.php';

/* $sql_empresa = "SELECT * FROM empresa  ";
$consulta_empresa = mysqli_query($conn,$sql_empresa);
 */
$consulta_empresa="SELECT * FROM empresa";
$datos_empresa=mysqli_query($conn,$consulta_empresa);

    $datos=mysqli_fetch_assoc($datos_empresa);

    $empresa=$datos['empresa'];
    $rif=$datos['rif'];
    $numero=$datos['numero'];
    $departamento=$datos['dept'];
    $direccion=$datos['direccion'];

   












?>