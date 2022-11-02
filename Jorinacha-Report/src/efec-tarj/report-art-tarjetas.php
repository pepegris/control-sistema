<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/efec-tarj/efec-tarj.php';




if (isset($_GET)) {


  $tipo_cob = $_GET['tipo_cob'];
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));


  for ($i = 0; $i < 20; $i += 1) {
    $sedes[] = $_GET[$i];
  }



?>


  <center>
    <h1>Cobros</h1>
  </center>
  <table class="table table-dark table-striped" id="tblData">
    <thead>
      <tr>

        <?php

        for ($i = 0; $i < count($sedes_ar); $i++) {




          if ($sedes_ar[$i] != null) {

            $sede = $sedes_ar[$i];

        ?>


            <?php
            if ($sedes_ar[$i] != 'Previa Shop') {

              echo "<th scope='col'>Fecha $sede</th>";
              echo "<th scope='col'>Tipo $sede</th>";
              echo "<th scope='col'>Factura $sede</th>";
              echo "<th scope='col'>Cobro $sede</th>";
              echo "<th scope='col'>Mov $sede</th>";

              echo "<th scope='col'>Banco $sede</th>";
              echo "<th scope='col'>Monto $sede</th>";
            }

            ?>
        <?php }
        } ?>

      </tr>
    </thead>

    <tbody>

      <!-- TIENDAS -->
      <?php


      $f = 1;
      for ($i = 0; $i < count($sedes_ar); $i++) {

        $reng_tip[$sedes_ar[$f]] = getReng_tip($sedes_ar[$f], 'todos', $fecha1, $fecha2);
       
        $f++;
      }

      var_dump($reng_tip['Comercial Corina I'][1]);
      

      $e = 0;
      for ($i = 0; $i < count($sedes_ar); $i++) {
      $f = 1;
      for ($i = 0; $i < count($sedes_ar); $i++) {


        if ($sedes_ar[$f] == null) {
          $f++;
        } else {

          $tipo_cob = $reng_tip[$sedes_ar[$f]][$e];
          $doc_num = $reng_tip[$sedes_ar[$f]][$e];
          $cob_num = $reng_tip[$sedes_ar[$f]][$e];
          $movi = $reng_tip[$sedes_ar[$f]][$e];
          $nombre_ban= $reng_tip[$sedes_ar[$f]][$e];
          $mont_doc = $reng_tip[$sedes_ar[$f]][$e];
          $fecha = $reng_tip[$sedes_ar[$f]][$e];

          $total_mont_doc [$sedes_ar[$f]] +=$mont_doc;
          



      ?>
          <tr>
            <td><?= $fecha  ?></td>
            <td><?= $tipo_cob  ?></td>
            <td><?= $doc_num  ?></td>
            <td><?= $cob_num  ?></td>
            <td><?= $movi  ?></td>
            <td><?= $mont_doc  ?></td>
            

          </tr>
      <?php
          $f++;
          $e++;
        }
      }
    }
      ?>






      <tr>
        
        <td colspan="1"></td>
        <td>
          <h4>Totales:</h4>
        </td>

        <?php

        $h = 1;
        for ($i = 0; $i < count($total_mont_doc); $i++) {
          

        ?>
          <td><b><?= $total_mont_doc [$sedes_ar[$h]] ?></b></td>






        <?php

          $h++;
        }

        ?>
      </tr>

    </tbody>
  </table>




<?php
var_dump($fecha2);
var_dump($tipo_cob);
var_dump($reng_tip);
  Cerrar(null);
} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>