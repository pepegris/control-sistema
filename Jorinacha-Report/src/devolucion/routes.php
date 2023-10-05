<?php
  require '../../includes/log.php';
  include '../../includes/loading.php';
  include '../../services/sqlserver.php';

if (isset($_POST)) {


    $tipo_dev=$_POST['tipo_dev'];
    $art=$_POST['art'];
    $linea=$_POST['linea'];
  

    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
    $fecha2 = date("Ymd", strtotime($_POST['fecha2']));  


  
    
       for ($i = 0; $i < 20; $i += 1) {
        $sedes[] = $_POST[$i];
      }
    
      $sedes = serialize($sedes);
      $sedes = urlencode($sedes);


      if ($tipo_dev== 'cliente') {

        header("refresh:2;url= report-devoluciones-cli.php?fecha1=$fecha1&fecha2=$fecha2&art=$art&linea=$linea&sedes=$sedes");
  
      }else {
  
        header("refresh:2;url= report-devoluciones-prov.php?fecha1=$fecha1&fecha2=$fecha2&art=$art&linea=$linea&sedes=$sedes");
        
      } 

    

 


} else {
    header('refresh:1;url= form.php');
    exit;
}
