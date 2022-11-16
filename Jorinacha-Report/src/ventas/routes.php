<?php

if (isset($_POST)) {

    include '../../includes/loading.php';
    include '../../services/sqlserver.php';

    $reporte=$_POST['reporte'];
    $divisa=$_POST['divisa'];

    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
    $fecha2 = date("Ymd", strtotime($_POST['fecha2']));  

  
    
      for ($i = 0; $i < 20; $i += 1) {
        $sedes[] = $_POST[$i];
      }
    
      $sedes = serialize($sedes);
      $sedes = urlencode($sedes);

    if ($reporte=='diario') {

        header("refresh:1;url= report-ventas-diaria.php?fecha1=$fecha1&divisa=$divisa&sedes=$sedes");

    }elseif ($reporte=='acumulado') {

        if ($fecha2) {
            
            if ($divisa== 'dl') {

                header("refresh:1;url= report-ventas-acumulado-dolores.php?fecha1=$fecha1&fecha2=$fecha2&divisa=$divisa&sedes=" . $sedes);
            }    else {

                header("refresh:1;url= report-ventas-acumulado.php?fecha1=$fecha1&fecha2=$fecha2&divisa=$divisa&sedes=" . $sedes);
            }

        }    else {

            header('refresh:1;url= form.php');

        }
        
    }elseif ($reporte=='ventas'){

        if ($fecha2) {
            header("refresh:1;url= report-ventas-tiendas.php?fecha1=$fecha1&fecha2=$fecha2&divisa=$divisa&sedes=" . $sedes);
        }    else {
            header('refresh:1;url= form.php');
        }
        

    } else {

        header('refresh:1;url= form.php');

    }

} else {
    header('refresh:1;url= form.php');
    exit;
}
