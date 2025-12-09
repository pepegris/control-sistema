<?php
// 1. Integración
require '../../includes/log.php';
include '../../includes/header.php';

// 2. Carga de Configuración
$ruta_config = '../../services/adm/replica/config_replicas.php';
if (!file_exists($ruta_config)) $ruta_config = 'config_replicas.php';
include $ruta_config;

// Credenciales para el Test de Conexión
$usr = "mezcla";
$pwd = "Zeus33$";
?>

<style>
    .container-custom { max-width: 900px; margin: 0 auto; padding-top: 20px; }
    
    /* Estilos de Tabla */
    .table-dark-custom { width: 100%; border-collapse: collapse; background: #222; }
    .table-dark-custom th { background: #111; color: #00ff99; padding: 10px; text-transform: uppercase; border-bottom: 2px solid #00ff99; }
    .table-dark-custom td { padding: 10px; border-bottom: 1px solid #333; color: white; vertical-align: middle; }
    .table-dark-custom tr:hover { background: #2a2a2a; }

    /* Badges de Estado */
    .badge-ok { background: rgba(0, 255, 153, 0.2); color: #00ff99; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; border: 1px solid #00ff99; }
    .badge-fail { background: rgba(255, 85, 85, 0.2); color: #ff5555; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; border: 1px solid #ff5555; }

    /* Botón Maestro */
    .btn-master {
        background: linear-gradient(45deg, #000, #111);
        border: 2px solid #ffd700;
        color: #ffd700;
        width: 100%;
        padding: 20px;
        font-size: 1.2em;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-master:hover { background: #ffd700; color: #000; box-shadow: 0 0 15px #ffd700; }
    .btn-master:disabled { border-color: #555; color: #555; cursor: not-allowed; box-shadow: none; background: #222; }

    .check-custom { transform: scale(1.5); cursor: pointer; accent-color: #00ff99; }
    .back-btn { display: inline-block; color: #ccc; margin-bottom: 15px; text-decoration: none; }
    .back-btn:hover { color: #fff; }
</style>
<br><br><br><br>
<div id="body">
    <div class="container-custom">
        <a href="form.php" class="back-btn">← Volver al Reporte Principal</a>
        
        <h3 style="color:white; border-bottom:2px solid #00ff99; padding-bottom:10px;">
            🎛️ Consola de Reinicio Masivo
        </h3>

        <div style="background:#2c2f33; padding:15px; border-left:4px solid #ffd700; margin-bottom:20px; color:#fff;">
            ⚠️ <b>ATENCIÓN:</b> Selecciona las tiendas. El sistema:
            <ol style="margin-bottom:0; padding-left:20px; color:#ddd;">
                <li>Detectará automáticamente Publicación y Suscriptor.</li>
                <li>Reinicializará <b>TODAS</b> las suscripciones de esa tienda.</li>
                <li>Generará una <b>NUEVA INSTANTÁNEA</b> inmediatamente.</li>
            </ol>
        </div>

        <form action="procesar_replica.php" method="POST" id="formReplica" onsubmit="return confirm('¿Estás SEGURO de procesar las tiendas seleccionadas?\n\nEsto generará tráfico de red intenso.');">
            
            <table class="table-dark-custom">
                <thead>
                    <tr>
                        <th width="50"><input type="checkbox" id="checkAll" class="check-custom" onclick="toggleAll(this)"></th>
                        <th>Tienda</th>
                        <th>IP / Base de Datos</th>
                        <th>Estado Conexión</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $activas = 0;
                    foreach ($lista_replicas as $key => $datos): 
                        // Test de conexión rápido (2 segundos timeout)
                        $conectado = false;
                        $conn_info = array("Database"=>$datos['db'], "UID"=>$usr, "PWD"=>$pwd, "LoginTimeout"=>2);
                        $conn = sqlsrv_connect($datos['ip'], $conn_info);
                        
                        if ($conn) { $conectado = true; sqlsrv_close($conn); $activas++; }
                    ?>
                    <tr>
                        <td style="text-align:center;">
                            <?php if($conectado): ?>
                                <input type="checkbox" name="tiendas[]" value="<?= $key ?>" class="check-custom item-check" checked>
                            <?php else: ?>
                                <input type="checkbox" disabled class="check-custom" style="accent-color: #555;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <b><?= $key ?></b>
                        </td>
                        <td style="font-family:monospace; color:#aaa;">
                            <?= $datos['ip'] ?><br>
                            <span style="color:#666;"><?= $datos['db'] ?></span>
                        </td>
                        <td>
                            <?php if($conectado): ?>
                                <span class="badge-ok">● CONECTADO</span>
                            <?php else: ?>
                                <span class="badge-fail">● SIN CONEXIÓN</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn-master" id="btnProcesar" <?= ($activas == 0) ? 'disabled' : '' ?>>
                ☢️ EJECUTAR REINICIO MASIVO (<?= $activas ?>)
            </button>
        </form>
    </div>
</div>

<script>
function toggleAll(source) {
    checkboxes = document.getElementsByClassName('item-check');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>

<?php include '../../includes/footer.php'; ?>