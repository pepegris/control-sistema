<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

if ($_GET) {

  $divisa = "dl";
  $fecha_titulo = date("d/m/Y", strtotime($_GET['fecha1']));
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));

  $fecha = date("Ymd", strtotime($_GET['fecha1']));

  $Day = date("d", strtotime($fecha));
  $Month = date("m", strtotime($fecha));
  $Year = date("Y", strtotime($fecha));


  /* $fecha_2 =  $fecha; */

  $fecha_2 = $Year . '/' . $Month . '/' . $Day;
  echo $fecha_2;




?>

  <style>
    img {


      width: 28px;
    }
  </style>


  <link rel='stylesheet' href='responm.css'>




  <center>
    <h1>Ventas Detalladas<?= $fecha_titulo ?></h1>
  </center>

  <table class="table table-dark table-striped" id="tblData">
    <thead>



      <tr>
        <th scope="col">Cod</th>
        <th scope='col'>Empresa</th>

        <th scope='col'>Tasa</th>

        <th scope='col'>Ventas Bs</th>
        <th scope='col'>Ventas USD</th>

        <th scope='col'>Divisas Bs</th>
        <th scope='col'>Divisas USD</th>

        <th scope='col'>% Cobro en USD</th>

        <th scope='col'>Pares</th>

        <th scope='col'>Gastos Bs</th>
        <th scope='col'>Gastos USD</th>

        <th scope='col'>Devol</th>
        <th scope='col'>Pares Dev</th>

        <th scope='col'>Depositos</th>
        <th scope='col'>Efectivo</th>
        <th scope='col'>Tarjeta</th>

        <th scope='col'>Cuadre Caja</th>

        <th scope='col'>Cierre Caja</th>
      </tr>

    </thead>
    <tbody>

      <?php

      for ($i = 1; $i < count($sedes_ar); $i++) {


        $tasas = getTasas($sedes_ar[$i],  $fecha_2);

        if ($tasas != null) {
          $tasa_v_tasas = $tasas['tasa_v'];
        } else {
          $tasa_v_tasas;
        }

        $tasa_dia = number_format($tasa_v_tasas, 2, ',', '.');

        $tasa_v_bs  = 1;

        $cod = Cliente($sedes_ar[$i]);

        /* CONSULTAS */

        $factura = getFactura($sedes_ar[$i], $fecha1, $fecha2, 'sin');
        $dev_cli = getDev_cli($sedes_ar[$i], $fecha1, $fecha2, 'sin');
        $factura_ven = getFactura($sedes_ar[$i], $fecha1, $fecha2, 'ven');
        $dev_cli_ven = getDev_cli($sedes_ar[$i], $fecha1, $fecha2, 'ven');
        $dep_caj = getDep_caj($sedes_ar[$i], $fecha1, $fecha2, 'sin');
        $mov_ban = getMov_ban($sedes_ar[$i], $fecha1, $fecha2, 'sin');
        $ord_pago = getOrd_pago($sedes_ar[$i], $fecha1, $fecha2, 'sin');
        $ord_pago_ven = getOrd_pago($sedes_ar[$i], $fecha1, $fecha2, 'ven');

        /* ART */

        $total_art_factura =  number_format($factura_ven['total_art'], 0, ',', '.');

        $total_art_dev_cli = number_format($dev_cli_ven['total_art'], 0, ',', '.');

        /* DOLARES *//* DOLARES *//* DOLARES */

        $tasa_tot_neto_factura_usd = $factura['tot_neto'] / $tasa_v_tasas;
        $tasa_tot_neto_dev_cli_usd = $dev_cli['tot_neto'] / $tasa_v_tasas;

        $venta_usd = $tasa_tot_neto_factura_usd - $tasa_tot_neto_dev_cli_usd;

        $tot_neto_factura_usd = number_format($venta_usd, 2, ',', '.');
        $tot_neto_dev_cli_usd = number_format($tasa_tot_neto_dev_cli_usd, 2, ',', '.');


        $tasa_total_efec_dep_caj_usd = $dep_caj['total_efec'] / $tasa_v_tasas;
        $tasa_total_tarj_dep_caj_usd = $dep_caj['total_tarj'] / $tasa_v_tasas;
        $total_efec_dep_caj_usd = number_format($tasa_total_efec_dep_caj_usd, 2, ',', '.');
        $total_tarj_dep_caj_usd = number_format($tasa_total_tarj_dep_caj_usd, 2, ',', '.');

        $tasa_monto_h_mov_ban_usd = $mov_ban['monto_h'] / $tasa_v_tasas;
        $monto_h_mov_ban_usd = number_format($tasa_monto_h_mov_ban_usd, 2, ',', '.');

        $tasa_monto_ord_pago_usd = $ord_pago['monto'] / $tasa_v_tasas;
        $monto_ord_pago_usd = number_format($tasa_monto_ord_pago_usd, 2, ',', '.');


        $tasa_monto_ord_pago_ven_usd = $ord_pago_ven['monto'] / $tasa_v_tasas;
        $monto_ord_pago_ven_usd = number_format($tasa_monto_ord_pago_ven_usd, 2, ',', '.');

        /* BOLIVARES *//* BOLIVARES *//* BOLIVARES */

        $tasa_tot_neto_factura_bs = $factura['tot_neto'] / $tasa_v_bs;
        $tasa_tot_neto_dev_cli_bs = $dev_cli['tot_neto'] / $tasa_v_bs;

        $venta_bs = $tasa_tot_neto_factura_bs - $tasa_tot_neto_dev_cli_bs;

        $tot_neto_dev_cli_bs = number_format($tasa_tot_neto_dev_cli_bs, 2, ',', '.');
        $tot_neto_factura_bs = number_format($venta_bs, 2, ',', '.');

        $tasa_total_efec_dep_caj_bs = $dep_caj['total_efec'] / $tasa_v_bs;
        $tasa_total_tarj_dep_caj_bs = $dep_caj['total_tarj'] / $tasa_v_bs;
        $total_efec_dep_caj_bs = number_format($tasa_total_efec_dep_caj_bs, 2, ',', '.');
        $total_tarj_dep_caj_bs = number_format($tasa_total_tarj_dep_caj_bs, 2, ',', '.');

        $tasa_monto_h_mov_ban_bs = $mov_ban['monto_h'] / $tasa_v_bs;
        $monto_h_mov_ban_bs = number_format($tasa_monto_h_mov_ban_bs, 2, ',', '.');

        $tasa_monto_ord_pago_bs = $ord_pago['monto'] / $tasa_v_bs;
        $monto_ord_pago_bs = number_format($tasa_monto_ord_pago_bs, 2, ',', '.');

        $tasa_monto_ord_pago_ven_bs = $ord_pago_ven['monto'] / $tasa_v_bs;
        $monto_ord_pago_ven_bs = number_format($tasa_monto_ord_pago_ven_bs, 2, ',', '.');

        /* totales *//* totales *//* totales */

        $total_venta_pares += $factura_ven['total_art'];

        $total_devol_pares += $dev_cli_ven['total_art'];

        /* totales *//* BOLIVARES *//* totales */

        $total_venta_usd += $tasa_tot_neto_factura_usd - $tasa_tot_neto_dev_cli_usd;
        $total_devol_usd += $tasa_tot_neto_dev_cli_usd;

        $total_depositos_usd += $tasa_monto_h_mov_ban_usd;

        $total_efectivo_usd += $tasa_total_efec_dep_caj_usd;
        $total_tarjeta_usd += $tasa_total_tarj_dep_caj_usd;

        $total_pagos_usd += $tasa_monto_ord_pago_usd;
        $total_gastos_usd += $tasa_monto_ord_pago_ven_usd;


        /* totales *//* DOLARES *//* totales */

        $total_venta_bs += $tasa_tot_neto_factura_bs - $tasa_tot_neto_dev_cli_bs;
        $total_devol_bs += $tasa_tot_neto_dev_cli_bs;

        $total_depositos_bs += $tasa_monto_h_mov_ban_bs;

        $total_efectivo_bs += $tasa_total_efec_dep_caj_bs;
        $total_tarjeta_bs += $tasa_total_tarj_dep_caj_bs;

        $total_pagos_bs += $tasa_monto_ord_pago_bs;
        $total_gastos_bs += $tasa_monto_ord_pago_ven_bs;

        /* porcentaje de dolares */

        $porcentaje_usd = ($monto_ord_pago_bs / $tot_neto_factura_bs  ) * 100;
        



      ?>
        <tr>

          <td><?= $cod   ?></td>
          <td><?= $sedes_ar[$i]  ?></td>

          <td><?= $tasa_dia ?></td>

          <td><?= $tot_neto_factura_bs    ?></td>
          <td><?= $tot_neto_factura_usd    ?></td>

          <td><?= $monto_ord_pago_bs  ?></td>
          <td><?= $monto_ord_pago_usd  ?></td>

          <td>%<?= number_format($porcentaje_usd, 0, ',', '.');   ?></td>

          <td><?= $total_art_factura - $total_art_dev_cli   ?></td>

          <td><?= $monto_ord_pago_ven_bs  ?></td>
          <td><?= $monto_ord_pago_ven_usd  ?></td>

          <td><?= $tot_neto_dev_cli_bs   ?></td>
          <td><?= $total_art_dev_cli  ?></td>

          <td><?= $monto_h_mov_ban_bs ?></td>


          <td><?= $total_efec_dep_caj_bs  ?></td>
          <td><?= $total_tarj_dep_caj_bs  ?></td>




          <td> <?php

                if ($venta_usd <= 1 & $total_art_factura_usd == 0) {

                  echo " <img src='./img/help.svg' alt=''> ";
                } elseif ($tot_neto_factura_usd > 1 & $monto_h_mov_ban_usd > 1) {

                  $diferencias = number_format($tasa_monto_ord_pago_usd + $tasa_monto_ord_pago_ven_usd  + $tasa_monto_h_mov_ban_usd  - $venta_usd, 2, ',', '.');
                  echo "$diferencias";
                } elseif ($total_tarj_dep_caj_usd  > 1) {

                  $diferencias = number_format($tasa_total_efec_dep_caj_usd  + $tasa_total_tarj_dep_caj_usd  + $tasa_monto_ord_pago_usd  + $tasa_monto_ord_pago_ven_usd  - $venta_usd, 2, ',', '.');

                  if ($diferencias > 1) {

                    echo "<img src='./img/help.svg' alt=''> ";
                  } else {

                    echo "$diferencias";
                  }
                } else {
                  echo "<img src='./img/help.svg' alt=''> ";
                }

                ?></td>

        <?php

        $total_diferencias += $diferencias;

        $caja = number_format($tasa_monto_ord_pago_usd  + $tasa_monto_ord_pago_ven_usd  + $tasa_monto_h_mov_ban_usd  - $venta_usd, 2, ',', '.');

        if ($venta_usd  <= 1 & $total_art_factura_usd  == 0) {

          echo "<td> <img src='./img/help.svg' alt=''> </td>";
        } elseif ($caja == 0) {

          echo "<td> <img src='./img/checkmark-circle.svg' alt=''> </td>";
        } elseif ($total_tarj_dep_caj_usd  > 1) {

          $caja2 = number_format($tasa_total_efec_dep_caj_usd  + $tasa_total_tarj_dep_caj_usd  + $tasa_monto_ord_pago_usd  + $tasa_monto_ord_pago_ven_usd  - $venta_usd, 2, ',', '.');

          if ($caja2 > 1) {

            echo "<td><img src='./img/help.svg' alt=''> </td>";
          } else {

            echo "<td> <img src='./img/checkmark-circle.svg' alt=''> </td>";
          }
        } elseif ($monto_h_mov_ban_usd  < 1) {

          echo "<td> <img src='./img/help.svg' alt=''> </td>";
        } else {
          echo "<td> <img src='./img/cross-circle.svg' alt=''> </td>";
        }

        echo "</tr>";
      }

      $total_porcentaje_usd = $total_pagos_bs  /   $total_venta_bs* 100;
        ?>



        <tr>
          <td colspan="3">
            <h3>Totales</h3>
          </td>



          <td><b>Bs<?= number_format($total_venta_bs, 2, ',', '.')  ?></b></td>
          <td><b>$<?= number_format($total_venta_usd, 2, ',', '.')  ?></b></td>

          <td><b>Bs<?= number_format($total_pagos_bs, 2, ',', '.')  ?></b></td>
          <td><b>$<?= number_format($total_pagos_usd, 2, ',', '.')  ?></b></td>

          <td>%<?= number_format($total_porcentaje_usd, 0, ',', '.');  ?></td>

          <td><b><?= number_format($total_venta_pares, 0, '', '.')  ?></b></td>

          <td><b>Bs<?= number_format($total_gastos_bs, 2, ',', '.')  ?></b></td>
          <td><b>$<?= number_format($total_gastos_usd, 2, ',', '.')  ?></b></td>

          <td><b>Bs<?= $simb ?><?= number_format($total_devol_bs, 2, ',', '.')  ?></b></td>
          <td><b><?= number_format($total_devol_pares, 0, '', '.')  ?></b></td>

          <td><b>Bs<?= $simb ?><?= number_format($total_depositos_bs, 2, ',', '.')  ?></b></td>

          <td><b>Bs<?= $simb ?><?= number_format($total_efectivo_bs, 2, ',', '.')  ?></b></td>
          <td><b>Bs<?= $simb ?><?= number_format($total_tarjeta_bs, 2, ',', '.')  ?></b></td>

          
          
          <td><b>$<?= $simb ?><?= number_format($total_diferencias, 2, ',', '.')  ?></b></td>


          <td></td>

        </tr>

    </tbody>


  </table>


<?php
} else {
  header("location: form.php");
}



include '../../includes/footer.php'; ?>