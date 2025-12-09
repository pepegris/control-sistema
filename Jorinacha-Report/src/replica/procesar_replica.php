<?php
// procesar_replica.php
// Aumentamos tiempo de ejecución (importante para conexiones lentas)
ini_set('max_execution_time', 300); 

// --- 1. CARGA DE CONFIGURACIÓN INTELIGENTE ---
// Intentamos cargar el config. Si no está en la ruta larga, buscamos en la misma carpeta.
$ruta_config_servicios = '../../services/adm/replica/config_replicas.php';
$ruta_config_local     = 'config_replicas.php';

if (file_exists($ruta_config_servicios)) {
    include $ruta_config_servicios;
} elseif (file_exists($ruta_config_local)) {
    include $ruta_config_local;
} else {
    die("<div style='background:red; color:white; padding:20px;'>
            <h3>❌ Error Crítico</h3>
            <p>No se encuentra el archivo <b>config_replicas.php</b>.</p>
            <p>Verifica que esté en <code>$ruta_config_servicios</code> o en la misma carpeta de este archivo.</p>
         </div>");
}
// ---------------------------------------------

// --- CONFIGURACIÓN DE CREDENCIALES ---
$usuario_admin = "mezcla";
$clave_admin   = "Zeus33$";

// IDENTIFICADOR DEL SUSCRIPTOR (BASE MADRE)
// IMPORTANTE: Si SQL Server fue instalado usando el nombre de la PC (Ej: SRVPREV),
// usar la IP aquí podría dar error. Si falla, cambia esto por 'SRVPREV'.
$nombre_servidor_madre = '172.16.1.39'; 
// -------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $tienda_key = $_POST['tienda_key'];
    
    // Verificamos que la tienda exista en el array cargado
    if (!isset($lista_replicas[$tienda_key])) {
        die("Error: La tienda '$tienda_key' no existe en config_replicas.php");
    }

    $datos = $lista_replicas[$tienda_key];
    
    $ip_destino  = $datos['ip'];
    $db_destino  = $datos['db'];
    $publicacion = $datos['publicacion']; // Ej: ACARIGUA

    // 1. CONEXIÓN DIRECTA A LA TIENDA (PUBLICADOR)
    $connectionInfo = array(
        "Database" => $db_destino, 
        "UID" => $usuario_admin, 
        "PWD" => $clave_admin,
        "LoginTimeout" => 15 // 15 segundos para intentar conectar
    );

    // Conectamos usando la IP de la VPN
    $conn_remota = sqlsrv_connect($ip_destino, $connectionInfo);

    if (!$conn_remota) {
        die("<div style='background:black; color:white; padding:20px; font-family:sans-serif;'>
                <h2 style='color:red;'>❌ Error de Conexión</h2>
                <p>No se pudo conectar a la tienda <b>$tienda_key</b>.</p>
                <p>IP Intentada: <b>$ip_destino</b></p>
                <p>Base de Datos: $db_destino</p>
                <hr>
                <a href='panel_control_replicas.php' style='color:white;'>Volver</a>
             </div>");
    }

    // 2. EJECUTAR EL COMANDO DE REINICIO
    // @upload_first = 'FALSE' -> Borra cambios locales pendientes y fuerza bajada nueva
    $sql = "EXEC sp_reinitmergesubscription 
            @publication = ?, 
            @subscriber = ?, 
            @upload_first = 'FALSE'"; 

    $params = array($publicacion, $nombre_servidor_madre);

    $stmt = sqlsrv_query($conn_remota, $sql, $params);

    if ($stmt) {
        // --- ÉXITO ---
        echo "
        <div style='background:#111; color:#fff; padding:40px; text-align:center; font-family:Segoe UI, sans-serif; height:100vh;'>
            <h1 style='color:#00ff99; font-size: 60px; margin-bottom:10px;'>✅ ÉXITO</h1>
            <h2 style='color:#ccc;'>Orden recibida en: $tienda_key</h2>
            <hr style='border:1px solid #333; width:50%; margin: 20px auto;'>
            
            <p style='font-size:1.2em;'>
                Se ha marcado la publicación <b>$publicacion</b> para reinicializar.
            </p>
            <p style='color:#ffff99;'>
                Suscriptor identificado como: <b>$nombre_servidor_madre</b>
            </p>
            <div style='background:#222; padding:15px; margin-top:20px; border-radius:5px;'>
                <p style='color:#aaa; font-size:0.9em; margin:0;'>
                    ℹ️ <b>Nota:</b> El proceso comenzará automáticamente la próxima vez que el Agente de Sincronización se ejecute en el servidor.
                </p>
            </div>
            
            <br><br>
            <a href='panel_control_replicas.php' style='background:#0066cc; color:white; padding:15px 30px; text-decoration:none; border-radius:5px; font-weight:bold;'>
                Volver al Panel
            </a>
        </div>";
    } else {
        // --- ERROR ---
        echo "<div style='font-family:sans-serif; padding:40px; background:#222; color:white; height:100vh;'>";
        echo "<h1 style='color: #ff5555;'>❌ Error al ejecutar el Comando</h1>";
        echo "<h3 style='color: #ccc;'>Conectamos a la tienda, pero el SP falló.</h3>";
        
        // Diagnóstico de error común
        echo "<div style='background:#333; padding:20px; border-left:5px solid #ff5555; margin: 20px 0;'>";
        echo "<h4 style='margin-top:0;'>💡 Posible Causa: Nombre del Suscriptor</h4>";
        echo "<p>El script envió la IP <b>'$nombre_servidor_madre'</b> como nombre del suscriptor.</p>";
        echo "<p>Si la réplica fue configurada usando el nombre del servidor (ej. <code>SRVPREV</code>), SQL Server rechazará la IP porque no coincide con el registro.</p>";
        echo "<p><b>Solución:</b> Edita este archivo y cambia <code>\$nombre_servidor_madre</code> por el nombre real del servidor.</p>";
        echo "</div>";
        
        echo "<h3>Detalle Técnico del Error SQL:</h3>";
        if (($errors = sqlsrv_errors()) != null) {
            foreach ($errors as $error) {
                echo "<div style='background:black; padding:10px; margin-bottom:5px; font-family:monospace;'>";
                echo "Code: " . $error['code'] . "<br>";
                echo "Message: <span style='color:#ffaaaa'>" . $error['message'] . "</span>";
                echo "</div>";
            }
        }
        echo "<br><br><a href='panel_control_replicas.php' style='background:#555; color:white; padding:10px 20px; text-decoration:none;'>← Volver</a>";
        echo "</div>";
    }
    
    sqlsrv_close($conn_remota);
}
?>