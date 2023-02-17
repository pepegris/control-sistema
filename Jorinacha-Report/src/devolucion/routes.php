<?php
  require '../../includes/log.php';
  include '../../includes/loading.php';
  include '../../services/sqlserver.php';

if (isset($_POST)) {


    $tipo_dev=$_POST['tipo_dev'];
    $doc1=$_POST['doc1'];
    $doc2=$_POST['doc2'];
    $art=$_POST['art'];

    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
    $fecha2 = date("Ymd", strtotime($_POST['fecha2']));  



    echo"$tipo_dev <br>";
    echo"$doc1 <br>";
    echo"$doc2 <br>";
    echo"$art <br>";

  
    
/*       for ($i = 0; $i < 20; $i += 1) {
        $sedes[] = $_POST[$i];
      }
    
      $sedes = serialize($sedes);
      $sedes = urlencode($sedes);


      if ($tipo_dev== 'cliente') {

        header("refresh:2;url= report-devoluciones-cli.php?fecha1=$fecha1&fecha2=$fecha2&sedes=$sedes");
  
      }else {
  
        header("refresh:2;url= report-devoluciones-prov.php?fecha1=$fecha1&fecha2=$fecha2&sedes=$sedes");
        
      } */

    

 


} else {
    header('refresh:1;url= form.php');
    exit;
}
