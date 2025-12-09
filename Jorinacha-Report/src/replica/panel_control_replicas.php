<?php 
// Asegúrate de que esta ruta sea correcta según donde guardaste el archivo config
require '../../includes/log.php';
include '../../services/adm/replica/config_replicas.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Control de Réplicas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #1a1d20; color: white; padding: 20px; font-family: 'Segoe UI', sans-serif; }
        
        .btn-replica {
            background-color: #000;
            color: #fff;
            border: 1px solid #444;
            padding: 15px;
            margin-bottom: 8px;
            text-align: left;
            width: 100%;
            text-transform: uppercase;
            font-weight: bold;
            transition: all 0.2s;
            display: flex; justify-content: space-between; align-items: center;
        }
        .btn-replica:hover {
            background-color: #222;
            color: #00ff99;
            border-color: #00ff99;
            cursor: pointer;
        }
        
        h3 { border-bottom: 2px solid #00ff99; padding-bottom: 10px; margin-bottom: 20px; }
        
        .back-btn { margin-bottom: 20px; display: inline-block; color: #aaa; text-decoration: none; }
        .back-btn:hover { color: white; }

        /* NUEVO ESTILO PARA QUE EL AMARILLO SE VEA BIEN */
        .alert-warning-custom {
            background-color: #2c2f33; /* Un gris un poco más claro que el fondo */
            color: #ffd700; /* AMARILLO ORO BRILLANTE */
            border: 1px solid #ffd700;
        }
    </style>
</head>
<body>

<div class="container" style="max-width: 700px;">
    
    <a href="form.php" class="back-btn">← Volver al Reporte Principal</a>

    <h3>🔄 Reiniciar Suscripciones (Modo VPN)</h3>
    
    <div class="alert alert-warning-custom">
        ⚠️ <b>ADVERTENCIA:</b> Al presionar "REINICIAR", la tienda borrará su base de datos local y descargará una copia nueva completa desde la central. <b>Hazlo solo si la réplica está rota.</b>
    </div>

    <?php foreach ($lista_replicas as $nombre_tienda => $datos): ?>
        <form action="procesar_replica.php" method="POST" onsubmit="return confirm('ATENCIÓN:\n\nVas a reiniciar la tienda: <?= $nombre_tienda ?>.\n\nEsto consumirá mucho ancho de banda.\n¿Estás 100% seguro?');">
            
            <input type="hidden" name="tienda_key" value="<?= $nombre_tienda ?>">
            
            <button type="submit" class="btn-replica">
                <div>
                    <span style="font-size: 1.1em;">🏢 <?= $nombre_tienda ?></span>
                    <br>
                    <small style="color:#888; font-weight:normal; text-transform:none;">
                        IP: <?= $datos['ip'] ?> | DB: <?= $datos['db'] ?>
                    </small>
                </div>
                <div style="text-align:right;">
                    <span style="font-size: 1.5em;">☢️</span><br>
                    <span style="font-size: 0.7em; color: #ff5555;">REINICIAR</span>
                </div>
            </button>
        </form>
    <?php endforeach; ?>
</div>

</body>
</html>