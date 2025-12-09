<?php
// 1. Integración (Solo Log, sin Header visual)
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
    <style>
        :root {
            --bg-color: #242943;
            --card-bg: #2e3451;
            --accent-green: #00ff99;
            --accent-red: #ff5555;
            --accent-yellow: #ffd700;
            --text-main: #ffffff;
            --text-muted: #a0a0a0;
            --border-color: #3b4261;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .main-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Encabezado */
        .header-panel {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }

        .header-panel h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header-panel h1 b {
            font-weight: 700;
            color: var(--accent-green);
        }

        .back-btn {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 14px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            background: rgba(0,0,0,0.2);
            padding: 8px 15px;
            border-radius: 30px;
        }
        .back-btn:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }

        /* Tarjeta de Advertencia */
        .warning-card {
            background: rgba(255, 215, 0, 0.05);
            border-left: 4px solid var(--accent-yellow);
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .warning-icon {
            font-size: 30px;
        }

        .warning-text h3 {
            margin: 0 0 5px 0;
            color: var(--accent-yellow);
            font-size: 16px;
        }

        .warning-text p {
            margin: 0;
            font-size: 13px;
            color: #ddd;
            line-height: 1.5;
        }

        /* Tabla de Estilo */
        .table-wrapper {
            background: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: rgba(0,0,0,0.2);
            color: var(--accent-green);
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: rgba(255,255,255,0.02);
        }

        /* Checkbox Personalizado */
        .check-container {
            display: block;
            position: relative;
            padding-left: 0;
            cursor: pointer;
            user-select: none;
            display: flex;
            justify-content: center;
        }

        .check-custom {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--accent-green);
            transform: scale(1.2);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .badge-ok {
            background: rgba(0, 255, 153, 0.1);
            color: var(--accent-green);
            border: 1px solid rgba(0, 255, 153, 0.3);
        }

        .badge-fail {
            background: rgba(255, 85, 85, 0.1);
            color: var(--accent-red);
            border: 1px solid rgba(255, 85, 85, 0.3);
        }

        .dot {
            height: 6px;
            width: 6px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .dot-green { background-color: var(--accent-green); box-shadow: 0 0 5px var(--accent-green); }
        .dot-red { background-color: var(--accent-red); box-shadow: 0 0 5px var(--accent-red); }

        .info-sub {
            display: block;
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 3px;
            font-family: monospace;
        }

        /* Botón Maestro */
        .btn-master-container {
            margin-top: 30px;
            text-align: center;
        }

        .btn-master {
            background: transparent;
            color: var(--accent-yellow);
            border: 2px solid var(--accent-yellow);
            padding: 18px 40px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }

        .btn-master:hover:not(:disabled) {
            background: var(--accent-yellow);
            color: #000;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.4);
            transform: translateY(-2px);
        }

        .btn-master:disabled {
            border-color: #555;
            color: #555;
            cursor: not-allowed;
            opacity: 0.5;
        }
        
        /* Spinner de carga para las tiendas */
        .loading-pulse {
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { opacity: 0.5; }
            50% { opacity: 1; }
            100% { opacity: 0.5; }
        }

    </style>
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
                    2) Reinicializará <b>TODOS</b> los suscriptores. 
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
                        // Iteramos sobre la lista de réplicas
                        foreach ($lista_replicas as $key => $datos): 
                            
                            // Test de conexión rápido (1 segundo timeout para que cargue rápido la página)
                            $conectado = false;
                            $conn_info = array("Database"=>$datos['db'], "UID"=>$usr, "PWD"=>$pwd, "LoginTimeout"=>1);
                            
                            // Usamos @ para suprimir warnings visuales si falla la conexión
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