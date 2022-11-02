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


 
      for ($i = 1; $i < count($sedes_ar); $i++) {

        $reng_tip[$sedes_ar[$i]] = getReng_tip($sedes_ar[$i], 'todos', $fecha1, $fecha2);
       
  
      }




      

      for ($i = 0; $i < count($reng_tip); $i++) {

        var_dump($reng_tip[$sedes_ar[$i]]);
        echo"<br>";
      
      for ($o = 0; $o < count($reng_tip[$sedes_ar[$i]]); $o++) {
        
        var_dump($reng_tip[$sedes_ar[$i]][$o]);
        echo"<br>";

      for ($p=0; $p < count($reng_tip[$sedes_ar[$i]][$o]) ; $p++) { 

        $tipo_cob = $reng_tip[$sedes_ar[$f]][$o][$p];
        $doc_num = $reng_tip[$sedes_ar[$f]][$o][$p];
        $cob_num = $reng_tip[$sedes_ar[$f]][$o][$p];
        $movi = $reng_tip[$sedes_ar[$f]][$o][$p];
        $nombre_ban= $reng_tip[$sedes_ar[$f]][$o][$p];
        $mont_doc = $reng_tip[$sedes_ar[$f]][$o][$p];
        $fecha = $reng_tip[$sedes_ar[$f]][$o][$p];


        var_dump($reng_tip[$sedes_ar[$i]][$o][$p]);
        echo"<br>";
        
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