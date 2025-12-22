<?php
// routes.php

// 1. IMPORTANTE: PRIMERO LA SESIÓN Y LOGS (Antes de enviar cualquier HTML)
require '../../includes/log.php';

// 2. CONFIGURACIÓN DE ERRORES Y TIEMPOS
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time', 300); 

// 3. DESHABILITAR COMPRESIÓN Y BUFFER (Para ver progreso en tiempo real)
if (function_exists('apache_setenv')) {
    @apache_setenv('no-gzip', 1);
}
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);

// Limpiamos cualquier buffer que haya abierto log.php
while (ob_get_level() > 0) {
    ob_end_clean();
}

// ====================================================================
// PASO 4: AHORA SÍ, CARGAMOS LA INTERFAZ VISUAL
// ====================================================================

// Verificamos que exista el archivo visual
if (file_exists('../../includes/loading-ordenes-compras.php')) {
    include '../../includes/loading-ordenes-compras.php';
} else {
    echo "<html><body style='background:#222; color:white; font-family:sans-serif;'>";
    echo "<center><h1>Procesando...</h1><div id='log-container'></div></center>";
}

// TRUCO DE MAGIA: Enviar espacios para forzar renderizado (después de log.php)
echo str_pad(' ', 4096); 
flush(); 

// ====================================================================
// PASO 5: CARGAR EL RESTO DE LÓGICA
// ====================================================================

include '../../services/adm/ordenes-compra/ordenes-compra.php'; 
include '../../services/mysql.php';

if (isset($_POST['tienda'])) {

    $tienda = $_POST['tienda'];
    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
    $corregir = isset($_POST['corregir']) ? $_POST['corregir'] : '';

    // MENSAJE INICIAL
    echo "<script>
        if(document.getElementById('log-container')) {
            document.getElementById('log-container').innerHTML += '<p style=\"color:#fff\">🚀 Iniciando proceso para $tienda...</p>';
        }
    </script>";
    flush(); 

    // --- BUSCAR FACTURAS ---
    $Factura_Ordenes = Factura_Ordenes($tienda, $fecha1, $corregir);

    // VALIDACIÓN
    if (!is_array($Factura_Ordenes) || count($Factura_Ordenes) === 0) {
        echo "<script>
            if(document.getElementById('spinner')) document.getElementById('spinner').style.display = 'none';
        </script>";
        echo "<center><h3 style='color:#ff5555; margin-top:20px;'>No hay Información que Importar</h3></center>";
        echo "<center><p>Revise la fecha o si ya fueron importadas.</p></center>";
        echo "<center><a href='form.php' class='btn btn-danger'>Volver</a></center>";
        echo "</div></body>"; 
        exit;
    }    

    // --- RECORRER FACTURAS ---
    foreach ($Factura_Ordenes as $factura) {

        $ordenes_fact_num = isset($factura['fact_num']) ? $factura['fact_num'] : '';
        $ordenes_contrib = isset($factura['contrib']) ? $factura['contrib'] : 0;
        $ordenes_saldo = isset($factura['saldo']) ? $factura['saldo'] : 0;
        $ordenes_tot_bruto = isset($factura['tot_bruto']) ? $factura['tot_bruto'] : 0;
        $ordenes_tot_neto = isset($factura['tot_neto']) ? $factura['tot_neto'] : 0;
        $ordenes_iva = isset($factura['iva']) ? $factura['iva'] : 0;

        // 1. CREAR CABECERA
        $orden = Ordenes_Compra($tienda, $ordenes_fact_num, $ordenes_contrib, $ordenes_saldo, $ordenes_tot_bruto, $ordenes_tot_neto, $ordenes_iva);
        
        // 2. INFORMAR CONEXIÓN
        global $tipo_conexion_actual;
        $msg_conexion = isset($tipo_conexion_actual) ? $tipo_conexion_actual : 'Desconocida';
        
        echo "<p style='color:#aaa; font-size:12px; margin:0; border-top:1px solid #444; margin-top:5px; padding-top:5px;'>
                Procesando Factura: <b style='color:#fff'>$ordenes_fact_num</b> <span style='font-size:10px'>($msg_conexion)</span>
              </p>";
        flush();

        // 3. RENGLONES
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
                    echo "<div style='color:#ff5555; font-size:14px;'>❌ Error Item: $rf_co_art</div>";
                } elseif ($corregir == '' && !$existe) {
                    echo "<div style='color:#00ff99; font-size:14px;'>✅ Item Creado: $rf_co_art</div>";
                }
                
                // Scroll automático
                echo "<script>window.scrollTo(0,document.body.scrollHeight);</script>";
                flush(); 
            }
        }

        // 4. ACTUALIZAR STATUS
        if ($corregir == 'IMPORTADO') {
            $importado = Up_Factura_Ordenes($tienda, $fecha1, $ordenes_fact_num, $orden, $reng_orden);
            echo "<h4 style='color:#fff; margin-top:5px; font-size:14px;'>📄 $importado</h4>";
            echo "<script>window.scrollTo(0,document.body.scrollHeight);</script>";
            flush();
        }
    }

    // FINALIZAR
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