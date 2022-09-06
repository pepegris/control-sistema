<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

?>


<div id="body">

  <ul class="list-group">
  <li class="list-group-item disabled" style="background-color:black"  aria-disabled="true"><center><b >Replica</b></center></li>

    <?php

    for ($i = 0; $i < count($sedes_ar); $i++) {

      if ($sedes_ar[$i] != 'Previa Shop') {

        $sede = $sedes_ar[$i];

        $res = Replica($sedes_ar[$i]);
        $res1 = $res['fec_emis'];
        $fecha = $res1->format('d-m-Y');
    ?>

        <li class="list-group-item"><b style="color:black" ><?= $sede ?></b> / <?=$fecha ?></i></li>



    <?php

      }
    }

    echo $hoy = date("Ymd");
    ?>

  </ul>

  <i class="lni lni-cloud-check"></i>
  <i class="lni lni-cloud-sync"></i>
  <i class="lni lni-cloud-sync"></i>

</div>




<?php include '../../includes/footer.php'; ?>