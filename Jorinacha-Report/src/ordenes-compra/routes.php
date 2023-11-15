<?php
require '../../includes/log.php';
#include '../../includes/loading.php';
include '../../services/adm/ordenes-compra/ordenes-compra.php';
include '../../services/mysql.php';

if (isset($_POST)  ) {



    
    $tienda=$_POST['tienda'];
    var_dump($tienda);

    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));

    $Factura_Ordenes = Factura_Ordenes($tienda,$fecha1);


    $r=0;
    for ($i=0; $i < count($Factura_Ordenes) ; $i++) { 

        $ordenes_fact_num = $Factura_Ordenes[$r]['fact_num'];
        $ordenes_contrib = $Factura_Ordenes[$r]['contrib'];
        $ordenes_saldo = $Factura_Ordenes[$r]['saldo'];
        $ordenes_tot_bruto = $Factura_Ordenes[$r]['tot_bruto'];
        $ordenes_tot_neto = $Factura_Ordenes[$r]['tot_neto'];
        $ordenes_iva = $Factura_Ordenes[$r]['iva'];
        echo "ordenes_fact_num";
        var_dump($ordenes_fact_num[$r]['fact_num']);
        echo "<br>";
        echo "<br>";
        $orden=Ordenes_Compra($tienda,$ordenes_fact_num,$ordenes_contrib,$ordenes_saldo,$ordenes_tot_bruto,$$ordenes_tot_neto,$ordenes_iva);
        echo "Orden";
        var_dump($orden);
        echo "<br>";
        echo "<br>";
        $Reng_Factura = Reng_Factura($tienda,$fecha1,$Factura_Ordenes[$r]['fact_num']);
        echo "Reng_Factura";
        var_dump($Reng_Factura[$r]['fact_num']);
        echo "<br>";
        echo "<br>";

        for ($e=0; $e < count($Reng_Factura); $e++) { 
            # code...
        }

        $r++;
    }




    

   
} else {
    header('refresh:1;url= form.php');
    exit;
}
