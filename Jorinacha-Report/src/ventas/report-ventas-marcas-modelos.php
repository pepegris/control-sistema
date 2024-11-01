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

  for ($t = 1; $t <= count($sedes_ar); $t++) {

    $sede = $sedes_ar[$t];
    $cod = Cliente($sede);

    echo "<center> <h2>$sede</h2> </center>";
    $y = 1;
    for ($k = 1; $k <= $Month_total; $k++) {




      if ($y < 10) {

        $Month = 0 . $y;
      } else {
        $Month = $y;
      }

      $y++;

      echo "<center> <h3>Ventas del Mes $Month del $Year</h3> </center>";


  ?>



      <table class="table table-dark table-striped" id="tblData">
        <thead>



          <tr>
            <th scope="col">Marca</th>
            <th scope='col'>Modelo</th>

            <th scope='col'>Ventas</th>
            <th scope='col'>Pares</th>

            <th scope='col'>Devol</th>
            <th scope='col'>Pares Dev</th>

          </tr>

        </thead>
        <tbody>

          <?php

          $cantidad_Dias = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
          $fecha1 =  $Year . '' . $Month . ''  . '01';
          $fecha2 =  $Year . '' . $Month . ''  . $cantidad_Dias;
          $marcas = getLin_art($sede, $fecha1, $fecha2, '', $linea);



          for ($i = 1; $i < count($marcas); $i++) {

            $tasa_tot_neto_factura = 0;
            $tasa_tot_neto_dev_cli = 0;
            $venta = 0;
            $tasa_total_efec_dep_caj = 0;
            $tasa_total_tarj_dep_caj = 0;
            $tasa_monto_h_mov_ban = 0;
            $tasa_monto_ord_pago = 0;
            $tasa_monto_ord_pago_ven = 0;



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






            /* totales */



            $total_venta += $tasa_tot_neto_factura - $tasa_tot_neto_dev_cli;
            $total_venta_pares += $venta_art;

            $total_devol += $tasa_tot_neto_dev_cli;
            $total_devol_pares += $dev_cli_ven['total_art'];






          ?>
            <tr>

              <td><?= $cod   ?></td>
              <td><?= $sede  ?></td>


              <td><?= $tot_neto_factura    ?></td>
              <td><?= $total_art_factura   ?></td>

              <td><?= $tot_neto_dev_cli   ?></td>
              <td><?= $total_art_dev_cli  ?></td>







            <?php

          }

          if ($divisa == 'dl') {

            $simb = '$';
          } else {

            $simb = 'Bs';
          }

            ?>

            </tr>

            <tr>
              <td colspan="2">
                <h3>Totales</h3>
              </td>



              <td><b><?= $simb ?><?= number_format($total_venta, 2, ',', '.')  ?></b></td>
              <td><b><?= number_format($total_venta_pares, 0, '', '.')  ?></b></td>


              <td><b><?= $simb ?><?= number_format($total_devol, 2, ',', '.')  ?></b></td>
              <td><b><?= number_format($total_devol_pares, 0, '', '.')  ?></b></td>

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
  }
} else {
  header("location: form.php");
}



include '../../includes/footer.php'; ?>