<?php
// procesar_replica.php
// Aumentamos tiempo de ejecución
ini_set('max_execution_time', 300); 

require '../../includes/log.php';

// Carga de configuración
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

    // --- FASE DE DETECCIÓN INTELIGENTE ---

    // A) Detectar el NOMBRE EXACTO de la Publicación
    $sql_detect_pub = "SELECT TOP 1 name FROM sysmergepublications";
    $stmt_pub = sqlsrv_query($conn_remota, $sql_detect_pub);
    
    $publicacion_real = null;
    if ($stmt_pub && $row = sqlsrv_fetch_array($stmt_pub, SQLSRV_FETCH_ASSOC)) {
        $publicacion_real = $row['name']; // Esto trae el nombre con espacios si los tiene
    }

    if (!$publicacion_real) {
        die("<div style='background:black; color:white; padding:20px;'>
                <h2 style='color:red;'>❌ Error Fatal</h2>
                <p>No se encontró ninguna publicación de mezcla en la base de datos <b>$db_destino</b>.</p>
                <p>Verifica que sea la base de datos correcta.</p>
                <a href='panel_control_replicas.php' style='color:white;'>Volver</a>
             </div>");
    }

    // B) Detectar el NOMBRE EXACTO del Suscriptor para esa publicación
    $sql_detect_sub = "SELECT TOP 1 subscriber_server 
                       FROM sysmergesubscriptions 
                       WHERE pubid = (SELECT pubid FROM sysmergepublications WHERE name = ?)";
    
    $stmt_sub = sqlsrv_query($conn_remota, $sql_detect_sub, array($publicacion_real));
    
    $suscriptor_real = null;
    if ($stmt_sub && $row = sqlsrv_fetch_array($stmt_sub, SQLSRV_FETCH_ASSOC)) {
        $suscriptor_real = $row['subscriber_server'];
    }

    // Fallback si no encuentra suscriptor (raro, pero por seguridad)
    if (!$suscriptor_real) {
        $suscriptor_real = 'SQL2K8'; 
        echo "<script>alert('Advertencia: No se detectó suscriptor en la tabla. Usando SQL2K8 por defecto.');</script>";
    }

    // -------------------------------------

    // 3. EJECUTAR REINICIO CON LOS DATOS REALES ENCONTRADOS
    $sql = "EXEC sp_reinitmergesubscription 
            @publication = ?, 
            @subscriber = ?, 
            @upload_first = 'FALSE'"; 

    // IMPORTANTE: Pasamos los valores detectados, no los del config
    $params = array($publicacion_real, $suscriptor_real);

    $stmt = sqlsrv_query($conn_remota, $sql, $params);

    if ($stmt) {
        // --- ÉXITO ---
        echo "
        <div style='background:#111; color:#fff; padding:40px; text-align:center; font-family:Segoe UI, sans-serif; height:100vh;'>
            <h1 style='color:#00ff99; font-size: 60px;'>✅ ÉXITO</h1>
            <h2 style='color:#ccc;'>Tienda: $tienda_key</h2>
            <hr style='border:1px solid #333; width:50%; margin: 20px auto;'>
            
            <div style='text-align:left; display:inline-block; background:#222; padding:20px; border-radius:10px;'>
                <p>Datos detectados automáticamente en la BD remota:</p>
                <ul>
                    <li>Publicación Real: <b>'$publicacion_real'</b></li>
                    <li>Suscriptor Real: <b>'$suscriptor_real'</b></li>
                </ul>
            </div>
            
            <br><br>
            <p style='color:#ffff99;'>Reinicio programado correctamente.</p>
            <br>
            <a href='panel_control_replicas.php' style='background:#0066cc; color:white; padding:15px 30px; text-decoration:none; border-radius:5px;'>Volver</a>
        </div>";
    } else {
        // --- ERROR ---
        echo "<div style='font-family:sans-serif; padding:40px; background:#222; color:white; height:100vh;'>";
        echo "<h1 style='color: #ff5555;'>❌ Error SQL</h1>";
        echo "<p>Datos usados:</p>";
        echo "<ul><li>Pub: '$publicacion_real'</li><li>Sub: '$suscriptor_real'</li></ul>";
        
        if (($errors = sqlsrv_errors()) != null) {
            foreach ($errors as $error) echo "<p style='color:#ffaaaa; border-bottom:1px solid #444;'>Mensaje: " . $error['message'] . "</p>";
        }
        echo "<br><a href='panel_control_replicas.php' style='color:white;'>Volver</a></div>";
    }
    
    sqlsrv_close($conn_remota);
}
?>