<?php
require '../../includes/log.php';
include '../../includes/loading-ordenes-compras.php';
include '../../services/adm/ordenes-compra/ordenes-compra.php';
include '../../services/mysql.php';

if (isset($_POST)  ) {



    
    $tienda=$_POST['tienda'];

    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
    $corregir= $_POST['corregir'];

    $Factura_Ordenes = Factura_Ordenes($tienda,$fecha1,$corregir);

 
    if ($Factura_Ordenes == null) {

        echo "<center><h3>No hay Información que Importar</h3></center>";
    }    
    

  
    for ($i=0; $i < count($Factura_Ordenes) ; $i++) { 


        $ordenes_fact_num = $Factura_Ordenes[$i]['fact_num'];
        $ordenes_contrib = $Factura_Ordenes[$i]['contrib'];
        $ordenes_saldo = $Factura_Ordenes[$i]['saldo'];
        $ordenes_tot_bruto = $Factura_Ordenes[$i]['tot_bruto'];
        $ordenes_tot_neto = $Factura_Ordenes[$i]['tot_neto'];
        $ordenes_iva = $Factura_Ordenes[$i]['iva'];


        $orden=Ordenes_Compra($tienda,$ordenes_fact_num,$ordenes_contrib,$ordenes_saldo,$ordenes_tot_bruto,$ordenes_tot_neto,$ordenes_iva);
        $Reng_Factura = Reng_Factura($tienda,$fecha1,$ordenes_fact_num );

    


        for ($e=0; $e < count($Reng_Factura); $e++) { 

            $Reng_Factura_fact_num = $Reng_Factura[$e]['fact_num'] ;
            $Reng_Factura_reng_num = $Reng_Factura[$e]['reng_num'] ;
            $Reng_Factura_co_art = $Reng_Factura[$e]['co_art'] ;
            $Reng_Factura_total_art = $Reng_Factura[$e]['total_art'] ;
            $Reng_Factura_prec_vta = $Reng_Factura[$e]['prec_vta'] ;
            $Reng_Factura_reng_neto = $Reng_Factura[$e]['reng_neto'] ;
            $Reng_Factura_cos_pro_un= $Reng_Factura[$e]['cos_pro_un'] ;
            $Reng_Factura_ult_cos_un= $Reng_Factura[$e]['ult_cos_un'] ;
            $Reng_Factura_ult_cos_om= $Reng_Factura[$e]['ult_cos_om'] ;
            $Reng_Factura_cos_pro_om= $Reng_Factura[$e]['cos_pro_om'] ;

            $Con_Reng_Factura_error=Con_Reng_Ordenes($tienda, $Reng_Factura_fact_num, $Reng_Factura_reng_num );

            $reng_orden= Reng_Ordenes($tienda,$Reng_Factura_fact_num,$Reng_Factura_reng_num,$Reng_Factura_co_art,$Reng_Factura_total_art,$Reng_Factura_prec_vta,$Reng_Factura_reng_neto,
            $Reng_Factura_cos_pro_un,
            $Reng_Factura_ult_cos_un,
            $Reng_Factura_ult_cos_om,
            $Reng_Factura_cos_pro_om);

            $Con_Reng_Factura=Con_Reng_Ordenes($tienda, $Reng_Factura_fact_num, $Reng_Factura_reng_num );

            if ($Con_Reng_Factura == null) {

                echo "<center><h3>Articulo sin Crear $Reng_Factura_co_art- Orden de Compra 10$Reng_Factura_fact_num Renglon -$Reng_Factura_reng_num </h3></center>";

            } elseif ($corregir == 'error' && $Con_Reng_Factura_error == null) {

                echo "<center><h3>Articulos creados $Reng_Factura_co_art- Orden de Compra 10$Reng_Factura_fact_num Renglon -$Reng_Factura_reng_num </h3></center>";
            } 


        } 

        if ($corregir == 'IMPORTADO') {
            $importado=Up_Factura_Ordenes($tienda,$fecha1,$ordenes_fact_num,$orden,$reng_orden);
            echo "<center><h3>$importado</h3></center>";
        }

  
 
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