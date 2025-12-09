<?php
// 1. Integración
require '../../includes/log.php';

// 2. Carga de Configuración
$ruta_config = '../../services/adm/replica/config_replicas.php';
if (!file_exists($ruta_config)) $ruta_config = 'config_replicas.php';
include $ruta_config;

// Credenciales para el Test de Conexión
$usr = "mezcla";
$pwd = "Zeus33$";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consola de Réplicas</title>
    
    <link rel="stylesheet" href="assets/css/replica_panel.css">

</head>
<body>

    <div class="main-container">
        
        <div class="header-panel">
            <h1>Consola de <b>Replicación</b></h1>
            <a href="form.php" class="back-btn">← Volver al Reporte</a>
        </div>

        <div class="warning-card">
            <div class="warning-icon">⚠️</div>
            <div class="warning-text">
                <h3>MODO DE REINICIALIZACIÓN FORZADA</h3>
                <p>
                    Al ejecutar este proceso, el sistema: 1) Detectará automáticamente la publicación. 
                    2) Reinicializará <b>TODAS</b> las suscripciones. 
                    3) Generará una <b>NUEVA INSTANTÁNEA (Snapshot)</b>. 
                    Esta operación consume alto ancho de banda.
                </p>
            </div>
        </div>

        <form action="procesar_replica.php" method="POST" id="formReplica" onsubmit="return confirm('¿Estás SEGURO de procesar las tiendas seleccionadas?\n\nEsto generará tráfico de red intenso.');">
            
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th width="60" style="text-align: center;">
                                <input type="checkbox" id="checkAll" class="check-custom" onclick="toggleAll(this)">
                            </th>
                            <th>Tienda (Sede)</th>
                            <th>Detalles Técnicos</th>
                            <th>Estado VPN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $activas = 0;
                        foreach ($lista_replicas as $key => $datos): 
                            
                            // Test de conexión (1 seg timeout)
                            $conectado = false;
                            $conn_info = array("Database"=>$datos['db'], "UID"=>$usr, "PWD"=>$pwd, "LoginTimeout"=>1);
                            $conn = @sqlsrv_connect($datos['ip'], $conn_info);
                            
                            if ($conn) { 
                                $conectado = true; 
                                sqlsrv_close($conn); 
                                $activas++; 
                            }
                        ?>
                        <tr>
                            <td style="text-align:center;">
                                <div class="check-container">
                                    <?php if($conectado): ?>
                                        <input type="checkbox" name="tiendas[]" value="<?= $key ?>" class="check-custom item-check" checked>
                                    <?php else: ?>
                                        <input type="checkbox" disabled class="check-custom" style="opacity: 0.3; cursor: not-allowed;">
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <strong style="font-size:15px;"><?= $key ?></strong>
                            </td>
                            <td>
                                <span class="info-sub">IP: <?= $datos['ip'] ?></span>
                                <span class="info-sub" style="color: #6c757d;">DB: <?= $datos['db'] ?></span>
                            </td>
                            <td>
                                <?php if($conectado): ?>
                                    <span class="badge badge-ok"><span class="dot dot-green"></span> CONECTADO</span>
                                <?php else: ?>
                                    <span class="badge badge-fail"><span class="dot dot-red"></span> SIN CONEXIÓN</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($lista_replicas)): ?>
                            <tr><td colspan="4" style="text-align:center; padding: 30px; color: #ff5555;">No se encontró configuración de tiendas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="btn-master-container">
                <button type="submit" class="btn-master" id="btnProcesar" <?= ($activas == 0) ? 'disabled' : '' ?>>
                    <span>☢️</span>
                    <span>EJECUTAR REINICIO MASIVO (<?= $activas ?> TIENDAS)</span>
                </button>
            </div>

        </form>

    </div>

    <script>
    function toggleAll(source) {
        checkboxes = document.getElementsByClassName('item-check');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            if(!checkboxes[i].disabled) {
                checkboxes[i].checked = source.checked;
            }
        }
    }
    </script>

</body>
</html>