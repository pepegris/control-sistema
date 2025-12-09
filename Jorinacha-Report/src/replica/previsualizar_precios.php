<?php
require '../../includes/log.php';
require '../../services/db_connection.php'; 

if (!isset($_POST['fecha_inicio'])) {
    header("Location: panel_actualizar_precios.php");
    exit;
}

$fecha_raw = $_POST['fecha_inicio'];
$fecha_profit = date("Ymd", strtotime($fecha_raw)); // Formato YYYYMMDD

// Conectar a PREVIA_A para mostrar qué se va a actualizar
$conn_local = ConectarSQLServer('PREVIA_A');
if (!$conn_local) die("Error conectando a PREVIA_A");

// CONSULTA SQL
$sql = "SELECT co_art, art_des, 
        CONVERT(numeric(10,2), prec_vta5) as p5, 
        CONVERT(numeric(10,2), prec_vta4) as p4, 
        campo4 
        FROM art WHERE fec_prec_5 >= '$fecha_profit'";

$stmt = sqlsrv_query($conn_local, $sql);
$articulos = [];
if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $articulos[] = $row;
    }
}
sqlsrv_close($conn_local);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Previsualizar Precios</title>
    <link rel="stylesheet" href="assets/css/replica_panel.css">
    <style>
        .preview-box {
            background: #111;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 0;
            max-height: 500px; /* Scroll vertical */
            overflow-y: auto;
            margin-bottom: 20px;
        }
        .table-preview { width: 100%; border-collapse: collapse; font-size: 0.9em; }
        .table-preview th { position: sticky; top: 0; background: #222; z-index: 10; color: #00ff99; padding: 10px; text-align: left; }
        .table-preview td { padding: 8px; border-bottom: 1px solid #333; color: #ddd; }
        .table-preview tr:hover { background: #1a1a1a; }
        .price-col { color: #ffd700; font-weight: bold; text-align: right; }
    </style>
</head>
<body>

    <div class="main-container">
        <div class="header-panel">
            <h1>Verificar <b>Cambios</b></h1>
            <a href="panel_actualizar_precios.php" class="back-btn">← Cancelar</a>
        </div>

        <?php if (count($articulos) > 0): ?>
            
            <div class="warning-card" style="border-left-color: #ffd700;">
                <div class="warning-icon">⚠️</div>
                <div class="warning-text">
                    <h3>CONFIRMACIÓN REQUERIDA</h3>
                    <p>Se han encontrado <b><?= count($articulos) ?></b> artículos con cambios de precio desde el <?= $fecha_raw ?>.</p>
                </div>
            </div>

            <div class="preview-box">
                <table class="table-preview">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th style="text-align:right">Precio 5 ($)</th>
                            <th style="text-align:right">Precio 4</th>
                            <th>Campo 4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($articulos as $art): ?>
                        <tr>
                            <td style="font-family:monospace; color:#00ff99;"><?= $art['co_art'] ?></td>
                            <td><?= $art['art_des'] ?></td>
                            <td class="price-col"><?= $art['p5'] ?></td>
                            <td class="price-col"><?= $art['p4'] ?></td>
                            <td style="color:#aaa;"><?= $art['campo4'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <form id="formConfirmar" action="procesar_precios.php" method="POST">
                <input type="hidden" name="fecha_inicio" value="<?= $fecha_raw ?>">
                
                <div class="btn-master-container">
                    <button type="submit" class="btn-master" style="border-color:#ffd700; color:#ffd700;">
                        <span>🚀</span>
                        <span>CONFIRMAR Y ACTUALIZAR 16 TIENDAS</span>
                    </button>
                </div>
            </form>

        <?php else: ?>
            <div class="warning-card">
                <h3>No hay cambios</h3>
                <p>No se encontraron precios modificados desde la fecha <?= $fecha_raw ?>.</p>
            </div>
            <a href="panel_actualizar_precios.php" class="btn-master" style="text-decoration:none;">Volver</a>
        <?php endif; ?>

    </div>

    <?php include 'includes/loading_overlay.php'; ?>
    <script>
        document.getElementById('formConfirmar').addEventListener('submit', function() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        });
    </script>

</body>
</html>