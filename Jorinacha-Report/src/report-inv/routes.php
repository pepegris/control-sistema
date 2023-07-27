<?php
require '../../includes/log.php';
include '../../includes/loading.php';
include '../../services/adm/inv/inv.php';
include '../../services/mysql.php';

if (isset($_POST)  ) {



    
    $tienda=$_POST['tienda'];

    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));

    header("refresh:1;url= report-valor-inv.php?fecha1=$fecha1&tienda=$tienda");

} else {
    header('refresh:1;url= form.php');
    exit;
}
