<?php
require '../../includes/log.php';
#include '../../includes/loading.php';
include '../../services/adm/ordenes-compra/ordenes-compra.php';
include '../../services/mysql.php';

if (isset($_POST)  ) {



    
    $tienda=$_POST['tienda'];

    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));

    $Factura_Ordenes = Factura_Ordenes($tienda,$fecha1);
    


    for ($i=0; $i < count($Factura_Ordenes) ; $i++) { 

        $ordenes_fact_num = $Factura_Ordenes[$r]['fact_num'];
        $ordenes_contrib = $Factura_Ordenes[$r]['contrib'];
        $ordenes_saldo = $Factura_Ordenes[$r]['saldo'];
        $ordenes_iva = $Factura_Ordenes[$r]['iva'];
        
        $Reng_Factura = Reng_Factura($tienda,$fecha1,$Factura_Ordenes[0]['fact_num']);


    }




    

   
} else {
    header('refresh:1;url= form.php');
    exit;
}
