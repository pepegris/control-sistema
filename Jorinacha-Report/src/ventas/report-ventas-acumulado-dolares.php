<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

if ($_GET) {

  $divisa = $_GET['divisa'];
  $fecha_titulo1 = date("d/m/Y", strtotime($_GET['fecha1']));
  $fecha_titulo2 = date("d/m/Y", strtotime($_GET['fecha2']));
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));


  $Day = date("d", strtotime($fecha2));
  $Month = date("m", strtotime($fecha2));
  $Year = date("Y", strtotime($fecha2));




?>

  <style>
    img {


      width: 28px;
    }
  </style>




  <link rel='stylesheet' href='responm.css'>




  <center>
    <h1>Ventas Acumulado <?= $fecha_titulo1 ?> - <?= $fecha_titulo2  ?></h1>
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

        /* calcular si se solicito dolares y ver q tasa tenia ese dia */

        $tasa_tot_neto_factura = 0;
        $tasa_tot_neto_dev_cli = 0;

        $venta = 0;

        $tasa_total_efec_dep_caj = 0;
        $tasa_total_tarj_dep_caj = 0;

        $tasa_monto_h_mov_ban = 0;

        $tasa_monto_ord_pago = 0;

        $tasa_monto_ord_pago_ven = 0;

        $e = 1;

        $sede = $sedes_ar[$i];
        $cod = Cliente($sede);


        for ($r = 1; $r <= $Day; $r++) {

          if ($e  < 10) {

            $d = 0 . $e;
          } else {
            $d = $e;
          }
           echo $d;

           require "../../services/empresas-fechas.php";
          


          $fecha =  $Year .'/'. $Month .'/'  . $d;
          $tasas = getTasas($sede, $fecha);


          if ($tasas != null) {
            $tasa_v_tasas = $tasas['tasa_v'];
          } else {
            $tasa_v_tasas;
          }



          $factura = getFactura($sede, $fecha, $fecha2, 'sin');
          $tasa_tot_neto_factura += $factura['tot_neto'] / $tasa_v_tasas;

          $dev_cli = getDev_cli($sede, $fecha, $fecha2, 'sin','todos');
          $tasa_tot_neto_dev_cli += $dev_cli['tot_neto'] / $tasa_v_tasas;



          $venta += $tasa_tot_neto_factura - $tasa_tot_neto_dev_cli;



          $dep_caj = getDep_caj($sede, $fecha, $fecha2, 'sin');
          $tasa_total_efec_dep_caj += $dep_caj['total_efec'] / $tasa_v_tasas;
          $tasa_total_tarj_dep_caj += $dep_caj['total_tarj'] / $tasa_v_tasas;


          $mov_ban = getMov_ban($sede, $fecha, $fecha2, 'sin');
          $tasa_monto_h_mov_ban += $mov_ban['monto_h'] / $tasa_v_tasas;


          $ord_pago = getOrd_pago($sede, $fecha, $fecha2, 'sin');
          $tasa_monto_ord_pago += $ord_pago['monto'] / $tasa_v_tasas;


          $ord_pago_ven = getOrd_pago($sede, $fecha, $fecha2, 'ven');
          $tasa_monto_ord_pago_ven += $ord_pago_ven['monto'] / $tasa_v_tasas;






          $e++;
        }


        


        $tot_neto_dev_cli = number_format($tasa_tot_neto_dev_cli, 2, ',', '.');
        $tot_neto_factura = number_format($venta, 2, ',', '.');

        $total_efec_dep_caj = number_format($tasa_total_efec_dep_caj, 2, ',', '.');
        $total_tarj_dep_caj = number_format($tasa_total_tarj_dep_caj, 2, ',', '.');
        $monto_h_mov_ban = number_format($tasa_monto_h_mov_ban, 2, ',', '.');
        $monto_ord_pago = number_format($tasa_monto_ord_pago, 2, ',', '.');
        $monto_ord_pago_ven = number_format($tasa_monto_ord_pago_ven, 2, ',', '.');


        $dev_cli_ven = getDev_cli($sede, $fecha1, $fecha2, 'ven2','todos');
        $total_art_dev_cli = number_format($dev_cli_ven['total_art'], 0, ',', '.');

        $factura_ven = getFactura($sede, $fecha1, $fecha2, 'ven2');

        $venta_art = $factura_ven['total_art'] - $dev_cli_ven['total_art'];
        $total_art_factura  = number_format($venta_art, 0, ',', '.');

        /* totales */



        $total_venta += $tasa_tot_neto_factura - $tasa_tot_neto_dev_cli;
        $total_venta_pares += $venta_art;

        $total_devol += $tasa_tot_neto_dev_cli;

        $total_devol_pares += $dev_cli_ven['total_art'];


        $total_depositos += $tasa_monto_h_mov_ban;

        $total_efectivo += $tasa_total_efec_dep_caj;
        $total_tarjeta += $tasa_total_tarj_dep_caj;

        $total_pagos += $tasa_monto_ord_pago;
        $total_gastos += $tasa_monto_ord_pago_ven;

      ?>
        <tr>

          <td><?= $cod   ?></td>
          <td><?= $sede  ?></td>


          <td><?= $tot_neto_factura    ?></td>
          <td><?= $total_art_factura   ?></td>

          <td><?= $tot_neto_dev_cli   ?></td>
          <td><?= $total_art_dev_cli  ?></td>

          <td><?= $monto_h_mov_ban ?></td>


          <td><?= $total_efec_dep_caj  ?></td>
          <td><?= $total_tarj_dep_caj  ?></td>

          <td><?= $monto_ord_pago  ?></td>
          <td><?= $monto_ord_pago_ven  ?></td>

        <?php

        $total_diferencias += $diferencias;

        $caja = number_format($tasa_monto_ord_pago + $tasa_monto_ord_pago_ven + $tasa_monto_h_mov_ban - $venta, 2, ',', '.');

        if ($venta <= 1 & $total_art_factura == 0) {

          echo "<td> <img src='./img/help.svg' alt=''> </td>";
        } elseif ($caja == 0) {

          echo "<td> <img src='./img/checkmark-circle.svg' alt=''> </td>";
        } elseif ($total_tarj_dep_caj > 1) {

          $caja2 = number_format($tasa_total_efec_dep_caj + $tasa_total_tarj_dep_caj + $tasa_monto_ord_pago + $tasa_monto_ord_pago_ven - $venta, 2, ',', '.');

          if ($caja2 > 1) {

            echo "<td><img src='./img/help.svg' alt=''> </td>";
          } else {

            echo "<td> <img src='./img/checkmark-circle.svg' alt=''> </td>";
          }
        } elseif ($monto_h_mov_ban < 1) {

          echo "<td> <img src='./img/help.svg' alt=''> </td>";
        } else {
          echo "<td> <img src='./img/cross-circle.svg' alt=''> </td>";
        }

        echo "</tr>";
      }

      if ($divisa == 'dl') {

        $simb = '$';
      } else {

        $simb = 'Bs';
      }

        ?>



        <tr>
          <td colspan="2">
            <h3>Totales</h3>
          </td>



          <td><b><?= $simb ?><?= number_format($total_venta, 2, ',', '.')  ?></b></td>
          <td><b><?= number_format($total_venta_pares, 0, '', '.')  ?></b></td>


          <td><b><?= $simb ?><?= number_format($total_devol, 2, ',', '.')  ?></b></td>
          <td><b><?= number_format($total_devol_pares, 0, '', '.')  ?></b></td>

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