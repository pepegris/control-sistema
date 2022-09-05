<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

?>


<div id="body">
  <h1>Replica</h1>

  <?php

  for ($i=0; $i < count($sedes_ar) ; $i++) { 

    if ($sedes_ar[$i] != 'Previa Shop') {

      $res = Replica($sedes_ar[$i]);
      $sede = $sedes_ar[$i];
      $res1= $res['fe_us_in'];
      $fecha =$res1->format('Y-m-d');
      
      
      echo "<p>$sede</p>";
      echo "<p>$fecha</p>";
      echo "<br>";



      }
  }

  ?>




</div>




<?php include '../../includes/footer.php'; ?>