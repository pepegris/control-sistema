<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

if ($_GET) {

  $divisa = $_GET['divisa'];
  $linea = $_GET['linea'];
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));


  $Day = date("d", strtotime($fecha2));
  $Month_total = date("m", strtotime($fecha2));
  $Year = date("Y", strtotime($fecha2));





?>

  <style>
    img {


      width: 28px;
    }
  </style>



  <link rel='stylesheet' href='responm.css'>


  <?php


  echo "<h4>En Dolares</h4>";

  $y = 1;
  for ($k = 1; $k <= $Month_total; $k++) {




    if ($y < 10) {

      $Month = 0 . $y;
    } else {
      $Month = $y;
    }

    $y++;

    echo "<center> <h2>Ventas del Mes $Month del $Year</h2> </center>";


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

        $cantidad_Dias = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);


        for ($i = 1; $i < count($sedes_ar); $i++) {

          $tasa_tot_neto_factura = 0;
          $tasa_tot_neto_dev_cli = 0;
          $venta = 0;
          $tasa_total_efec_dep_caj = 0;
          $tasa_total_tarj_dep_caj = 0;
          $tasa_monto_h_mov_ban = 0;
          $tasa_monto_ord_pago = 0;
          $tasa_monto_ord_pago_ven = 0;

          $sede = $sedes_ar[$i];
          $cod = Cliente($sede);

          if ($Year < 2023) {
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////   
            if ($sede == "Sucursal Caracas I") {
              $sede = 'Comercial Merina';
            }


            ////////////////////////////////////////////////////////////////////////////////////////////////////////////   
            if ($sede == "Sucursal Caracas II") {
              $sede = 'Comercial Merina3';
            }


            ////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($sede == "Sucursal Maturin") {
              $sede = 'Comercial Matur';
            }


            ////////////////////////////////////////////////////////////////////////////////////////////////////////////          
            if ($sede == "Sucursal Cagua") {
              $sede = 'Comercial Kagu';
            }


            ////////////////////////////////////////////////////////////////////////////////////////////////////////////  
            if ($sede == 'Sucursal Coro1') {
              $sede = "Comercial Trina";
            }
            if ($sede == 'Sucursal Coro2') {
              $sede = "Comercial Corina I";
            }
            if ($sede == 'Sucursal Coro3') {
              $sede = "Comercial Corina II";
            }
          } else {
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////   
            if ($sede == "Sucursal Caracas I" && $Month < 04  && $Year <= 2023) {
              $sede = 'Comercial Merina';
            }
            if ($sede == "Comercial Merina" && $Month == 04  && $Year >= 2023) {
              $sede = 'Sucursal Caracas I';
            }

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////   
            if ($sede == "Sucursal Caracas II" && $Month < 04  && $Year <= 2023) {
              $sede = 'Comercial Merina3';
            }
            if ($sede == "Comercial Merina3" && $Month == 04   && $Year >=  2023) {
              $sede = 'Sucursal Caracas II';
            }

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($sede == "Sucursal Maturin" && $Month < 10  && $Year <= 2023) {
              $sede = 'Comercial Matur';
            }


            ////////////////////////////////////////////////////////////////////////////////////////////////////////////          
            if ($sede == "Sucursal Cagua" && $Month < 06  && $Year <= 2023) {
              $sede = 'Comercial Kagu';
            }


            ////////////////////////////////////////////////////////////////////////////////////////////////////////////   
            if ($sede == 'Sucursal Coro1' && $Month <= 10 && $Year == '2024') {
              $sede = "Comercial Trina";
            } elseif ($sede == 'Comercial Trina' && $Month > 10 && $Year == '2024') {
              $sede = "Sucursal Coro3";
            }
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////    
            if ($sede == 'Sucursal Coro2' && $Month <= 10 && $Year == '2024') {
              $sede = "Comercial Corina I";
            } elseif ($sede == 'Comercial Corina I' && $Month > 10 && $Year == '2024') {
              $sede = "Sucursal Coro3";
            }
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////             
            if ($sede == 'Sucursal Coro3' && $Month <= 10 && $Year == '2024') {
              $sede = "Comercial Corina II";
            } elseif ($sede == 'Comercial Corina II' && $Month > 10 && $Year == '2024') {
              $sede = "Sucursal Coro3";
            }
          }





          $fecha =  $Year . '/' . $Month . '/'  . $cantidad_Dias;
          $tasas = getTasas($sede, $fecha);


          if ($tasas != null) {
            $tasa_v_tasas = $tasas['tasa_v'];
          } else {
            $tasa_v_tasas;
          }

          $fecha1 =  $Year . '' . $Month . ''  . '01';
          $fecha2 =  $Year . '' . $Month . ''  . $cantidad_Dias;


          $factura = getFactura($sede, $fecha1, $fecha2, '', $linea);
          $tasa_tot_neto_factura = $factura['tot_neto'] / $tasa_v_tasas;



          $dev_cli = getDev_cli($sede, $fecha1, $fecha2, '', $linea);
          $tasa_tot_neto_dev_cli = $dev_cli['tot_neto'] / $tasa_v_tasas;
          $tot_neto_dev_cli = number_format($tasa_tot_neto_dev_cli, 2, ',', '.');


          $venta = $tasa_tot_neto_factura - $tasa_tot_neto_dev_cli;
          $tot_neto_factura = number_format($venta, 2, ',', '.');


          $dev_cli_ven = getDev_cli($sede, $fecha1, $fecha2, 'ven2', $linea);
          $total_art_dev_cli = number_format($dev_cli_ven['total_art'], 0, ',', '.');

          $factura_ven = getFactura($sede, $fecha1, $fecha2, 'ven2', $linea);

          $venta_art = $factura_ven['total_art'] - $dev_cli_ven['total_art'];
          $total_art_factura  = number_format($venta_art, 0, ',', '.');


          $dep_caj = getDep_caj($sede, $fecha1, $fecha2, '');
          $tasa_total_efec_dep_caj = $dep_caj['total_efec'] / $tasa_v_tasas;
          $tasa_total_tarj_dep_caj = $dep_caj['total_tarj'] / $tasa_v_tasas;
          $total_efec_dep_caj = number_format($tasa_total_efec_dep_caj, 2, ',', '.');
          $total_tarj_dep_caj = number_format($tasa_total_tarj_dep_caj, 2, ',', '.');

          $mov_ban = getMov_ban($sede, $fecha1, $fecha2, '');
          $tasa_monto_h_mov_ban = $mov_ban['monto_h'] / $tasa_v_tasas;
          $monto_h_mov_ban = number_format($tasa_monto_h_mov_ban, 2, ',', '.');

          $ord_pago = getOrd_pago($sede, $fecha1, $fecha2, '');
          $tasa_monto_ord_pago = $ord_pago['monto'] / $tasa_v_tasas;
          $monto_ord_pago = number_format($tasa_monto_ord_pago, 2, ',', '.');

          $ord_pago_ven = getOrd_pago($sede, $fecha1, $fecha2, 'ven2');
          $tasa_monto_ord_pago_ven = $ord_pago_ven['monto'] / $tasa_v_tasas;
          $monto_ord_pago_ven = number_format($tasa_monto_ord_pago_ven, 2, ',', '.');


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

    $total_venta = 0;
    $total_venta_pares = 0;
    $total_devol = 0;
    $total_devol_pares = 0;
    $total_depositos = 0;
    $total_efectivo = 0;
    $total_tarjeta = 0;
    $total_pagos = 0;
    $total_gastos = 0;
  }
} else {
  header("location: form.php");
}



include '../../includes/footer.php'; ?>