<?php
  require '../../includes/log.php';
  include '../../includes/loading.php';
  include '../../services/sqlserver.php';

if (isset($_POST)) {


    $ordenes = $_POST['ordenes'];
    $fecha1 = $_POST['fecha1'];
    $fecha2 = $_POST['fecha2'];
  
    
      for ($i = 0; $i < 20; $i += 1) {
        $sedes[] = $_POST[$i];
      }
    
      $sedes = serialize($sedes);
      $sedes = urlencode($sedes);


    if ($ordenes== 'tiendas') {

      header("refresh:2;url= report-art-stock.php?fecha1=$fecha1&fecha2=$fecha2&sedes=$sedes");

    }else{

      header("refresh:2;url= report-art-stock.php?fecha1=$fecha1&fecha2=$fecha2&sedes=$sedes");

    }



} else {
    header('refresh:1;url= form.php');
    exit;
}
