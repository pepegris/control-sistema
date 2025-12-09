<?php
// procesar_replica.php

// 1. SEGURIDAD
require '../../includes/security.php';

// Aumentamos tiempo de ejecución...
ini_set('max_execution_time', 600); 

require '../../includes/log.php';

// Carga de configuración
$ruta_config = '../../services/adm/replica/config_replicas.php';
if (!file_exists($ruta_config)) $ruta_config = 'config_replicas.php';
include $ruta_config;

// Credenciales
$usuario_admin = "mezcla";
$clave_admin   = "Zeus33$";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Proceso</title>
    
    <link rel="stylesheet" href="assets/css/replica_panel.css">
    <link rel="stylesheet" href="assets/css/replica_procesar.css">

</head>
<body>

<div class="main-container report-container">
    
    <div class="header-panel">
        <h1>Reporte de <b>Ejecución</b></h1>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['tiendas'])) {
        echo "<div class='warning-card'><div class='warning-text'><h3>No hay selección</h3><p>No se seleccionaron tiendas para procesar.</p></div></div>";
        echo "<a href='panel_control_replicas.php' class='back-btn'>Volver</a>";
        exit;
    }

    $tiendas_seleccionadas = $_POST['tiendas'];

    foreach ($tiendas_seleccionadas as $tienda_key) {
        
        // Variables para el log
        $log_buffer = "";
        
        // Verificar config
        if (!isset($lista_replicas[$tienda_key])) {
            echo_card($tienda_key, "FAIL", "Configuración no encontrada en el array.", true);
            continue;
        }

        $datos = $lista_replicas[$tienda_key];
        $ip = $datos['ip'];
        $db = $datos['db'];

        // 1. CONEXIÓN
        $connInfo = array("Database"=>$db, "UID"=>$usuario_admin, "PWD"=>$clave_admin, "LoginTimeout"=>10);
        $conn = sqlsrv_connect($ip, $connInfo);

        if (!$conn) {
            echo_card($tienda_key, "FAIL", "❌ Error de Conexión a la IP: $ip", true);
            continue;
        }

        // 2. DETECCIÓN AUTOMÁTICA
        // A) Publicación
        $pub_real = null;
        $sql_pub = "SELECT TOP 1 name FROM sysmergepublications";
        $stmt_pub = sqlsrv_query($conn, $sql_pub);
        if ($stmt_pub && $row = sqlsrv_fetch_array($stmt_pub, SQLSRV_FETCH_ASSOC)) $pub_real = $row['name'];

        if (!$pub_real) {
            echo_card($tienda_key, "FAIL", "❌ No se encontró Publicación de Mezcla en la BD remota.", true);
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

        $log_buffer .= "<span style='color:#a0a0a0;'>Detectado:</span> Pub=[<b style='color:white'>$pub_real</b>] | Sub=[<b style='color:white'>$sub_real</b>]<br>";

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
        $log_buffer .= "<span style='color:var(--accent-green);'>✔ Reinicialización marcada para 'all' suscriptores.</span><br>";

        // 4. SNAPSHOT (NUEVA INSTANTÁNEA)
        $sql_snap = "EXEC sp_startpublication_snapshot @publication = ?";
        $stmt_snap = sqlsrv_query($conn, $sql_snap, array($pub_real));

        // Lógica de validación de mensaje
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
            $log_buffer .= "<span style='color:var(--accent-green);'>✔ Snapshot iniciado correctamente.</span><br>";
            $log_buffer .= "<span style='color:#666; font-size:0.8em;'>Resp: " . substr($snap_msg, 0, 80) . "...</span>";
            echo_card($tienda_key, "OK", $log_buffer, false);
        } else {
            $log_buffer .= "<span style='color:var(--accent-red);'>❌ Error Snapshot:</span> $snap_msg";
            echo_card($tienda_key, "FAIL", $log_buffer, true);
        }

        sqlsrv_close($conn);
        
        // Forzar envío al navegador
        flush();
        ob_flush();
    }

    // FUNCIÓN PARA DIBUJAR LAS TARJETAS (ESTILIZADA)
    function echo_card($tienda, $status, $log, $is_manual) {
        $badge_class = ($status == "OK") ? "st-ok" : "st-fail";
        $badge_text = ($status == "OK") ? "EXITOSO" : "FALLIDO";
        $icon = ($status == "OK") ? "🏢" : "⚠️";
        
        echo "<div class='log-card'>";
        echo "  <div class='log-header'>";
        echo "      <h3>$icon $tienda</h3>";
        echo "      <span class='status-badge $badge_class'>$badge_text</span>";
        echo "  </div>";
        echo "  <div class='console-output'>$log</div>";
        
        if ($is_manual) {
            echo "  <div style='margin-top:10px; color:var(--accent-yellow); font-size:13px; font-weight:bold;'>⚠️ Acción Requerida: Proceso Manual necesario.</div>";
        } else {
            echo "  <div style='margin-top:10px; color:var(--accent-green); font-size:13px; display:flex; align-items:center; gap:10px;'>
                        <span class='loading-pulse' style='width:8px; height:8px; background:var(--accent-green); border-radius:50%; display:inline-block;'></span> 
                        Generando instantánea en segundo plano...
                    </div>";
        }
        echo "</div>";
    }
    ?>

    <a href="panel_control_replicas.php" class="btn-return">← Volver al Panel de Control</a>

</div>

</body>
</html>