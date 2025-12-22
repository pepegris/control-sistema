<?php
// routes.php

// 1. ACTIVAR REPORTE DE ERRORES (Para ver qué pasa en lugar del Error 500)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. CONFIGURACIÓN DE TIEMPO Y BUFFER (Versión Compatible)
ini_set('max_execution_time', 300); 

// Iniciamos buffer solo si no está activo
if (ob_get_level() == 0) ob_start();
ob_implicit_flush(true);

// ---------------------------------------------------------
// VERIFICACIÓN DE ARCHIVOS (Evita Error 500 por rutas mal)
// ---------------------------------------------------------
$files_to_check = [
    '../../includes/log.php',
    '../../includes/loading-ordenes-compras.php',
    '../../services/adm/ordenes-compra/ordenes-compra.php',
    '../../services/mysql.php'
];

foreach ($files_to_check as $file) {
    if (!file_exists($file)) {
        die("<h3 style='color:red'>Error Fatal: No se encuentra el archivo: $file <br>Verifica la estructura de carpetas.</h3>");
    }
}

require '../../includes/log.php';
include '../../includes/loading-ordenes-compras.php'; 
include '../../services/adm/ordenes-compra/ordenes-compra.php'; 
include '../../services/mysql.php';

// Enviamos un "paquete" de espacios para forzar al navegador a mostrar la carga
echo str_pad(' ', 4096); 
flush();

if (isset($_POST['tienda'])) {

    $tienda = $_POST['tienda'];
    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
    $corregir = isset($_POST['corregir']) ? $_POST['corregir'] : '';

    // LOG VISUAL
    echo "<script>
        if(document.getElementById('log-container')) {
            document.getElementById('log-container').innerHTML += '<p>Conectando a central...</p>';
        }
    </script>";
    flush();

    // Paso 1: Buscar facturas
    $Factura_Ordenes = Factura_Ordenes($tienda, $fecha1, $corregir);

    // Validación
    if (!is_array($Factura_Ordenes) || count($Factura_Ordenes) === 0) {
        echo "<script>
            if(document.getElementById('spinner')) document.getElementById('spinner').style.display = 'none';
        </script>";
        echo "<center><h3 style='color:#ff5555;'>No hay Información que Importar (0 Registros encontrados)</h3></center>";
        echo "<center><a href='form.php' class='btn btn-danger'>Volver</a></center>";
        exit;
    }    

    // Paso 2: Recorrer
    foreach ($Factura_Ordenes as $factura) {

        // Validamos que existan las claves para evitar errores de índice
        $ordenes_fact_num = isset($factura['fact_num']) ? $factura['fact_num'] : '';
        $ordenes_contrib = isset($factura['contrib']) ? $factura['contrib'] : 0;
        $ordenes_saldo = isset($factura['saldo']) ? $factura['saldo'] : 0;
        $ordenes_tot_bruto = isset($factura['tot_bruto']) ? $factura['tot_bruto'] : 0;
        $ordenes_tot_neto = isset($factura['tot_neto']) ? $factura['tot_neto'] : 0;
        $ordenes_iva = isset($factura['iva']) ? $factura['iva'] : 0;

        // Crear Cabecera
        $orden = Ordenes_Compra($tienda, $ordenes_fact_num, $ordenes_contrib, $ordenes_saldo, $ordenes_tot_bruto, $ordenes_tot_neto, $ordenes_iva);
        
        // MOSTRAR TIPO DE CONEXIÓN
        // Declaramos la variable global para leerla desde la función
        global $tipo_conexion_actual;
        $msg_conexion = isset($tipo_conexion_actual) ? $tipo_conexion_actual : 'Desconocida';
        
        echo "<p style='color:#aaa; font-size:12px; margin:0; border-top:1px solid #444; margin-top:5px;'>Procesando Factura: $ordenes_fact_num ($msg_conexion)</p>";
        flush();

        // Renglones
        $Reng_Factura = Reng_Factura($tienda, $fecha1, $ordenes_fact_num);
        $reng_orden = false; 

        if (is_array($Reng_Factura)) {
            foreach ($Reng_Factura as $renglon) {
                
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

                if (!$verificacion) {
                    echo "<h3 style='color:#ff5555; font-size:16px;'>❌ Error: Art $rf_co_art</h3>";
                } elseif ($corregir == '' && !$existe) {
                    echo "<h3 style='color:#00ff99; font-size:16px;'>✅ Creado: Art $rf_co_art</h3>";
                }
                
                echo "<script>window.scrollTo(0,document.body.scrollHeight);</script>";
                flush(); 
            }
        }

        if ($corregir == 'IMPORTADO') {
            $importado = Up_Factura_Ordenes($tienda, $fecha1, $ordenes_fact_num, $orden, $reng_orden);
            echo "<h3 style='color:#fff; padding-bottom:10px;'>📄 $importado</h3>";
            echo "<script>window.scrollTo(0,document.body.scrollHeight);</script>";
            flush();
        }
    }

    echo "<script>
        if(document.getElementById('spinner')) document.getElementById('spinner').style.display = 'none'; 
        if(document.querySelector('h1')) document.querySelector('h1').innerText = 'PROCESO FINALIZADO';
    </script>";

    echo "<center><br><a href='form.php' class='btn btn-success btn-lg'>Volver al Inicio</a></center>";
    echo "</div></body>"; 

} else {
    header('Location: form.php');
    exit;
}
?>