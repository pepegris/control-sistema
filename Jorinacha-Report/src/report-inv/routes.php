<?php
require '../../includes/log.php';
include '../../includes/loading.php';
include '../../services/adm/inv/inv.php';
include '../../services/mysql.php';

if (isset($_POST)  ) {



    
    $tienda=$_POST['tienda'];
    $report=$_POST['reporte'];
    $almac=$_POST['almac'];

    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));


    if ($report=='global') {
        header("refresh:1;url= report-valor-inv.php?fecha1=$fecha1&tienda=$tienda&almac=$almac");
    }else {
        header("refresh:1;url= report-valor-inv-detallado.php?fecha1=$fecha1&tienda=$tienda&almac=$almac");
    }
    

} else {
    header('refresh:1;url= form.php');
    exit;
}
