<?php
// routes.php

// 1. DESHABILITAR BUFFERS Y CACHÉ (OBLIGATORIO PARA VER PROGRESO)
@apache_setenv('no-gzip', 1);
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);
for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
ob_implicit_flush(1);

// Incluimos la visual PRIMERO
include '../../includes/loading-ordenes-compras.php'; 

// 2. ENVIAR "RELLENO" PARA OBLIGAR AL NAVEGADOR A MOSTRAR EL LOADING
// Enviamos 4kb de espacios en blanco invisibles. Chrome necesita esto para arrancar.
echo str_pad(' ', 4096); 
flush();

require '../../includes/log.php';
include '../../services/adm/ordenes-compra/ordenes-compra.php'; 
include '../../services/mysql.php';

ini_set('max_execution_time', 300); 

if (isset($_POST['tienda'])) {

    $tienda = $_POST['tienda'];
    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
    $corregir = isset($_POST['corregir']) ? $_POST['corregir'] : '';

    // LOG VISUAL
    echo "<script>document.getElementById('log-container').innerHTML += '<p>Buscando facturas...</p>';</script>";
    flush();

    $Factura_Ordenes = Factura_Ordenes($tienda, $fecha1, $corregir);

    if (!is_array($Factura_Ordenes) || count($Factura_Ordenes) === 0) {
        echo "<script>document.getElementById('spinner').style.display = 'none';</script>";
        echo "<center><h3 style='color:#ff5555;'>No hay Información que Importar</h3></center>";
        echo "<center><a href='form.php' class='btn btn-danger'>Volver</a></center>";
        exit;
    }    

    foreach ($Factura_Ordenes as $factura) {

        $ordenes_fact_num = $factura['fact_num'];
        $ordenes_contrib = $factura['contrib'];
        // ... resto de variables ...
        $ordenes_saldo = $factura['saldo'];
        $ordenes_tot_bruto = $factura['tot_bruto'];
        $ordenes_tot_neto = $factura['tot_neto'];
        $ordenes_iva = $factura['iva'];

        // Crear Cabecera (Aquí se define la conexión)
        $orden = Ordenes_Compra($tienda, $ordenes_fact_num, $ordenes_contrib, $ordenes_saldo, $ordenes_tot_bruto, $ordenes_tot_neto, $ordenes_iva);
        
        // --- MOSTRAR TIPO DE CONEXIÓN USADA ---
        // Accedemos a la variable global que definimos en ordenes-compra.php
        global $tipo_conexion_actual; 
        echo "<p style='color:#aaa; font-size:12px; margin:0;'>Conectando a $tienda vía: <b style='color:yellow'>$tipo_conexion_actual</b></p>";
        flush(); 
        // --------------------------------------

        $Reng_Factura = Reng_Factura($tienda, $fecha1, $ordenes_fact_num);
        $reng_orden = false; 

        if (is_array($Reng_Factura)) {
            foreach ($Reng_Factura as $renglon) {
                // ... variables de renglón ...
                $rf_fact_num = $renglon['fact_num'];
                $rf_reng_num = $renglon['reng_num'];
                $rf_co_art = $renglon['co_art'];
                $rf_total_art = $renglon['total_art'];
                $rf_prec_vta = $renglon['prec_vta'];
                $rf_reng_neto = $renglon['reng_neto'];
                $rf_cos_pro_un = $renglon['cos_pro_un'];
                $rf_ult_cos_un = $renglon['ult_cos_un'];
                $rf_ult_cos_om = $renglon['ult_cos_om'];
                $rf_cos_pro_om = $renglon['cos_pro_om'];

                $existe = Con_Reng_Ordenes($tienda, $rf_fact_num, $rf_reng_num);
                $insertado = Reng_Ordenes($tienda, $rf_fact_num, $rf_reng_num, $rf_co_art, $rf_total_art, $rf_prec_vta, $rf_reng_neto, $rf_cos_pro_un, $rf_ult_cos_un, $rf_ult_cos_om, $rf_cos_pro_om);
                
                if($insertado) $reng_orden = true;

                $verificacion = Con_Reng_Ordenes($tienda, $rf_fact_num, $rf_reng_num);

                // --- MENSAJES DE LOG ---
                if (!$verificacion) {
                    echo "<h3 style='color:#ff5555; font-size:16px;'>❌ Error: Art $rf_co_art (Orden $rf_fact_num)</h3>";
                } elseif ($corregir == '' && !$existe) {
                    echo "<h3 style='color:#00ff99; font-size:16px;'>✅ Creado: Art $rf_co_art (Orden $rf_fact_num)</h3>";
                }
                
                // SCROLL AUTOMÁTICO Y FORZAR PINTADO
                echo "<script>window.scrollTo(0,document.body.scrollHeight);</script>";
                flush(); 
            }
        }

        if ($corregir == 'IMPORTADO') {
            $importado = Up_Factura_Ordenes($tienda, $fecha1, $ordenes_fact_num, $orden, $reng_orden);
            echo "<h3 style='color:#fff; border-bottom:1px solid #444; padding-bottom:10px;'>📄 $importado</h3>";
            echo "<script>window.scrollTo(0,document.body.scrollHeight);</script>";
            flush();
        }
    }

    echo "<script>
        document.getElementById('spinner').style.display = 'none'; 
        document.querySelector('h1').innerText = 'PROCESO FINALIZADO';
    </script>";

    echo "<center><br><a href='form.php' class='btn btn-success btn-lg'>Volver al Inicio</a></center>";
    echo "</div></body>"; // Cierre de loading

} else {
    header('Location: form.php');
    exit;
}
?>