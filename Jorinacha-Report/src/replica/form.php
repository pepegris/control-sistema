<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

?>
<center><h1>Replica</h1></center>

<div id="body">

  <ul class="list-group">
    <?php

    for ($i = 0; $i < count($sedes_ar); $i++) {

      if ($sedes_ar[$i] != 'Previa Shop') {

        $res = Replica($sedes_ar[$i]);
        $sede = $sedes_ar[$i];
        $res1 = $res['fe_us_in'];
        $fecha = $res1->format('Y-m-d');
    ?>

        <li class="list-group-item"><b style="color:black" ><?= $sede ?></b> / <?=$fecha ?></li>



    <?php

      }
    }

    ?>

  </ul>


</div>




<?php include '../../includes/footer.php'; ?>