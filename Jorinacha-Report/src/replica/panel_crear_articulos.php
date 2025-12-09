<?php
require '../../includes/log.php';
// No cargamos config_replicas aquí, lo cargará el procesador
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
                <p>
                    Este proceso buscará en <b>PREVIA_A</b> todos los artículos, líneas, colores y categorías creados a partir de la fecha seleccionada 
                    y los creará automáticamente en las 16 tiendas remotas si no existen.
                </p>
            </div>
        </div>

        <form action="procesar_creacion.php" method="POST" style="background: var(--card-bg); padding: 40px; border-radius: 10px; border: 1px solid var(--border-color); max-width: 600px; margin: 0 auto;">
            
            <h3 style="margin-top:0; color:white; text-align:center;">Selecciona Fecha de Inicio</h3>
            <p style="text-align:center; color:#aaa; font-size:0.9em;">Se buscarán artículos creados DESDE esta fecha:</p>

            <div style="text-align: center; margin: 30px 0;">
                <input type="date" name="fecha_inicio" required 
                       value="<?= date('Y-m-d') ?>"
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

</body>
</html>