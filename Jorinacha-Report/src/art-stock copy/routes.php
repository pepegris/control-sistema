<?php

if (isset($_POST)) {

    include '../../includes/loading.php';

    $pedidos=$_POST['pedidos'];

    if ($pedidos=='con') {
        header("refresh:5;url= report-art-stock-completo.php ,$_POST");
    }elseif ($pedidos=='sin') {
        header("refresh:5;url= report-art-stock.php;$_POST");
    }else {
        header('refresh:5;url= form.php');
    }

} else {
    header('refresh:5;url= form.php');
    exit;
}
