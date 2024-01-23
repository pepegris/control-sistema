<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

if ($_GET) {

  $divisa = $_GET['divisa'];

  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));


  $Month = date("m", strtotime($fecha2));
  $Year = date("Y", strtotime($fecha2));

  $Day_total = date("d", strtotime($fecha2));
  $Month_total = date("m", strtotime($fecha2));







?>

  <style>
    img {


      width: 28px;
    }
  </style>


  <link rel='stylesheet' href='responm.css'>


  

  <?php

  if ($divisa == 'dl') {

    echo "<h4>En Dolares</h4>";
  } else {
    echo "<h4>En Bolivares</h4>";
  }

  $y=1;
  for ($k = 0; $k < $Month_total; $k++) {




    if ($y < 10) {

      $mes = 0 . $y;
    } else {
      $mes = $y;
    }

    $y++;



    echo "<center> <h2>Ventas del Mes $mes del $Year</h2> </center>";




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

          <th scope='col'>Cuadre Caja</th>

          <th scope='col'>Cierre Caja</th>
        </tr>

      </thead>
      <tbody>

        <?php

        $cantidad_Dias = cal_days_in_month(CAL_GREGORIAN, $mes, $Year);


        for ($i = 1; $i < count($sedes_ar); $i++) {

          $cod = Cliente($sedes_ar[$i]);
          $tienda=$sedes_ar[$i];

          $w=1;
          $venta =0;
          for ($u = 1; $u < $cantidad_Dias; $u++) {
            $w++;

            if ($w < 10) {

              $dia = 0 . $w;
            } else {
              $dia = $w;
            }
            
            $fecha_2 = $Year . '/' . $mes . '/' . $dia;
    
            if ($w == 1) {
        
              $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
            } else {
              $fecha1 = $Year . '' . $mes . '' . $dia;
            }


          if ($divisa == 'dl') {

            $tasas = getTasas($tienda,  $fecha_2);

            if ($tasas != null) {
              $tasa_v_tasas = $tasas['tasa_v'];
            } else {
              $tasa_v_tasas;
            }
          } else {
            $tasa_v_tasas  = 1;
          }

         

          $factura = getFactura($tienda, $fecha1, $fecha3, 'sin');
          $tasa_tot_neto_factura = $factura['tot_neto'] / $tasa_v_tasas;

          $dev_cli = getDev_cli($tienda, $fecha1, $fecha3, 'sin');
          $tasa_tot_neto_dev_cli = $dev_cli['tot_neto'] / $tasa_v_tasas;
          #$tot_neto_dev_cli = number_format($tasa_tot_neto_dev_cli, 2, ',', '.');


          $venta += $tasa_tot_neto_factura - $tasa_tot_neto_dev_cli;
          #$tot_neto_factura += number_format($venta, 2, ',', '.');


          $factura_ven = getFactura($tienda, $fecha1, $fecha3, 'ven');
          $total_art_factura =  number_format($factura_ven['total_art'], 0, ',', '.');

          $dev_cli_ven = getDev_cli($tienda, $fecha1, $fecha3, 'ven');
          $total_art_dev_cli = number_format($dev_cli_ven['total_art'], 0, ',', '.');


          $pares+=$total_art_factura - $total_art_dev_cli  ;


          $dep_caj = getDep_caj($tienda, $fecha1, $fecha3, 'sin');
          $tasa_total_efec_dep_caj += $dep_caj['total_efec'] / $tasa_v_tasas;
          $tasa_total_tarj_dep_caj += $dep_caj['total_tarj'] / $tasa_v_tasas;
          #$total_efec_dep_caj = number_format($tasa_total_efec_dep_caj, 2, ',', '.');
          #$total_tarj_dep_caj = number_format($tasa_total_tarj_dep_caj, 2, ',', '.');

          $mov_ban = getMov_ban($tienda, $fecha1, $fecha3, 'sin');
          $tasa_monto_h_mov_ban += $mov_ban['monto_h'] / $tasa_v_tasas;
          #$monto_h_mov_ban = number_format($tasa_monto_h_mov_ban, 2, ',', '.');

          $ord_pago = getOrd_pago($tienda, $fecha1, $fecha3, 'sin');
          $tasa_monto_ord_pago += $ord_pago['monto'] / $tasa_v_tasas;
          #$monto_ord_pago = number_format($tasa_monto_ord_pago, 2, ',', '.');

          $ord_pago_ven = getOrd_pago($tienda, $fecha1, $fecha3, 'ven');
          $tasa_monto_ord_pago_ven += $ord_pago_ven['monto'] / $tasa_v_tasas;
          #$monto_ord_pago_ven = number_format($tasa_monto_ord_pago_ven, 2, ',', '.');



        }

        $tot_neto_dev_cli = number_format($tasa_tot_neto_dev_cli, 2, ',', '.');
        $tot_neto_factura=number_format($venta, 2, ',', '.');
        $total_efec_dep_caj = number_format($tasa_total_efec_dep_caj, 2, ',', '.');
        $total_tarj_dep_caj = number_format($tasa_total_tarj_dep_caj, 2, ',', '.');
        $monto_h_mov_ban = number_format($tasa_monto_h_mov_ban, 2, ',', '.');
        $monto_ord_pago = number_format($tasa_monto_ord_pago, 2, ',', '.');
        $monto_ord_pago_ven = number_format($tasa_monto_ord_pago_ven, 2, ',', '.');

        ?>
          <tr>

            <td><?= $cod   ?></td>
            <td><?= $tienda  ?></td>



            <td><?= $tot_neto_factura    ?></td>
            <td><?= $pares   ?></td>

            <td><?= $tot_neto_dev_cli   ?></td>
            <td><?= $total_art_dev_cli  ?></td>

            <td><?= $monto_h_mov_ban ?></td>


            <td><?= $total_efec_dep_caj  ?></td>
            <td><?= $total_tarj_dep_caj  ?></td>

            <td><?= $monto_ord_pago  ?></td>
            <td><?= $monto_ord_pago_ven  ?></td>

            <td> <?php



                    /* totales */

                    
                    
                    $total_venta += $venta;
                    #$total_venta += $tasa_tot_neto_factura - $tasa_tot_neto_dev_cli;
                    $total_venta_pares += $pares;

                    $total_devol += $tasa_tot_neto_dev_cli;
                    $total_devol_pares += $total_art_dev_cli;

                    $total_depositos += $tasa_monto_h_mov_ban;

                    $total_efectivo += $tasa_total_efec_dep_caj;
                    $total_tarjeta += $tasa_total_tarj_dep_caj;

                    $total_pagos += $tasa_monto_ord_pago;
                    $total_gastos += $tasa_monto_ord_pago_ven;

          
                    $tasa_tot_neto_factura =0;
                    $tasa_tot_neto_dev_cli =0;
                    $tot_neto_dev_cli = 0;
                    $tot_neto_factura =0;
                    $total_art_factura =0;
                    $total_art_dev_cli =0;
                    $tasa_total_efec_dep_caj =0;
                    $tasa_total_tarj_dep_caj =0;
                    $total_efec_dep_caj = 0;
                    $total_tarj_dep_caj = 0;
                    $tasa_monto_h_mov_ban =0;
                    $monto_h_mov_ban = 0;
                    $tasa_monto_ord_pago =0;
                    $monto_ord_pago = 0;
                    $tasa_monto_ord_pago_ven =0;
                    $monto_ord_pago_ven = 0;
          

                  if ($venta <= 1 & $total_art_factura == 0) {

                    echo " <img src='./img/help.svg' alt=''> ";
                  } elseif ($tot_neto_factura > 1 & $monto_h_mov_ban > 1) {

                    $diferencias = number_format($tasa_monto_ord_pago + $tasa_monto_ord_pago_ven + $tasa_monto_h_mov_ban - $venta, 2, ',', '.');
                    echo "$diferencias";
                  } elseif ($total_tarj_dep_caj > 1) {

                    $diferencias = number_format($tasa_total_efec_dep_caj + $tasa_total_tarj_dep_caj + $tasa_monto_ord_pago + $tasa_monto_ord_pago_ven - $venta, 2, ',', '.');

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
          $colspan = 3;
        } else {

          $simb = 'Bs';
          $colspan = 2;
        }

          ?>



          <tr>
            <td colspan="<?= $colspan ?>">
              <h3>Totales</h3>
            </td>



            <td><b><?= $simb ?><?= number_format($total_venta, 2, ',', '.')  ?></b></td>
            <td><b><?= $total_venta_pares ?></b></td>

            <td><b><?= $simb ?><?= number_format($total_devol, 2, ',', '.')  ?></b></td>
            <td><b><?= number_format($total_devol_pares, 0, '', '.')  ?></b></td>

            <td><b><?= $simb ?><?= number_format($total_depositos, 2, ',', '.')  ?></b></td>

            <td><b><?= $simb ?><?= number_format($total_efectivo, 2, ',', '.')  ?></b></td>
            <td><b><?= $simb ?><?= number_format($total_tarjeta, 2, ',', '.')  ?></b></td>

            <td><b><?= $simb ?><?= number_format($total_pagos, 2, ',', '.')  ?></b></td>
            <td><b><?= $simb ?><?= number_format($total_gastos, 2, ',', '.')  ?></b></td>
            <td><b><?= $simb ?><?= number_format($total_diferencias, 2, ',', '.')  ?></b></td>


            <td></td>

          </tr>

      </tbody>


    </table>

<?php
    $total_venta = 0;
    $total_venta = 0;
    $total_devol = 0;
    $total_devol_pares = 0;
    $total_depositos = 0;
    $total_efectivo = 0;
    $total_tarjeta = 0;
    $total_pagos = 0;
    $total_gastos = 0;
    $total_diferencias = 0;

    $total_venta_pares=0;


    $tasa_tot_neto_factura= 0;
    $tasa_tot_neto_dev_cli= 0;
    $factura_ven= 0;
    $tasa_tot_neto_dev_cli= 0;
    $dev_cli_ven= 0;
    $tasa_monto_h_mov_ban= 0;
    $tasa_total_efec_dep_caj= 0;
    $tasa_total_tarj_dep_caj= 0;
    $tasa_monto_ord_pago= 0;
    $tasa_monto_ord_pago_ven= 0;


  }
} else {
  header("location: form.php");
}



include '../../includes/footer.php'; ?>