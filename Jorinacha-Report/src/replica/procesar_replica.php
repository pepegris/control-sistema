<?php
// Aumentamos tiempo de ejecución drásticamente (10 minutos)
ini_set('max_execution_time', 600); 

require '../../includes/log.php';
// Carga de configuración
$ruta_config = '../../services/adm/replica/config_replicas.php';
if (!file_exists($ruta_config)) $ruta_config = 'config_replicas.php';
include $ruta_config;

// Credenciales
$usuario_admin = "mezcla";
$clave_admin   = "Zeus33$";

// Estilos embebidos para el reporte
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body { background: #1a1d20; color: white; font-family: 'Segoe UI', sans-serif; padding: 20px; }
        .log-container { max-width: 900px; margin: 0 auto; }
        .card-result { 
            background: #222; border: 1px solid #444; border-radius: 8px; 
            margin-bottom: 15px; padding: 15px; display: flex; flex-direction: column;
        }
        .card-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 10px; margin-bottom: 10px; }
        .status-ok { color: #00ff99; font-weight: bold; border: 1px solid #00ff99; padding: 5px 10px; border-radius: 4px; }
        .status-fail { color: #ff5555; font-weight: bold; border: 1px solid #ff5555; padding: 5px 10px; border-radius: 4px; }
        .log-detail { font-family: monospace; font-size: 0.9em; color: #ccc; background: #111; padding: 10px; border-radius: 4px; }
        .manual-msg { color: #ffd700; margin-top: 5px; font-weight: bold; }
        h2 { color: white; border-bottom: 2px solid #00ff99; padding-bottom: 10px; }
        .btn-back { display: block; width: 100%; text-align: center; background: #0066cc; color: white; padding: 15px; text-decoration: none; font-weight: bold; border-radius: 5px; margin-top: 30px; }
        .btn-back:hover { background: #0055aa; }
    </style>
</head>
<body>

<div class="log-container">
    <h2>📜 Reporte de Reinicialización Masiva</h2>

    <?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['tiendas'])) {
        echo "<div class='status-fail'>No se seleccionaron tiendas.</div>";
        echo "<a href='panel_control_replicas.php' class='btn-back'>Volver</a>";
        exit;
    }

    $tiendas_seleccionadas = $_POST['tiendas'];

    foreach ($tiendas_seleccionadas as $tienda_key) {
        
        // Inicializar variables de estado para esta vuelta
        $status_icon = "⏳";
        $status_class = "";
        $log_buffer = "";
        $error_critico = false;
        
        // Verificar config
        if (!isset($lista_replicas[$tienda_key])) {
            echo_card($tienda_key, "FAIL", "Configuración no encontrada", true);
            continue;
        }

        $datos = $lista_replicas[$tienda_key];
        $ip = $datos['ip'];
        $db = $datos['db'];

        // 1. CONEXIÓN
        $connInfo = array("Database"=>$db, "UID"=>$usuario_admin, "PWD"=>$clave_admin, "LoginTimeout"=>10);
        $conn = sqlsrv_connect($ip, $connInfo);

        if (!$conn) {
            echo_card($tienda_key, "FAIL", "❌ No se pudo conectar a la IP: $ip", true);
            continue;
        }

        // 2. DETECCIÓN AUTOMÁTICA (INTELIGENCIA)
        // A) Publicación
        $pub_real = null;
        $sql_pub = "SELECT TOP 1 name FROM sysmergepublications";
        $stmt_pub = sqlsrv_query($conn, $sql_pub);
        if ($stmt_pub && $row = sqlsrv_fetch_array($stmt_pub, SQLSRV_FETCH_ASSOC)) $pub_real = $row['name'];

        if (!$pub_real) {
            echo_card($tienda_key, "FAIL", "❌ No se encontró Publicación de Mezcla en la BD.", true);
            sqlsrv_close($conn);
            continue;
        }

        // B) Suscriptor
        $sub_real = null;
        $sql_sub = "SELECT TOP 1 subscriber_server FROM sysmergesubscriptions WHERE pubid = (SELECT pubid FROM sysmergepublications WHERE name = ?)";
        $stmt_sub = sqlsrv_query($conn, $sql_sub, array($pub_real));
        if ($stmt_sub && $row = sqlsrv_fetch_array($stmt_sub, SQLSRV_FETCH_ASSOC)) $sub_real = $row['subscriber_server'];
        
        // Fallback
        if (!$sub_real) $sub_real = 'SQL2K8';

        $log_buffer .= "✔ Datos detectados: Pub=['$pub_real'] | Sub=['$sub_real']<br>";

        // 3. REINICIALIZAR (TODOS)
        $sql_reinit = "EXEC sp_reinitmergesubscription @publication = ?, @subscriber = 'all', @upload_first = 'FALSE'";
        $stmt_reinit = sqlsrv_query($conn, $sql_reinit, array($pub_real));

        if (!$stmt_reinit) {
            $msg_err = "";
            if (($e = sqlsrv_errors()) != null) foreach($e as $err) $msg_err .= $err['message'];
            echo_card($tienda_key, "FAIL", "❌ Falló reinicio: $msg_err", true);
            sqlsrv_close($conn);
            continue;
        }
        $log_buffer .= "✔ Suscripciones marcadas para reinicio.<br>";

        // 4. SNAPSHOT (NUEVA INSTANTÁNEA)
        $sql_snap = "EXEC sp_startpublication_snapshot @publication = ?";
        $stmt_snap = sqlsrv_query($conn, $sql_snap, array($pub_real));

        // Lógica de "Falso Positivo" (Tu corrección)
        $snap_success = false;
        $snap_msg = "";
        
        if ($stmt_snap) {
            $snap_success = true;
        } else {
            if (($errors = sqlsrv_errors()) != null) {
                foreach ($errors as $error) {
                    $m = $error['message'];
                    $snap_msg .= $m . " ";
                    if (stripos($m, 'inici') !== false && stripos($m, 'correctamente') !== false) $snap_success = true;
                    if (stripos($m, 'started') !== false && stripos($m, 'successfully') !== false) $snap_success = true;
                }
            }
        }

        if ($snap_success) {
            $log_buffer .= "✔ Snapshot iniciado: " . substr($snap_msg, 0, 100) . "...<br>";
            echo_card($tienda_key, "OK", $log_buffer, false);
        } else {
            // Falló el snapshot
            $log_buffer .= "❌ Error Snapshot: $snap_msg";
            echo_card($tienda_key, "FAIL", $log_buffer, true);
        }

        sqlsrv_close($conn);
        
        // Forzar envío al navegador para ver progreso real
        flush();
        ob_flush();
    }

    // FUNCIÓN AUXILIAR PARA DIBUJAR LAS TARJETAS
    function echo_card($tienda, $status, $log, $is_manual) {
        $color_class = ($status == "OK") ? "status-ok" : "status-fail";
        $icon = ($status == "OK") ? "✅ ÉXITO" : "❌ FALLÓ";
        
        echo "<div class='card-result'>";
        echo "  <div class='card-header'>";
        echo "      <h3 style='margin:0'>$tienda</h3>";
        echo "      <span class='$color_class'>$icon</span>";
        echo "  </div>";
        echo "  <div class='log-detail'>$log</div>";
        
        if ($is_manual) {
            echo "  <div class='manual-msg'>⚠️ Acción Requerida: Debes realizar el proceso MANUALMENTE en esta tienda.</div>";
        } else {
            echo "  <div style='color:#00ff99; margin-top:5px; font-size:0.9em;'>⏳ Generando instantánea en segundo plano...</div>";
        }
        echo "</div>";
    }
    ?>

    <a href="panel_control_replicas.php" class="btn-back">Volver al Panel</a>
</div>

</body>
</html>