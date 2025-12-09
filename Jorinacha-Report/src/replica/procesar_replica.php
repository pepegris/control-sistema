<?php
// procesar_replica.php
ini_set('max_execution_time', 300); 

require '../../includes/log.php';
// Carga de configuración (Búsqueda automática de la ruta)
$ruta_config = '../../services/adm/replica/config_replicas.php';
if (!file_exists($ruta_config)) $ruta_config = 'config_replicas.php';
include $ruta_config;

// --- CREDENCIALES ---
$usuario_admin = "mezcla";
$clave_admin   = "Zeus33$";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $tienda_key = $_POST['tienda_key'];
    
    if (!isset($lista_replicas[$tienda_key])) die("Error: Tienda no configurada.");

    $datos = $lista_replicas[$tienda_key];
    $ip_destino  = $datos['ip'];
    $db_destino  = $datos['db'];
    $publicacion = $datos['publicacion'];

    // 1. CONEXIÓN A LA TIENDA
    $connectionInfo = array("Database" => $db_destino, "UID" => $usuario_admin, "PWD" => $clave_admin, "LoginTimeout" => 15);
    $conn_remota = sqlsrv_connect($ip_destino, $connectionInfo);

    if (!$conn_remota) {
        die("<div style='background:black; color:white; padding:20px;'>
                <h2 style='color:red;'>❌ Error de Conexión</h2>
                <p>No se pudo conectar a $tienda_key ($ip_destino).</p>
                <a href='panel_control_replicas.php' style='color:white;'>Volver</a>
             </div>");
    }

    // 2. PASO MÁGICO: DETECTAR EL NOMBRE DEL SUSCRIPTOR AUTOMÁTICAMENTE
    // Consultamos a la tienda quién está suscrito a esa base de datos
    $sql_detect = "SELECT TOP 1 subscriber_server FROM sysmergesubscriptions WHERE db_name = '$db_destino'";
    $stmt_detect = sqlsrv_query($conn_remota, $sql_detect);
    
    $suscriptor_detectado = null;
    if ($stmt_detect && $row = sqlsrv_fetch_array($stmt_detect, SQLSRV_FETCH_ASSOC)) {
        $suscriptor_detectado = $row['subscriber_server'];
    }

    // Si no encontramos suscriptor, usamos 'SQL2K8' como último recurso
    if (!$suscriptor_detectado) {
        $suscriptor_detectado = 'SQL2K8'; 
    }

    // 3. EJECUTAR REINICIO CON EL NOMBRE CORRECTO
    $sql = "EXEC sp_reinitmergesubscription 
            @publication = ?, 
            @subscriber = ?, 
            @upload_first = 'FALSE'"; 

    $params = array($publicacion, $suscriptor_detectado);

    $stmt = sqlsrv_query($conn_remota, $sql, $params);

    if ($stmt) {
        // --- ÉXITO ---
        echo "
        <div style='background:#111; color:#fff; padding:40px; text-align:center; font-family:Segoe UI, sans-serif; height:100vh;'>
            <h1 style='color:#00ff99; font-size: 60px;'>✅ ÉXITO</h1>
            <h2 style='color:#ccc;'>Tienda: $tienda_key</h2>
            <hr style='border:1px solid #333; width:50%; margin: 20px auto;'>
            <p style='font-size:1.2em;'>Se ha marcado la publicación <b>$publicacion</b> para reinicio.</p>
            <p style='color:#ffff99;'>Suscriptor detectado y usado: <b>$suscriptor_detectado</b></p>
            <br><a href='panel_control_replicas.php' style='background:#0066cc; color:white; padding:15px 30px; text-decoration:none; border-radius:5px;'>Volver</a>
        </div>";
    } else {
        // --- ERROR ---
        echo "<div style='font-family:sans-serif; padding:40px; background:#222; color:white; height:100vh;'>";
        echo "<h1 style='color: #ff5555;'>❌ Error SQL</h1>";
        echo "<p>Intentamos usar el suscriptor: <b>$suscriptor_detectado</b></p>";
        if (($errors = sqlsrv_errors()) != null) {
            foreach ($errors as $error) echo "<p style='color:#ffaaaa; border-bottom:1px solid #444;'>Mensaje: " . $error['message'] . "</p>";
        }
        echo "<br><a href='panel_control_replicas.php' style='color:white;'>Volver</a></div>";
    }
    sqlsrv_close($conn_remota);
}
?>