<?php
require '../../includes/log.php';
include '../../includes/loading-ordenes-compras.php';
include '../../services/adm/ordenes-compra/ordenes-compra.php';
include '../../services/mysql.php';

if (isset($_POST)  ) {



    
    $tienda=$_POST['tienda'];

    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));

    $Factura_Ordenes = Factura_Ordenes($tienda,$fecha1);

 
    if ($Factura_Ordenes == null) {

        echo "<center><h3>No hay Información que Importar</h3></center>";
    }    
    

    $r=0;
    for ($i=0; $i < count($Factura_Ordenes) ; $i++) { 


        $ordenes_fact_num = $Factura_Ordenes[$r]['fact_num'];
        $ordenes_contrib = $Factura_Ordenes[$r]['contrib'];
        $ordenes_saldo = $Factura_Ordenes[$r]['saldo'];
        $ordenes_tot_bruto = $Factura_Ordenes[$r]['tot_bruto'];
        $ordenes_tot_neto = $Factura_Ordenes[$r]['tot_neto'];
        $ordenes_iva = $Factura_Ordenes[$r]['iva'];


        $orden=Ordenes_Compra($tienda,$ordenes_fact_num,$ordenes_contrib,$ordenes_saldo,$ordenes_tot_bruto,$ordenes_tot_neto,$ordenes_iva);
        $Reng_Factura = Reng_Factura($tienda,$fecha1,$ordenes_fact_num );

        echo "<center><h3>$ordenes_fact_num////////$Reng_Factura</h3></center>";
        echo "<br>";

        $f=0;
        for ($e=0; $e < count($Reng_Factura); $e++) { 

            $Reng_Factura_fact_num = $Reng_Factura[$f]['fact_num'] ;
            $Reng_Factura_reng_num = $Reng_Factura[$f]['reng_num'] ;
            $Reng_Factura_co_art = $Reng_Factura[$f]['co_art'] ;
            $Reng_Factura_total_art = $Reng_Factura[$f]['total_art'] ;
            $Reng_Factura_prec_vta = $Reng_Factura[$f]['prec_vta'] ;
            $Reng_Factura_reng_neto = $Reng_Factura[$f]['reng_neto'] ;
            $Reng_Factura_cos_pro_un= $Reng_Factura[$f]['cos_pro_un'] ;
            $Reng_Factura_ult_cos_un= $Reng_Factura[$f]['ult_cos_un'] ;
            $Reng_Factura_ult_cos_om= $Reng_Factura[$f]['ult_cos_om'] ;
            $Reng_Factura_cos_pro_om= $Reng_Factura[$f]['cos_pro_om'] ;

            $reng_orden= Reng_Ordenes($tienda,$Reng_Factura_fact_num,$Reng_Factura_reng_num,$Reng_Factura_co_art,$Reng_Factura_total_art,$Reng_Factura_prec_vta,$Reng_Factura_reng_neto,
            $Reng_Factura_cos_pro_un,
            $Reng_Factura_ult_cos_un,
            $Reng_Factura_ult_cos_om,
            $Reng_Factura_cos_pro_om);

            $f++;
        }
 
        $importado=Up_Factura_Ordenes($tienda,$fecha1,$ordenes_fact_num,$orden,$reng_orden);
        echo "<center><h3>$importado</h3></center>";
 
        $r++;
    }

    if ($orden==true && $reng_orden == true ) {
        echo "<center><a href='form.php' class='btn btn-success'>Volver</a></center>";
    }else {
        echo "<center><a href='form.php'' class='btn btn-danger'>Volver</a></center>";
    }


    

   
} else {
    header('refresh:1;url= form.php');
    exit;
}
?>