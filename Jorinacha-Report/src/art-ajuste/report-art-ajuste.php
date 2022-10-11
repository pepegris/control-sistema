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



  <center>
    <h1>Reporte de Aj</h1>
  </center>
  <table class="table table-dark table-striped" id="tblData">
    <thead>
      <tr>
        <th scope="col">TIENDAS</th>

        <?php

        for ($i = 0; $i < count($sedes_ar); $i++) {

          if ($sedes_ar[$i] != null) {

            $sede = $sedes_ar[$i];


            if ($sedes_ar[$i] != 'Previa Shop') {
              echo "<th scope='col'> $sede </th>";
            }
          }
        } ?>
        <th scope="col">TOTALES</th>

      </tr>
    </thead>
    <tbody>



    <tr>
        <th scope='row'>FACTURADO DESDE TIENDA</th>

        <?php
        $g = 1;
        for ($i = 0; $i < count($sedes_ar); $i++) {




          if ($sedes_ar[$g] != null) {

            $res = getFacturaCompras($sedes_ar[$g], $fecha1, $fecha2, $linea);

            if ($res == null) {
              $res = 0;
            }


            $total_desde_tienda[$sedes_ar[$g]] += $res;
            $total += $res


        ?>

            <td><?= $res ?></td>

        <?php }
          $g++;
        }   ?>

        <th><?= $total ?></th>

      </tr>


      <tr>
        <th scope='row'>FACTURADO DESDE PREVIA</th>

        <?php
        $g = 1;
        for ($i = 0; $i < count($sedes_ar); $i++) {




          if ($sedes_ar[$g] != null) {

            $res0 = getFactura($sedes_ar[$g], null, $fecha1, $fecha2, $linea);

            if ($res0 == null) {
              $res0 = 0;
            }


            $total_enviado_tienda[$sedes_ar[$g]] += $res0;
            $total0 += $res0


        ?>

            <td><?= $res0 ?></td>

        <?php }
          $g++;
        }   ?>

        <th><?= $total0 ?></th>

      </tr>

      <tr>
        <th scope='row'>AJUSTES ENTRADAS X SOBRANTES</th>

        <?php
        $g = 1;
        for ($i = 0; $i < count($sedes_ar); $i++) {




          if ($sedes_ar[$g] != null) {

            $res1 = getAjustes($sedes_ar[$g], $fecha1, $fecha2, $linea, 'EN');

            if ($res1 == null) {
              $res1 = 0;
            }

            $total_entradas_sobrantes[$sedes_ar[$g]] += $res1;
            $total1 += $res1

        ?>

            <td><?= $res1 ?></td>

        <?php }
          $g++;
        }   ?>

        <th><?= $total1 ?></th>
      </tr>


      <tr>
        <th scope='row'>AJUSTES SALIDAS X FALTANTES</th>

        <?php
        $g = 1;

        for ($i = 0; $i < count($sedes_ar); $i++) {




          if ($sedes_ar[$g] != null) {

            $res2 = getAjustes($sedes_ar[$g], $fecha1, $fecha2, $linea, 'SAL');

            if ($res2 == null) {
              $res2 = 0;
            }

            $total_salidas_faltantes[$sedes_ar[$g]] += $res2;
            $total2 += $res2


        ?>

            <td>-<?= $res2 ?></td>

        <?php }
          $g++;
        }   ?>

        <th>-<?= $total2 ?></th>
      </tr>





      <th scope='row'>DEVOLUCION AL PROVEEDOR PREVIA</th>

<?php
$g = 1;

for ($i = 0; $i < count($sedes_ar); $i++) {




  if ($sedes_ar[$g] != null) {

    $res3 = getDevProveedor($sedes_ar[$g], $fecha1, $fecha2, $linea, 'SAL');

    if ($res3 == null) {
      $res3 = 0;
    }

    $total_DevProveedor[$sedes_ar[$g]] += $res3;
    $total3 += $res3


?>

    <td>-<?= $res3 ?></td>

<?php }
  $g++;
}   ?>

<th>-<?= $total3 ?></th>
</tr>

      <tr>
        <th scope='row'>TOTALES</th>

        <?php
        $g = 1;
        $total = 0;
        for ($i = 0; $i < count($sedes_ar); $i++) {


          if ($sedes_ar[$g] != null) {

            $total = $total_enviado_tienda[$sedes_ar[$g]]  + $total_entradas_sobrantes[$sedes_ar[$g]] - $total_salidas_faltantes[$sedes_ar[$g]] - $total_DevProveedor[$sedes_ar[$g]];
            $totales += $total;

        ?>

            <th><?= $total ?></th>

        <?php }
          $total = 0;
          $g++;
        }   ?>
        <th><?= $totales ?></th>
      </tr>

    </tbody>
  </table>





<?php
} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>