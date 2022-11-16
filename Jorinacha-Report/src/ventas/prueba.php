<?php

ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';



$fecha1 = '20221001';



$Day = 31;
$Month = 10;
$Year = 2022;





for ($i = 1; $i < count($sedes_ar); $i++) {

    /* calcular si se solicito dolares y ver q tasa tenia ese dia */

    $tasa_tot_neto_factura = 0;  
    $tasa_tot_neto_dev_cli = 0;

    $venta = 0;
 
    $tasa_total_efec_dep_caj = 0;
    $tasa_total_tarj_dep_caj = 0;
  
    $tasa_monto_h_mov_ban= 0;
 
    $tasa_monto_ord_pago = 0;
  
    $tasa_monto_ord_pago_ven = 0;

    $e = 1;

    $sede =$sedes_ar[$i];

for ($r = 1; $r <= $Day; $r++) {

    


    if ($e  < 10) {

      $d = 0 . $e;
    } else {
      $d = $e;
    }


    $fecha =  $Year . $Month . $d;

    $tasas = getTasas("Comercial Kagu", $fecha);

    $tasa_v_tasas = $tasas['tasa_v'];

    var_dump($sede);
    var_dump($tasa_v_tasas);
    echo "<br>";



    $factura = getFactura($sede, $fecha, $fecha2, 'sin');

    $tasa_tot_neto_factura += $factura['tot_neto'] / $tasa_v_tasas;


    




    $dev_cli = getDev_cli($sede, $fecha, $fecha2, 'sin');
    $tasa_tot_neto_dev_cli += $dev_cli['tot_neto'] / $tasa_v_tasas;
    


    $venta += $tasa_tot_neto_factura - $tasa_tot_neto_dev_cli;


    var_dump($tasa_tot_neto_factura);
    echo "<br>";
    var_dump($tasa_tot_neto_dev_cli);
    echo "<br>";
    
    var_dump($venta);
    echo "<br>";
    var_dump($fecha);
    echo "<br>";

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


  $dev_cli_ven = getDev_cli($sede, $fecha1, $fecha2, 'ven2');
  $total_art_dev_cli = number_format($dev_cli_ven['total_art'], 0, ',', '.');

  $factura_ven = getFactura($sede, $fecha1, $fecha2, 'ven2');

  $venta_art = $factura_ven['total_art'] - $dev_cli_ven['total_art'];
  $total_art_factura  = number_format($venta_art, 0, ',', '.');

  echo "<hr>";
  var_dump($tot_neto_factura);
  echo "<br>";
  var_dump($sede);
  echo "<hr>";
}