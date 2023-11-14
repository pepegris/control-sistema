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

    $Factura_Ordenes = Factura_Ordenes($tienda,$fecha1);

    var_dump(count($Factura_Ordenes['fact_num']));
    echo "<br>";
    var_dump($Factura_Ordenes[1]['fact_num']);

    echo "<br>";

    for ($i=0; $i < count($Factura_Ordenes['fact_num']) ; $i++) { 
        # code...
    }


    

   
} else {
    header('refresh:1;url= form.php');
    exit;
}
