<?php
// Aumentamos tiempo
ini_set('max_execution_time', 600); 

require '../../includes/log.php';
require '../../services/db_connection.php'; 

// Configuración y Credenciales
$ruta_config = '../../services/adm/replica/config_replicas.php';
if (!file_exists($ruta_config)) $ruta_config = 'config_replicas.php';
include $ruta_config;

$usr_remoto = 'mezcla';
$pwd_remoto = 'Zeus33$';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesando Precios</title>
    <link rel="stylesheet" href="assets/css/replica_panel.css">
    <link rel="stylesheet" href="assets/css/replica_procesar.css">
    <style>
        .st-warning { background: rgba(255, 215, 0, 0.15); color: var(--accent-yellow); border: 1px solid var(--accent-yellow); }
    </style>
</head>
<body>

<div class="main-container report-container">
    <div class="header-panel">
        <h1>Actualizando <b>Precios</b></h1>
    </div>

    <?php
    if (!isset($_POST['fecha_inicio'])) {
        die("<div class='warning-card'><h3>Error</h3><p>Faltan datos.</p></div>");
    }

    $fecha_raw = $_POST['fecha_inicio']; 
    $fecha_profit = date("Ymd", strtotime($fecha_raw)); 
    
    // --- 1. OBTENER DATOS DE PREVIA_A ---
    echo "<div class='log-card' style='border-color:#ffd700;'><h3 style='color:#ffd700'>📥 Obteniendo datos maestros...</h3>";
    
    $conn_local = ConectarSQLServer('PREVIA_A'); 
    if (!$conn_local) die("❌ Error conectando a PREVIA_A Local.");

    // Consulta de Precios
    $sql = "SELECT co_art, art_des, 
            CONVERT(numeric(10,2), prec_vta5) as p5, 
            CONVERT(numeric(10,2), prec_vta4) as p4, 
            campo4 
            FROM art WHERE fec_prec_5 >= '$fecha_profit'";
            
    $stmt = sqlsrv_query($conn_local, $sql);
    $datos_actualizar = [];
    if($stmt) {
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $datos_actualizar[] = $row;
        }
    }
    sqlsrv_close($conn_local);

    echo "Se encontraron <b>" . count($datos_actualizar) . "</b> artículos para actualizar.</div>";

    if (empty($datos_actualizar)) {
        // Guardamos fecha hoy aunque no haya nada
        file_put_contents('assets/ultima_fecha_precio.txt', date('Y-m-d'));
        echo "<a href='panel_actualizar_precios.php' class='btn-return'>Volver</a>";
        exit;
    }

    // --- 2. RECORRER TIENDAS ---
    foreach ($lista_replicas as $tienda => $config) {
        
        $log_msg = "";
        $modo_offline = false; 
        $err_vpn = "";

        // Limpieza de variables
        $nombre_db_vpn = trim($config['db']);
        $nombre_db_local = trim($config['db_local']);
        $ip_vpn = trim($config['ip']);

        // A) Intentar VPN
        $connInfo = array("Database" => $nombre_db_vpn, "UID" => $usr_remoto, "PWD" => $pwd_remoto, "LoginTimeout" => 20);
        $conn_destino = @sqlsrv_connect($ip_vpn, $connInfo);

        // B) Fallback Local
        if (!$conn_destino) {
            if(($e=sqlsrv_errors())!=null) $err_vpn = $e[0]['message'];
            $modo_offline = true;
            $conn_destino = ConectarSQLServer($nombre_db_local);
        }

        if (!$conn_destino) {
            echo_card($tienda, "FAIL", "❌ No conecta ni remoto ni local.", true);
            continue;
        }

        // --- C) EJECUTAR UPDATES ---
        sqlsrv_begin_transaction($conn_destino);
        $updates_ok = 0;

        try {
            foreach ($datos_actualizar as $item) {
                // Preparar valores
                $co_art = $item['co_art'];
                $p5 = $item['p5'];
                $p4 = $item['p4'];
                $c4 = str_replace("'", "''", $item['campo4']); // Escapar comillas en campo4

                // QUERY UPDATE
                // Usamos co_art como llave principal.
                $sql_upd = "UPDATE art SET 
                            prec_vta5 = $p5, 
                            prec_vta4 = $p4, 
                            campo4 = '$c4' 
                            WHERE co_art = '$co_art'";
                
                $stmt_upd = sqlsrv_query($conn_destino, $sql_upd);
                if (!$stmt_upd) throw new Exception("Fallo update en art: $co_art");
                
                // Contar solo si hubo cambios reales (opcional, pero sqlsrv_rows_affected puede dar -1 en updates masivos)
                $updates_ok++; 
            }

            sqlsrv_commit($conn_destino);
            $log_msg = "✔ $updates_ok Precios actualizados.";

            if ($modo_offline) {
                $err_short = substr($err_vpn, 0, 100);
                echo_card($tienda, "WARNING", $log_msg . "<br>⚠️ <b>Guardado en Local ($nombre_db_local).</b><br><small style='color:#ff9999'>VPN: $err_short</small>", false);
            } else {
                echo_card($tienda, "OK", $log_msg . "<br><small style='color:#666'>BD Remota: $nombre_db_vpn</small>", false);
            }

        } catch (Exception $e) {
            sqlsrv_rollback($conn_destino);
            echo_card($tienda, "FAIL", "Error: " . $e->getMessage(), true);
        }

        sqlsrv_close($conn_destino);
        flush(); ob_flush(); 
    }

    // --- 3. GUARDAR FECHA DE HOY EN TXT ---
    $ruta_txt = __DIR__ . '/assets/ultima_fecha_precio.txt';
    if(file_put_contents($ruta_txt, date('Y-m-d')) === false) {
        echo "<div style='color:red; background:black; padding:10px;'>❌ No se pudo guardar la fecha en el TXT (Permisos).</div>";
    }

    function echo_card($tienda, $status, $log, $is_error) {
        if ($status == "OK") { $badge_class = "st-ok"; $badge_text = "ACTUALIZADO"; $icon = "✅"; }
        elseif ($status == "WARNING") { $badge_class = "st-warning"; $badge_text = "GUARDADO LOCAL"; $icon = "🟠"; }
        else { $badge_class = "st-fail"; $badge_text = "ERROR"; $icon = "❌"; }
        
        echo "<div class='log-card'>";
        echo "  <div class='log-header'><h3>$icon $tienda</h3><span class='status-badge $badge_class'>$badge_text</span></div>";
        echo "  <div class='console-output'>$log</div>";
        echo "</div>";
    }
    ?>
    <a href="panel_actualizar_precios.php" class="btn-return">← Volver al Panel</a>
</div>
</body>
</html>