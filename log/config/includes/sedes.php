<?php

require_once '../includes/conexion_control.php';

/* $sql_empresa = "SELECT * FROM empresa  ";
$consulta_empresa = mysqli_query($conn,$sql_empresa);
 */
$consulta_sedes="SELECT * FROM sedes";
$datos_sedes=mysqli_query($conn,$consulta_sedes);

    $datos=mysqli_fetch_assoc($datos_empresa);

    $sedes_nom=$datos['sedes_nom'];
    $rif=$datos['rif'];
    $numero=$datos['numero'];
   












?>