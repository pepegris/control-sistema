<?php

if (isset($_POST)) {

    include '../../includes/loading.php';
    include '../../services/sqlserver.php';

    $reporte=$_POST['reporte'];

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

    if ($reporte=='detal') {
        header("refresh:2;url= report-art-stock-detal.php?linea=$linea&fecha1=$fecha1&fecha2=$fecha2&sedes=$sedes");
    }elseif ($reporte=='precios') {
        header("refresh:2;url= report-art-stock-precios.php?linea=$linea&fecha1=$fecha1&fecha2=$fecha2&sedes=" . $sedes);
    }elseif ($reporte=='global'){
        header("refresh:2;url= report-art-stock-global.php?linea=$linea&fecha1=$fecha1&fecha2=$fecha2&sedes=" . $sedes);

    }
    else {
        header('refresh:1;url= form.php');
    }

} else {
    header('refresh:1;url= form.php');
    exit;
}
