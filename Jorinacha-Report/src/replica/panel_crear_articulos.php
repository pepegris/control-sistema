<?php
require '../../includes/log.php';

// --- LÓGICA DE FECHA (TXT) ---
$archivo_fecha = 'assets/ultima_fecha.txt';
$fecha_por_defecto = date('Y-m-d'); // Por defecto hoy

if (file_exists($archivo_fecha)) {
    $contenido = file_get_contents($archivo_fecha);
    if (!empty($contenido)) {
        $fecha_por_defecto = trim($contenido);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Creación Masiva de Artículos</title>
    <link rel="stylesheet" href="assets/css/replica_panel.css">
    
    <style>
        #loadingOverlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.9); z-index: 9999;
            flex-direction: column; justify-content: center; align-items: center;
            backdrop-filter: blur(5px);
        }
        .spinner {
            width: 60px; height: 60px; border: 6px solid #333; border-top: 6px solid #ffd700;
            border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 20px;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .loading-text { color: white; font-size: 1.5em; font-weight: bold; text-transform: uppercase; font-family: sans-serif; }
        .loading-subtext { color: #aaa; margin-top: 10px; font-size: 0.9em; font-family: sans-serif; }
    </style>
</head>
<body>

    <div id="loadingOverlay">
        <div class="spinner"></div>
        <div class="loading-text">Procesando Datos...</div>
        <div class="loading-subtext">Por favor espere, conectando con 16 tiendas.</div>
    </div>

    <div class="main-container">
        
        <div class="header-panel">
            <h1>Creación de <b>Artículos</b></h1>
            <a href="form.php" class="back-btn">← Volver al Menú</a>
        </div>

        <div class="warning-card">
            <div class="warning-icon">📦</div>
            <div class="warning-text">
                <h3>DISTRIBUCIÓN DE ARTÍCULOS NUEVOS</h3>
                <p>
                    Este proceso buscará en <b>PREVIA_A</b> todos los artículos creados a partir de la fecha seleccionada 
                    y los creará automáticamente en las 16 tiendas.
                </p>
            </div>
        </div>

        <form id="formCrear" action="procesar_creacion.php" method="POST" style="background: var(--card-bg); padding: 40px; border-radius: 10px; border: 1px solid var(--border-color); max-width: 600px; margin: 0 auto;">
            
            <h3 style="margin-top:0; color:white; text-align:center;">Selecciona Fecha de Inicio</h3>
            <p style="text-align:center; color:#aaa; font-size:0.9em;">
                Última ejecución registrada: <b style="color:var(--accent-green);"><?= $fecha_por_defecto ?></b>
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <input type="date" name="fecha_inicio" required 
                       value="<?= $fecha_por_defecto ?>"
                       style="padding: 15px; font-size: 1.5em; border-radius: 5px; border: 2px solid var(--accent-green); background: #1a1d20; color: white; text-align: center;">
            </div>

            <div class="btn-master-container">
                <button type="submit" class="btn-master">
                    <span>🚀</span>
                    <span>BUSCAR Y REPLICAR</span>
                </button>
            </div>

        </form>

    </div>

    <script>
        document.getElementById('formCrear').addEventListener('submit', function() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        });
    </script>

</body>
</html>