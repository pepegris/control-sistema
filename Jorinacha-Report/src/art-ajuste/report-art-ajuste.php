<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

if (isset($_GET)) {


  $linea = $_GET['linea'];
  $almacen = $_GET['almacen'];

  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));


?>


  <style>
    form,
    td {
      font-size: 12px;
    }
  </style>
  <center>
    <h1>Reporte de Aj</h1>
  </center>
  <table class="table table-dark table-striped" id="tblData">
    <thead>
      <tr>
        <th scope="col">Tienda</th>

        <?php

        for ($i = 0; $i < count($sedes_ar); $i++) {

          if ($sedes_ar[$i] != null) {

            $sede = $sedes_ar[$i];


            if ($sedes_ar[$i] != 'Previa Shop') {
              echo "<th scope='col'> $sede </th>";
            }
          }
        } ?>

      </tr>
    </thead>
    <tbody>


        <tr>
          <th scope='row'><?= $n ?></th>

          <?php
          $g = 1;
          $total_vendido = 0;
          for ($i = 0; $i < count($sedes_ar); $i++) {


    

            if ($sedes_ar[$g] != null) {

              $res0 = getFactura($sedes_ar[$g], $co_art, $fecha1, $fecha2 , $linea);
              $total_enviado = round($res0['total_art']);
              $total_enviado_tienda[$sedes_ar[$g]] += $total_enviado;

            }
            $g++;
          }


          ?>
          <th scope='row'>$total_enviado</th>


        
      </tr>

    </tbody>
  </table>
  <script src="../../assets/js/excel.js"></script>
  <center>
    <button id="submitExport" class="btn btn-success">Exportar Reporte a EXCEL</button>
  </center>




<?php
} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>