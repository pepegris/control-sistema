<?php

if (isset($_POST)) {

    include '../includes/loading.php';
    $pedidos=$_POST['pedidos'];

    if ($pedidos=='con') {
        header('refresh:2;url= equipo.php');
    }else {
        header('refresh:2;url= equipo.php');
    }

} else {
    header('refresh:1;url= form.php');
    exit;
}
