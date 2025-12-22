<?php
// routes.php

// ============================================================================
// PARTE 1: EL CEREBRO (PROCESO EN SEGUNDO PLANO)
// ============================================================================
// Si el script recibe la señal "modo_stream", ejecuta la lógica y devuelve texto.
if (isset($_GET['modo_stream'])) {

    // Configuración para que no haya caché ni esperas
    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-cache');
    @ini_set('implicit_flush', 1);
    @ini_set('zlib.output_compression', 0);
    ini_set('max_execution_time', 600);

    require '../../includes/log.php';
    include '../../services/adm/ordenes-compra/ordenes-compra.php'; 
    include '../../services/mysql.php';

    // Recibimos los datos por GET (vienen del JavaScript)
    $tienda = $_GET['tienda'];
    $fecha1 = $_GET['fecha1'];
    $corregir = $_GET['corregir'];

    // Función auxiliar para enviar mensajes al JS
    function enviarMsg($msg, $tipo = 'info') {
        $color = '#fff'; // blanco
        if ($tipo == 'error') $color = '#ff5555'; // rojo
        if ($tipo == 'success') $color = '#00ff99'; // verde
        if ($tipo == 'header') {
            echo "<div style='border-top:1px solid #444; margin-top:10px; padding-top:5px; color:#aaa; font-size:12px;'>$msg</div>";
        } elseif ($tipo == 'titulo') {
            echo "<h4 style='color:#fff; border-bottom:1px solid #333; padding-bottom:5px; margin-top:5px;'>$msg</h4>";
        } else {
            echo "<div style='color:$color; font-size:14px; margin-left:10px;'>$msg</div>";
        }
        flush(); // Empujar datos
    }

    // --- INICIO DE LÓGICA ---
    $Factura_Ordenes = Factura_Ordenes($tienda, $fecha1, $corregir);

    if (!is_array($Factura_Ordenes) || count($Factura_Ordenes) === 0) {
        enviarMsg("❌ No hay Información que Importar para la fecha seleccionada.", 'error');
        exit;
    }

    foreach ($Factura_Ordenes as $factura) {
        $ordenes_fact_num = $factura['fact_num'];
        // Datos cabecera...
        $ordenes_contrib = $factura['contrib']; $ordenes_saldo = $factura['saldo'];
        $ordenes_tot_bruto = $factura['tot_bruto']; $ordenes_tot_neto = $factura['tot_neto'];
        $ordenes_iva = $factura['iva'];

        // 1. Crear Cabecera
        $orden = Ordenes_Compra($tienda, $ordenes_fact_num, $ordenes_contrib, $ordenes_saldo, $ordenes_tot_bruto, $ordenes_tot_neto, $ordenes_iva);
        
        // Detectar conexión
        global $tipo_conexion_actual;
        $con = isset($tipo_conexion_actual) ? $tipo_conexion_actual : 'Desconocida';
        
        enviarMsg("Procesando Factura: <b>$ordenes_fact_num</b> ($con)", 'header');

        // 2. Renglones
        $Reng_Factura = Reng_Factura($tienda, $fecha1, $ordenes_fact_num);
        $reng_orden = false;

        if (is_array($Reng_Factura)) {
            foreach ($Reng_Factura as $renglon) {
                // Variables renglón...
                $rf_fact_num = $renglon['fact_num']; $rf_reng_num = $renglon['reng_num']; $rf_co_art = $renglon['co_art'];
                $rf_total_art = $renglon['total_art']; $rf_prec_vta = $renglon['prec_vta']; $rf_reng_neto = $renglon['reng_neto'];
                $rf_cos_pro_un = $renglon['cos_pro_un']; $rf_ult_cos_un = $renglon['ult_cos_un'];
                $rf_ult_cos_om = $renglon['ult_cos_om']; $rf_cos_pro_om = $renglon['cos_pro_om'];

                $existe = Con_Reng_Ordenes($tienda, $rf_fact_num, $rf_reng_num);
                $insertado = Reng_Ordenes($tienda, $rf_fact_num, $rf_reng_num, $rf_co_art, $rf_total_art, $rf_prec_vta, $rf_reng_neto, $rf_cos_pro_un, $rf_ult_cos_un, $rf_ult_cos_om, $rf_cos_pro_om);
                
                if ($insertado) $reng_orden = true;
                
                $verificacion = Con_Reng_Ordenes($tienda, $rf_fact_num, $rf_reng_num);

                if (!$verificacion) {
                    enviarMsg("Error Item: $rf_co_art", 'error');
                } elseif ($corregir == '' && !$existe) {
                    enviarMsg("✔ Item Creado: $rf_co_art", 'success');
                }
            }
        }

        // 3. Actualizar Status
        if ($corregir == 'IMPORTADO') {
            $importado = Up_Factura_Ordenes($tienda, $fecha1, $ordenes_fact_num, $orden, $reng_orden);
            enviarMsg("📄 " . $importado, 'titulo');
        }
    }
    
    // Señal de finalización
    echo "";
    exit; // Terminamos el proceso PHP aquí.
}
?>

