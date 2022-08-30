<?php

if (isset($_POST)) {

    include '../../includes/loading.php';

    $pedidos=$_POST['pedidos'];

    if ($pedidos=='con') {
        header("refresh:5;url= report-art-stock-completo.php?form=$_POST");
    }elseif ($pedidos=='sin') {
        header("refresh:5;url= report-art-stock.php?form=$_POST");
    }else {
        header('refresh:5;url= form.php');
    }

} else {
    header('refresh:5;url= form.php');
    exit;
}
