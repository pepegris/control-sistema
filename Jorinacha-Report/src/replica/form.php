<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

?>


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
    ?>

        <li class="list-group-item"><b style="color:black"><?= $sede ?></b> / <?= $fecha ?></i></li>



    <?php

      }
    }



    $fecha_actual = strtotime(date("d-m-Y", time()));
    $fecha_entrada = strtotime($fecha);

    //sumo 1 mes
    //echo date("d-m-Y",strtotime($fecha_actual."+ 1 month")); 
    //resto 1 mes
    $fecha2= date("d-m-Y",strtotime($fecha_actual."- 1 month"));

    echo "$fecha2 <br>";

    if ($fecha_actual > $fecha_entrada) {
      echo "La fecha actual es mayor a la comparada.";
    }elseif ($fecha2 > $fecha_entrada) {
      echo "2La fecha comparada es igual o menor2";
    }
     else {
      echo "La fecha comparada es igual o menor";
    }
    ?>

  </ul>

  <i class="lni lni-cloud-check"></i>
  <i class="lni lni-cloud-sync"></i>
  <i class="lni lni-cloud-sync"></i>

</div>




<?php include '../../includes/footer.php'; ?>