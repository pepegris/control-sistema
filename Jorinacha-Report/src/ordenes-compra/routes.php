<?php
require '../../includes/log.php';
#include '../../includes/loading.php';
include '../../services/adm/ordenes-compra/ordenes-compra.php';
include '../../services/mysql.php';

if (isset($_POST)  ) {



    
    $tienda=$_POST['tienda'];

    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));

    $database = Database($tienda);
    $cliente = Cliente($tienda);
    var_dump($database);
    var_dump($cliente);

    echo "<br>";

    $prueba = Factura_Ordenes($tienda,$fecha1);

    var_dump($prueba);


    

   
} else {
    header('refresh:1;url= form.php');
    exit;
}
