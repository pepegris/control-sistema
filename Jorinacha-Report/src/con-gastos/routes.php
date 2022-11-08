<?php

if (isset($_POST)) {

    include '../../includes/loading.php';
    include '../../services/sqlserver.php';

    
    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
    $fecha2 = date("Ymd", strtotime($_POST['fecha2']));  

  
    
      for ($i = 0; $i < 20; $i += 1) {
        $sedes[] = $_POST[$i];
      }
    
      $sedes = serialize($sedes);
      $sedes = urlencode($sedes);


    header("refresh:2;url= report-gastos.php?sedes=$sedes&fecha1=$fecha1&fecha2=$fecha2");



} else {
    header('refresh:1;url= form.php');
    exit;
}
