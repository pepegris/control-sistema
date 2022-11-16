<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

if ($_GET) {

  $divisa = $_GET['divisa'];
  $fecha_titulo1 = date("d/m/Y", strtotime($_GET['fecha1']));
  $fecha_titulo2 = date("d/m/Y", strtotime($_GET['fecha2']));
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));




?>



  <link rel='stylesheet' href='responm.css'>




  <center>
    <h1>Ventas Diarias <?= $fecha_titulo1 ?> - <?= $fecha_titulo2  ?></h1>
  </center>

  <?php

  if ($divisa == 'dl') {

    echo "<h4>En Dolares</h4>";
  } else {
    echo "<h4>En Bolivares</h4>";
  }

  ?>



  <table class="table table-dark table-striped" id="tblData">
    <thead>



      <tr>
        <th scope="col">Cod</th>
        <th scope='col'>Empresa</th>

        <th scope='col'>Ventas</th>
        <th scope='col'>Pares</th>

        <th scope='col'>Devol</th>
        <th scope='col'>Pares Dev</th>

        <th scope='col'>Depositos</th>
        <th scope='col'>Efectivo</th>
        <th scope='col'>Tarjeta</th>

        <th scope='col'>Divisas</th>
		<th scope='col'>Gastos</th>

        <th scope='col'>Cierre Caja</th>
      </tr>

    </thead>
    <tbody>

      <?php

      for ($i = 1; $i < count($sedes_ar); $i++) {

        if ($divisa == 'dl') {
          $tasas = getTasas($sedes_ar[$i], $fecha1);

          if ($tasas != null) {
            $tasa_v_tasas = $tasas['tasa_v'];
          } else {
            $tasa_v_tasas;
          }
        } else {
          $tasa_v_tasas  = 1;
        }

        $cod = Cliente($sedes_ar[$i]);

        $factura = getFactura($sedes_ar[$i], $fecha1, $fecha2, '');
        $tasa_tot_neto_factura = $factura['tot_neto'] / $tasa_v_tasas;

        $factura_ven = getFactura($sedes_ar[$i], $fecha1, $fecha2, 'ven2');
        $total_art_factura =  number_format($factura_ven['total_art'], 0, ',', '.');

        $dev_cli = getDev_cli($sedes_ar[$i], $fecha1, $fecha2, '');
        $tasa_tot_neto_dev_cli = $dev_cli['tot_neto'] / $tasa_v_tasas;
        $tot_neto_dev_cli = number_format($tasa_tot_neto_dev_cli, 2, ',', '.');


        $venta += $tasa_tot_neto_factura - $tasa_tot_neto_dev_cli;
        $tot_neto_factura = number_format($venta, 2, ',', '.');


        $dev_cli_ven = getDev_cli($sedes_ar[$i], $fecha1, $fecha2, 'ven2');
        $total_art_dev_cli = number_format($dev_cli_ven['total_art'], 0, ',', '.');

        $dep_caj = getDep_caj($sedes_ar[$i], $fecha1, $fecha2, '');
        $tasa_total_efec_dep_caj = $dep_caj['total_efec'] / $tasa_v_tasas;
        $tasa_total_tarj_dep_caj = $dep_caj['total_tarj'] / $tasa_v_tasas;
        $total_efec_dep_caj = number_format($tasa_total_efec_dep_caj, 2, ',', '.');
        $total_tarj_dep_caj = number_format($tasa_total_tarj_dep_caj, 2, ',', '.');

        $mov_ban = getMov_ban($sedes_ar[$i], $fecha1, $fecha2, '');
        $tasa_monto_h_mov_ban = $mov_ban['monto_h'] / $tasa_v_tasas;
        $monto_h_mov_ban = number_format($tasa_monto_h_mov_ban, 2, ',', '.');

        $ord_pago = getOrd_pago($sedes_ar[$i], $fecha1, $fecha2, '');
        $tasa_monto_ord_pago = $ord_pago['monto'] / $tasa_v_tasas;
        $monto_ord_pago = number_format($tasa_monto_ord_pago, 2, ',', '.');

        $ord_pago_ven = getOrd_pago($sedes_ar[$i], $fecha1, $fecha2, 'ven2');
        $tasa_monto_ord_pago_ven = $ord_pago_ven['monto'] / $tasa_v_tasas;
        $monto_ord_pago_ven = number_format($tasa_monto_ord_pago_ven, 2, ',', '.');


        /* totales */



        $total_venta += $tasa_tot_neto_factura - $tasa_tot_neto_dev_cli;
        $total_venta_pares += $total_art_factura - $total_art_dev_cli;

        $total_devol += $tasa_tot_neto_dev_cli;
        $total_devol_pares += $total_art_dev_cli;

        $total_depositos += $tasa_monto_h_mov_ban;

        $total_efectivo += $tasa_total_efec_dep_caj;
        $total_tarjeta += $tasa_total_tarj_dep_caj;

        $total_pagos += $tasa_monto_ord_pago;
        $total_gastos += $tasa_monto_ord_pago_ven;

      ?>
        <tr>

          <td><?= $cod   ?></td>
          <td><?= $sedes_ar[$i]  ?></td>


          <td><?= $tot_neto_factura    ?></td>
          <td><?= $total_art_factura - $total_art_dev_cli   ?></td>

          <td><?= $tot_neto_dev_cli   ?></td>
          <td><?= $total_art_dev_cli  ?></td>

          <td><?= $monto_h_mov_ban ?></td>


          <td><?= $total_efec_dep_caj  ?></td>
          <td><?= $total_tarj_dep_caj  ?></td>

          <td><?= $monto_ord_pago  ?></td>
          <td><?= $monto_ord_pago_ven  ?></td>

        <?php

        if ($monto_h_mov_ban == $tot_neto_factura) {
          echo "<td><i class='lni lni-checkmark-circle'></i></td>";
        } else {
          echo "<td><i class='lni lni-cross-circle'></i></td>";
        }

        echo "</tr>";

      }

      if ($divisa == 'dl') {

        $simb='$';


      }else {

        $simb='Bs';


      }

        ?>



        <tr>
          <td colspan="2">
            <h3>Totales</h3>
          </td>



          <td><b><?= $simb ?><?= number_format($total_venta, 2, ',', '.')  ?></b></td>
          <td><b><?= $total_venta_pares ?></b></td>

          <td><b><?= $simb ?><?= number_format($total_devol, 2, ',', '.')  ?></b></td>
          <td><b><?= $total_devol_pares  ?></b></td>

          <td><b><?= $simb ?><?= number_format($total_depositos, 2, ',', '.')  ?></b></td>

          <td><b><?= $simb ?><?= number_format($total_efectivo, 2, ',', '.')  ?></b></td>
          <td><b><?= $simb ?><?= number_format($total_tarjeta, 2, ',', '.')  ?></b></td>

          <td><b><?= $simb ?><?= number_format($total_pagos, 2, ',', '.')  ?></b></td>
          <td><b><?= $simb ?><?= number_format($total_gastos, 2, ',', '.')  ?></b></td>
		  
          <td></td>

        </tr>

    </tbody>


  </table>


<?php
} else {
  header("location: form.php");
}



include '../../includes/footer.php'; ?>