<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

if ($_GET) {

  $divisa = $_GET['divisa'];
  $fecha_titulo = date("d/m/Y", strtotime($_GET['fecha1']));
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));




?>



  <link rel='stylesheet' href='responm.css'>




  <center>
    <h1>Ventas Diarias <?= $fecha_titulo ?></h1>
  </center>

  <?php

  if ($divisa == 'dl') {

    echo "<h4>En Dls</h4>";
  } else {
    echo "<h4>En Bs</h4>";
  }

  ?>



  <table class="table table-dark table-striped" id="tblData">
    <thead>



      <tr>
        <th scope="col">Cod</th>
        <th scope='col'>Empresa</th>

        <?php

        if ($divisa == 'dl') {

          echo "<th scope='col'>Tasa</th>";
        }

        ?>

        <th scope='col'>Ventas</th>
        <th scope='col'>Pares</th>

        <th scope='col'>Devol</th>
        <th scope='col'>Pares Dev</th>

        <th scope='col'>Depositos</th>
        <th scope='col'>Efectivo</th>
        <th scope='col'>Tarjeta</th>

        <th scope='col'>Pagos</th>

        <th scope='col'>Estatus Caja</th>
      </tr>

    </thead>
    <tbody>

      <?php

      for ($i = 1; $i < count($sedes_ar); $i++) {

        if ($divisa == 'dl') {
          $tasas = getTasas($sedes_ar[$i], $fecha1, 'sin');

          if ($tasas != null) {
            $tasa_v_tasas = $tasas['tasa_v'];
          } else {
            $tasa_v_tasas;
          }
        } else {
          $tasa_v_tasas  = 1;
        }

        $cod = Cliente($sedes_ar[$i]);

        $factura = getFactura($sedes_ar[$i], $fecha1, 'sin');
        $tasa_tot_neto_factura = $factura['tot_neto'] / $tasa_v_tasas;
        $tot_neto_factura = number_format($tasa_tot_neto_factura, 2, ',', '.');

        $factura_ven = getFactura($sedes_ar[$i], $fecha1, 'ven');
        $total_art_factura =  number_format($factura_ven['total_art'], 0, ',', '.');

        $dev_cli = getDev_cli($sedes_ar[$i], $fecha1, 'sin');
        $tasa_tot_neto_dev_cli = $dev_cli['tot_neto'] / $tasa_v_tasas;
        $total_art_dev_cli = number_format($dev_cli['total_art'], 0, ',', '.');
        $tot_neto_dev_cli = number_format($tasa_tot_neto_dev_cli, 2, ',', '.');

        $dep_caj = getDep_caj($sedes_ar[$i], $fecha1, 'sin');
        $tasa_total_efec_dep_caj = $dep_caj['total_efec'] / $tasa_v_tasas;
        $tasa_total_tarj_dep_caj = $dep_caj['total_tarj'] / $tasa_v_tasas;
        $total_efec_dep_caj = number_format($tasa_total_efec_dep_caj, 2, ',', '.');
        $total_tarj_dep_caj = number_format($tasa_total_tarj_dep_caj, 2, ',', '.');

        $mov_ban = getMov_ban($sedes_ar[$i], $fecha1, 'sin');
        $tasa_monto_h_mov_ban = $mov_ban['monto_h'] / $tasa_v_tasas;
        $monto_h_mov_ban = number_format($tasa_monto_h_mov_ban, 2, ',', '.');

        $ord_pago = getOrd_pago($sedes_ar[$i], $fecha1, 'sin');
        $tasa_monto_ord_pago = $ord_pago['monto'] / $tasa_v_tasas;
        $monto_ord_pago = number_format($tasa_monto_ord_pago, 2, ',', '.');


        /* totales */

        $total_venta[$sedes_ar[$i]] += $tot_neto_factura;
        $total_venta_pares[$sedes_ar[$i]] += $total_art_factura;

        $total_devol[$sedes_ar[$i]] += $tot_neto_dev_cli;
        $total_devol_pares[$sedes_ar[$i]] += $total_art_dev_cli;

        $total_depositos[$sedes_ar[$i]] += $monto_h_mov_ban;

        $total_efectivo[$sedes_ar[$i]] += $total_efec_dep_caj;
        $total_tarjeta[$sedes_ar[$i]] += $total_tarj_dep_caj;

        $total_pagos[$sedes_ar[$i]] += $monto_ord_pago;

      ?>
        <tr>

          <td><?= $cod   ?></td>
          <td><?= $sedes_ar[$i]  ?></td>

          <?php

          if ($divisa == 'dl') {

            $tasa_dia = number_format($tasa_v_tasas, 2, ',', '.');

            echo "<td>$tasa_dia</td>";
          }

          ?>


          <td><?= $tot_neto_factura   ?></td>
          <td><?= $total_art_factura - $total_art_dev_cli   ?></td>

          <td><?= $tot_neto_dev_cli   ?></td>
          <td><?= $total_art_dev_cli  ?></td>

          <td><?= $monto_h_mov_ban ?></td>


          <td><?= $total_efec_dep_caj  ?></td>
          <td><?= $total_tarj_dep_caj  ?></td>

          <td><?= $monto_ord_pago  ?></td>

        <?php

        if ($monto_h_mov_ban == $tot_neto_factura) {
          echo "<td><i class='lni lni-checkmark-circle'></i></td>";
        } else {
          echo "<td><i class='lni lni-cross-circle'></i></td>";
        }

        echo "</tr>";
      }

        ?>



        <tr>
          <td colspan="3">
            <h3>Totales</h3>
          </td>

          <?php

          for ($e= 1; $e < count($sedes_ar); $e++) {

          ?>

          <td><b><?= $total_venta[$sedes_ar[$e]] ?></b></td>
          <td><b><?= $total_venta_pares[$sedes_ar[$e]] ?></b></td>

          <td><b><?= $total_devol[$sedes_ar[$e]] ?></b></td>
          <td><b><?= $total_devol_pares[$sedes_ar[$e]]  ?></b></td>

          <td><b><?= $total_depositos[$sedes_ar[$e]] ?></b></td>

          <td><b><?= $total_efectivo[$sedes_ar[$e]] ?></b></td>
          <td><b><?= $total_devol[$sedes_ar[$e]] ?></b></td>

          <td><b><?= $total_tarjeta[$sedes_ar[$e]] ?></b></td>
          <td></td>


          <?php
          }

          ?>

        </tr>

    </tbody>


  </table>


<?php
} else {
  header("location: form.php");
}



include '../../includes/footer.php'; ?>