<?php
// Si no hay POST, devolvemos al form
if (!isset($_POST['tienda'])) { header('Location: form.php'); exit; }

// Preparamos los datos para pasarlos al JavaScript
$tiendaJS = urlencode($_POST['tienda']);
$fechaJS = urlencode(date("Ymd", strtotime($_POST['fecha1'])));
$corregirJS = urlencode(isset($_POST['corregir']) ? $_POST['corregir'] : '');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesando...</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap-5.2.0-dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #242943; color: white; font-family: 'Segoe UI', sans-serif;
            display: flex; flex-direction: column; align-items: center; min-height: 100vh; padding-top: 50px;
        }
        /* Spinner CSS Puro */
        .spinner {
            width: 60px; height: 60px;
            border: 6px solid rgba(255,255,255,0.1);
            border-top: 6px solid #00ff99;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        #log-container {
            width: 90%; max-width: 800px;
            background: rgba(0,0,0,0.4);
            border: 1px solid #444; border-radius: 8px;
            padding: 20px; height: 400px; overflow-y: auto;
            font-family: monospace;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>

    <h1 id="titulo-estado">Conectando...</h1>
    <div class="spinner" id="spinner"></div>

    <div id="log-container">
        <div>🚀 Iniciando sistema...</div>
    </div>

    <br>
    <div id="btn-volver" style="display:none;">
        <a href="form.php" class="btn btn-success btn-lg">Volver al Inicio</a>
    </div>

    <script>
        const logContainer = document.getElementById('log-container');
        const titulo = document.getElementById('titulo-estado');
        const spinner = document.getElementById('spinner');
        const btnVolver = document.getElementById('btn-volver');

        // Construimos la URL para llamar al mismo archivo en "Modo Stream"
        const url = `routes.php?modo_stream=1&tienda=<?= $tiendaJS ?>&fecha1=<?= $fechaJS ?>&corregir=<?= $corregirJS ?>`;

        async function iniciarProceso() {
            titulo.innerText = "Procesando Datos...";
            
            try {
                // Iniciamos la petición FETCH
                const response = await fetch(url);
                const reader = response.body.getReader();
                const decoder = new TextDecoder();

                // Leemos el flujo de datos mientras llega
                while (true) {
                    const { done, value } = await reader.read();
                    if (done) break;

                    // Decodificamos el pedazo de texto que llegó
                    const textChunk = decoder.decode(value, { stream: true });
                    
                    // Si detectamos el final
                    if (textChunk.includes("")) {
                        finalizar();
                    }

                    // Agregamos al HTML
                    const div = document.createElement('div');
                    div.innerHTML = textChunk.replace("", ""); 
                    logContainer.appendChild(div);
                    
                    // Auto-scroll hacia abajo
                    logContainer.scrollTop = logContainer.scrollHeight;
                }
                
                finalizar();

            } catch (error) {
                logContainer.innerHTML += `<div style='color:red; margin-top:20px'>❌ Error de conexión: ${error}</div>`;
                finalizar();
            }
        }

        function finalizar() {
            titulo.innerText = "PROCESO FINALIZADO";
            spinner.style.display = 'none'; // Ocultar spinner
            btnVolver.style.display = 'block'; // Mostrar botón
        }

        // Arrancamos apenas carga la página
        window.onload = iniciarProceso;
    </script>
</body>
</html>