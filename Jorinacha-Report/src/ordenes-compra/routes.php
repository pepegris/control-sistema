<?php
require '../../includes/log.php';
#include '../../includes/loading.php';
include '../../services/adm/ordenes-compra/ordenes-compra.php';
include '../../services/mysql.php';

if (isset($_POST)  ) {



    
    $tienda=$_POST['tienda'];

    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));

    $Factura_Ordenes = Factura_Ordenes($tienda,$fecha1);
    $Reng_Factura = Reng_Factura($tienda,$fecha1,$Factura_Ordenes[0]['fact_num']);

    var_dump($Factura_Ordenes);
    echo "<br>";echo "<br>";
    var_dump($Factura_Ordenes[0]['fact_num']);
    echo "<br>";
    var_dump($Reng_Factura);


    $prueba=$Factura_Ordenes[0]['fact_num'];
    $Reng_Factura2 = Reng_Factura($tienda,$fecha1,$prueba);
    var_dump($prueba);
    echo "<br>";
    var_dump($Reng_Factura2);

    echo "<br>";

    for ($i=0; $i < count($Factura_Ordenes) ; $i++) { 
        # code...
    }

    for ($i=0; $i < count($Reng_Factura) ; $i++) { 
        # code...
    }


    

   
} else {
    header('refresh:1;url= form.php');
    exit;
}
