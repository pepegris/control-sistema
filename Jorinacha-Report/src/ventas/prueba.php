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




$e = 1;
for ($r = 1; $r <= $Day; $r++) {

    $sede=$sedes_ar[$r];
    var_dump($sede);

    if ($e  < 10) {

      $d = 0 . $e;
    } else {
      $d = $e;
    }


    $fecha =  $Year . $Month . $d;

    $tasas = getTasas($sede, $fecha);

    $tasa_v_tasas = $tasas['tasa_v'];

    var_dump($fecha);
    echo "<br>";
    var_dump($tasas);
    echo "<br>";
    var_dump($tasa_v_tasas);
    echo "<br>";



    $factura = getFactura($sede, $fecha, $fecha2, 'sin');

    $tasa_tot_neto_factura += $factura['tot_neto'] / $tasa_v_tasas;



    $dev_cli = getDev_cli($sede, $fecha, $fecha2, 'sin');
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