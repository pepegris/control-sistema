<?php
require '../../includes/log.php';

// --- LECTURA DE LA ÚLTIMA FECHA ---
$archivo_fecha = 'assets/ultima_fecha_precio.txt';
$fecha_por_defecto = date('Y-m-d'); 

if (file_exists($archivo_fecha)) {
    $contenido = file_get_contents($archivo_fecha);
    if (!empty($contenido)) $fecha_por_defecto = trim($contenido);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Precios</title>
    <link rel="stylesheet" href="assets/css/replica_panel.css">
</head>
<body>

    <div class="main-container">
        
        <div class="header-panel">
            <h1>Actualización de <b>Precios</b></h1>
            <a href="form.php" class="back-btn">← Volver al Menú</a>
        </div>

        <div class="warning-card" style="border-left-color: #00ff99; background: rgba(0, 255, 153, 0.05);">
            <div class="warning-icon">💲</div>
            <div class="warning-text">
                <h3 style="color:#00ff99;">ACTUALIZACIÓN MASIVA DE PRECIOS</h3>
                <p>
                    Se buscarán cambios de precio en <b>PREVIA_A</b> basados en la fecha seleccionada.
                    <br>Antes de procesar, verás una <b>lista preliminar</b> para confirmar.
                </p>
            </div>
        </div>

        <form action="previsualizar_precios.php" method="POST" style="background: var(--card-bg); padding: 40px; border-radius: 10px; border: 1px solid var(--border-color); max-width: 600px; margin: 0 auto;">
            
            <h3 style="margin-top:0; color:white; text-align:center;">Fecha de Corte (fec_prec_5)</h3>
            <p style="text-align:center; color:#aaa; font-size:0.9em;">
                Última actualización realizada: <b style="color:#00ff99;"><?= $fecha_por_defecto ?></b>
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <input type="date" name="fecha_inicio" required 
                       value="<?= $fecha_por_defecto ?>"
                       style="padding: 15px; font-size: 1.5em; border-radius: 5px; border: 2px solid #00ff99; background: #1a1d20; color: white; text-align: center;">
            </div>

            <div class="btn-master-container">
                <button type="submit" class="btn-master" style="border-color:#00ff99; color:#00ff99;">
                    <span>👁️</span>
                    <span>VERIFICAR CAMBIOS</span>
                </button>
            </div>

        </form>

    </div>

</body>
</html>