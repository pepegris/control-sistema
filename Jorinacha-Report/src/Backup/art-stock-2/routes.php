<?php

if (isset($_POST)) {

    include '../../includes/loading.php';
    include '../../services/sqlserver.php';

    $pedidos=$_POST['pedidos'];
    $almacen=$_POST['almacen'];

    $linea = $_POST['linea'];
    /*   $fecha1 = $_POST['fecha1'];
      $fecha2 = $_POST['fecha2']; */
      $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
      $fecha2 = date("Ymd", strtotime($_POST['fecha2']));  
    
      for ($i = 0; $i < 20; $i += 1) {
        $sedes[] = $_POST[$i];
      }
    
      $sedes = serialize($sedes);
      $sedes = urlencode($sedes);


    header("refresh:2;url= report-art-stock.php?linea=$linea&fecha1=$fecha1&fecha2=$fecha2&almacen=$almacen&sedes=" . $sedes);


} else {
    header('refresh:1;url= form.php');
    exit;
}
