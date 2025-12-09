<?php
// procesar_replica.php
// Aumentamos tiempo de ejecución para conexiones lentas
ini_set('max_execution_time', 300); 

// 1. Log de sistema
require '../../includes/log.php';

// 2. Configuración de Réplicas (Ruta directa fija)
include '../../services/adm/replica/config_replicas.php';

// --- CREDENCIALES ---
$usuario_admin = "mezcla";
$clave_admin   = "Zeus33$";

// --- IDENTIFICADOR SUSCRIPTOR (BASE MADRE) ---
// SOLUCIÓN: Usamos el nombre 'SQL2K8' en lugar de la IP.
$nombre_servidor_madre = 'SQL2K8'; 
// ---------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $tienda_key = $_POST['tienda_key'];
    
    // Validación básica
    if (!isset($lista_replicas) || !isset($lista_replicas[$tienda_key])) {
        die("<h3>Error:</h3> <p>No se cargó la configuración de la tienda <b>$tienda_key</b>.</p>");
    }

    $datos = $lista_replicas[$tienda_key];
    
    $ip_destino  = $datos['ip'];
    $db_destino  = $datos['db'];
    $publicacion = $datos['publicacion'];

    // 1. CONEXIÓN A LA TIENDA (Usando IP VPN)
    $connectionInfo = array(
        "Database" => $db_destino, 
        "UID" => $usuario_admin, 
        "PWD" => $clave_admin,
        "LoginTimeout" => 15
    );

    $conn_remota = sqlsrv_connect($ip_destino, $connectionInfo);

    if (!$conn_remota) {
        die("<div style='background:black; color:white; padding:20px; font-family:sans-serif;'>
                <h2 style='color:red;'>❌ Error de Conexión</h2>
                <p>No se pudo conectar a la tienda <b>$tienda_key</b>.</p>
                <p>IP: $ip_destino | BD: $db_destino</p>
                <br><a href='panel_control_replicas.php' style='color:white;'>Volver</a>
             </div>");
    }

    // 2. EJECUTAR REINICIO
    // Ahora sí: "El suscriptor SQL2K8 solicita reinicio"
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
            <h2 style='color:#ccc;'>Tienda: $tienda_key</h2>
            <hr style='border:1px solid #333; width:50%; margin: 20px auto;'>
            
            <p style='font-size:1.2em;'>
                Publicación <b>$publicacion</b> marcada para reinicio.
            </p>
            <p style='color:#ffff99;'>
                Suscriptor reconocido: <b>$nombre_servidor_madre</b>
            </p>
            
            <br><br>
            <a href='panel_control_replicas.php' style='background:#0066cc; color:white; padding:15px 30px; text-decoration:none; border-radius:5px; font-weight:bold;'>
                Volver al Panel
            </a>
        </div>";
    } else {
        // --- ERROR ---
        echo "<div style='font-family:sans-serif; padding:40px; background:#222; color:white; height:100vh;'>";
        echo "<h1 style='color: #ff5555;'>❌ Error SQL</h1>";
        
        if (($errors = sqlsrv_errors()) != null) {
            foreach ($errors as $error) {
                echo "<p style='color:#ffaaaa; border-bottom:1px solid #444; padding-bottom:10px;'>";
                echo "Mensaje: " . $error['message'];
                echo "</p>";
            }
        }
        echo "<br><a href='panel_control_replicas.php' style='background:#555; color:white; padding:10px 20px; text-decoration:none;'>← Volver</a>";
        echo "</div>";
    }
    
    sqlsrv_close($conn_remota);
}
?>