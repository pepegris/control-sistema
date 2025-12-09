<?php
// 1. Integración con tu sistema (Log y Header con estilos globales)
require '../../includes/log.php';
include '../../includes/header.php';

// 2. Cargar la configuración de las réplicas (Ruta especificada)
// Usamos require_once para asegurar que si falla, detenga el script y avise.
require_once '../../services/adm/replica/config_replicas.php';
?>

<style>
    .container-custom {
        max-width: 800px;
        margin: 0 auto;
        padding-top: 20px;
    }

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
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        text-decoration: none; /* Quitar subrayado si hereda de enlaces */
    }

    .btn-replica:hover {
        background-color: #222;
        color: #00ff99;
        border-color: #00ff99;
        cursor: pointer;
    }

    /* Clase personalizada para la alerta amarilla brillante */
    .alert-warning-custom {
        background-color: #2c2f33; 
        color: #ffd700; /* AMARILLO ORO */
        border: 1px solid #ffd700;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    /* Estilo para el título */
    .titulo-replica {
        color: white;
        border-bottom: 2px solid #00ff99;
        padding-bottom: 10px;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .back-btn {
        margin-bottom: 20px;
        display: inline-block;
        color: #ccc;
        text-decoration: none;
        font-weight: bold;
    }
    .back-btn:hover {
        color: #00ff99;
    }
</style>

<div id="body">
    <div class="container-custom">
        
        <a href="form.php" class="back-btn">← Volver al Reporte Principal</a>

        <h3 class="titulo-replica">🔄 Reiniciar Suscripciones (Modo VPN)</h3>

        <div class="alert-warning-custom">
            ⚠️ <b>ADVERTENCIA:</b> Al presionar "REINICIAR", la tienda borrará su base de datos local y descargará una copia nueva completa desde la central.<br>
            <b>Úsalo solo si la réplica está rota.</b>
        </div>

        <?php if (!isset($lista_replicas) || empty($lista_replicas)): ?>
            <div class="alert alert-danger">
                ❌ <b>ERROR:</b> No se cargó la lista de tiendas.<br>
                Verifica que el archivo en <code>../../services/adm/replica/config_replicas.php</code> exista y tenga el array <code>$lista_replicas</code>.
            </div>
        <?php else: ?>

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

        <?php endif; ?>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>