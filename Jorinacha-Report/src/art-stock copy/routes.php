<?php

if (isset($_POST)) {

    include '../../includes/loading.php';

    $pedidos=$_POST['pedidos'];

    $linea = $_POST['linea'];
    /*   $fecha1 = $_POST['fecha1'];
      $fecha2 = $_POST['fecha2']; */
      $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
      $fecha2 = date("Ymd", strtotime($_POST['fecha2']));  
    
      for ($i = 0; $i < 20; $i += 1) {
        $sedes[] = $_POST[$i];
      }
    

    if ($pedidos=='con') {
        header("refresh:5;url= report-art-stock-completo.php?linea=$linea&fecha1=$fecha1&fecha2=$fecha2");
    }elseif ($pedidos=='sin') {
        header("refresh:5;url= report-art-stock.php?linea=$linea&fecha1=$fecha1&fecha2=$fecha2");
    }else {
        header('refresh:5;url= form.php');
    }

} else {
    header('refresh:5;url= form.php');
    exit;
}
