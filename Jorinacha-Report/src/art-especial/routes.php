<?php
    require '../../includes/log.php';

if (isset($_POST)) {

    include '../../includes/loading.php';
    include '../../services/sqlserver.php';

    $tienda=$_POST['tienda'];

    $linea = $_POST['linea'];

  
    
      for ($i = 0; $i < 20; $i += 1) {
        $sedes[] = $_POST[$i];
      }
    
      $sedes = serialize($sedes);
      $sedes = urlencode($sedes);

    header("refresh:2;url= report-art-especial.php?linea=$linea&tienda=$tienda&sedes=$sedes");


} else {
    header('refresh:1;url= form.php');
    exit;
}
