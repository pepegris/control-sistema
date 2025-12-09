<?php
require '../../includes/log.php';

// Lógica de fecha (assets/ultima_fecha.txt)
$archivo_fecha = 'assets/ultima_fecha.txt';
$fecha_por_defecto = date('Y-m-d'); 

if (file_exists($archivo_fecha)) {
    $fecha_guardada = file_get_contents($archivo_fecha);
    if (!empty($fecha_guardada)) $fecha_por_defecto = trim($fecha_guardada);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Creación Masiva de Artículos</title>
    <link rel="stylesheet" href="assets/css/replica_panel.css">
</head>
<body>

    <div class="main-container">
        
        <div class="header-panel">
            <h1>Creación de <b>Artículos</b></h1>
            <a href="form.php" class="back-btn">← Volver al Menú</a>
        </div>

        <div class="warning-card">
            <div class="warning-icon">📦</div>
            <div class="warning-text">
                <h3>DISTRIBUCIÓN DE ARTÍCULOS NUEVOS</h3>
                <p>Se buscarán artículos en <b>PREVIA_A</b> desde la fecha indicada y se replicarán a las 16 tiendas.</p>
            </div>
        </div>

        <form action="procesar_creacion.php" method="POST" style="background: var(--card-bg); padding: 40px; border-radius: 10px; border: 1px solid var(--border-color); max-width: 600px; margin: 0 auto;">
            
            <h3 style="margin-top:0; color:white; text-align:center;">Selecciona Fecha de Inicio</h3>
            <p style="text-align:center; color:#aaa; font-size:0.9em;">
                Última fecha procesada: <b style="color:var(--accent-green);"><?= $fecha_por_defecto ?></b>
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

    <?php include 'includes/loading_overlay.php'; ?>

</body>
</html>