<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

?>
<style>
  img {


    width: 36px;
  }
</style>


<div id="body">

  <ul class="list-group">
    <li class="list-group-item disabled" style="background-color:black" aria-disabled="true">
      <center><b>Replica</b></center>

    </li>
    <?php

    for ($i = 0; $i < count($sedes_ar); $i++) {

      if ($sedes_ar[$i] != 'Previa Shop') {

        $sede = $sedes_ar[$i];

        $res = Replica($sedes_ar[$i]);
        $res1 = $res['fec_emis'];
        $fecha = $res1->format('d-m-Y');

        $fecha_actual = date("d-m-Y");

        $fecha1 = date("d-m-Y", strtotime($fecha_actual . "- 3 day"));
        $fecha2 = date("d-m-Y", strtotime($fecha_actual . "- 7 day"));


        $past = new DateTime($fecha);
        $now_1 = new DateTime($fecha1);
        $now_2 = new DateTime($fecha2);



        if ($past  >= $now_1) {

          echo "<li class='list-group-item'><b style='color:black'> $sede </b> /  $fecha  <img src='./img/cloud-check.svg' alt=''> </li>";
        } elseif ($past  >= $now_2) {
          echo "<li class='list-group-item'><b style='color:black'> $sede </b> /  $fecha  <img src='./img/cloud-sync.svg' alt=''> </li>";
        } else {
          echo "<li class='list-group-item'><b style='color:black'> $sede </b> /  $fecha  <img src='./img/cloud-upload.svg' alt=''> </li>";
        }
      }
    }




    ?>

  </ul>






</div>



<?php include '../../includes/footer.php'; ?>