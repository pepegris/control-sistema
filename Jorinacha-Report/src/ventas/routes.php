<?php
require '../../includes/log.php';
include '../../includes/loading.php';
include '../../services/sqlserver.php';
include '../../services/mysql.php';

if (isset($_POST)  ) {


    $clave=$_POST['clave'];




if ($clave === 'N3td0s' ) {
    
    $reporte=$_POST['reporte'];
    $divisa=$_POST['divisa'];
    $linea=$_POST['linea'];

    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
    $fecha2 = date("Ymd", strtotime($_POST['fecha2']));  

  
    
      for ($i = 0; $i < 20; $i += 1) {
        $sedes[] = $_POST[$i];
      }
    
      $sedes = serialize($sedes);
      $sedes = urlencode($sedes);

    if ($reporte=='diario') {

        header("refresh:1;url= report-ventas-diaria.php?fecha1=$fecha1&divisa=$divisa&sedes=$sedes");

    }elseif ($reporte=='dias') {

        header("refresh:1;url= report-ventas-diaria-todo.php?fecha1=$fecha1&fecha2=$fecha2&divisa=$divisa&sedes=$sedes");

    }    
    elseif ($reporte=='mes') {

        if ($linea=='todos') {
            if ($divisa== 'dl') {

                header("refresh:1;url= report-ventas-mes-todo-dolares.php?fecha1=$fecha1&fecha2=$fecha2&linea=$linea&divisa=$divisa&sedes=$sedes");
            }    else {
    
                header("refresh:1;url= report-ventas-mes-todo-bolivares.php?fecha1=$fecha1&fecha2=$fecha2&linea=$linea&divisa=$divisa&sedes=$sedes");
            }
        }else {
            if ($divisa== 'dl') {

                header("refresh:1;url= report-ventas-mes-marcas-dolares.php?fecha1=$fecha1&fecha2=$fecha2&linea=$linea&divisa=$divisa&sedes=$sedes");
            }    else {
    
                header("refresh:1;url= report-ventas-mes-marcas-bolivares.php?fecha1=$fecha1&fecha2=$fecha2&linea=$linea&divisa=$divisa&sedes=$sedes");
            }
        }




        

    } 
    
    elseif ($reporte=='acumulado') {

        if ($fecha2) {
            
            if ($divisa== 'dl') {

                header("refresh:1;url= report-ventas-acumulado-dolares.php?fecha1=$fecha1&fecha2=$fecha2&divisa=$divisa&sedes=" . $sedes);
            }    else {

                header("refresh:1;url= report-ventas-acumulado.php?fecha1=$fecha1&fecha2=$fecha2&divisa=$divisa&sedes=" . $sedes);
            }

        }    else {

            header('refresh:1;url= form.php');

        }
        
    }elseif ($reporte=='ventas'){

        header("refresh:1;url= report-ventas-detalle.php?fecha1=$fecha1&divisa=$divisa&sedes=$sedes");

        /* reporte de ventas de tienda en desarrollo */

/*         if ($fecha2) {
            header("refresh:1;url= report-ventas-tiendas.php?fecha1=$fecha1&fecha2=$fecha2&divisa=$divisa&sedes=" . $sedes);
        }    else {
            header('refresh:1;url= form.php');
        } */
        

    }elseif ($reporte=='ord'){
        
        header("refresh:1;url= report-ventas-ordenes.php?fecha1=$fecha1&fecha2=$fecha2&divisa=$divisa&sedes=$sedes");

    } elseif ($reporte=='factura'){
        
        header("refresh:1;url= report-ventas-facturas.php?fecha1=$fecha1&fecha2=$fecha2&divisa=$divisa&sedes=$sedes");

    }
    else {

        header('refresh:1;url= form.php');

    }

}else {

    header('refresh:1;url= form.php');

}

} else {
    header('refresh:1;url= form.php');
    exit;
}
