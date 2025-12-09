<?php
// procesar_replica.php
// Aumentamos tiempo de ejecución
ini_set('max_execution_time', 300); 

include '../../services/adm/replica/config_replicas.php';

// --- CONFIGURACIÓN CRÍTICA ---
$usuario_admin = "mezcla";
$clave_admin   = "Zeus33$";

// AQUÍ ESTÁ EL CAMBIO: Usamos la IP que indicaste como identificador del Suscriptor
$nombre_servidor_madre = '172.16.1.39'; 
// Si esto falla, intenta cambiarlo nuevamente por 'SRVPREV' o el nombre del equipo.
// -----------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $tienda_key = $_POST['tienda_key'];
    
    if (!isset($lista_replicas[$tienda_key])) die("Tienda no válida");

    $datos = $lista_replicas[$tienda_key];
    
    $ip_destino  = $datos['ip'];
    $db_destino  = $datos['db'];
    $publicacion = $datos['publicacion'];

    // 1. CONEXIÓN DIRECTA A LA TIENDA (PUBLICADOR)
    $connectionInfo = array(
        "Database" => $db_destino, 
        "UID" => $usuario_admin, 
        "PWD" => $clave_admin,
        "LoginTimeout" => 15 // Damos 15 seg para conectar
    );

    // Conectamos a la IP de la VPN de la tienda
    $conn_remota = sqlsrv_connect($ip_destino, $connectionInfo);

    if (!$conn_remota) {
        die("<div style='background:black; color:white; padding:20px; font-family:sans-serif;'>
                <h2 style='color:red;'>❌ Error de Conexión</h2>
                <p>No se pudo conectar a la tienda <b>$tienda_key</b>.</p>
                <p>IP Intentada: $ip_destino</p>
                <p>Base de Datos: $db_destino</p>
             </div>");
    }

    // 2. EJECUTAR EL COMANDO DE REINICIO
    // Le decimos a la tienda: "El suscriptor (172.16.1.39) quiere bajar todo de nuevo"
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
            <p style='color:#aaa; font-size:0.9em;'>
                La próxima sincronización descargará la base de datos completa.
            </p>
            
            <br><br>
            <a href='panel_control_replicas.php' style='background:#0066cc; color:white; padding:15px 30px; text-decoration:none; border-radius:5px; font-weight:bold;'>
                Volver al Panel
            </a>
        </div>";
    } else {
        // --- ERROR ---
        echo "<div style='font-family:sans-serif; padding:20px; background:#222; color:white;'>";
        echo "<h1 style='color: #ff5555;'>❌ Error al ejecutar el Comando</h1>";
        
        // Diagnóstico de error común
        echo "<div style='background:#333; padding:15px; border-left:4px solid #ff5555;'>";
        echo "<h3>Posible Causa: Nombre del Suscriptor incorrecto</h3>";
        echo "<p>Intentamos usar la IP <b>'$nombre_servidor_madre'</b> como nombre del suscriptor.</p>";
        echo "<p>Si la tienda conoce a la madre por nombre (ej. SRVPREV), SQL rechazará la IP.</p>";
        echo "</div><br>";
        
        echo "<h3>Detalle Técnico del Error SQL:</h3>";
        if (($errors = sqlsrv_errors()) != null) {
            foreach ($errors as $error) {
                echo "SQL Code: " . $error['code'] . "<br>";
                echo "Mensaje: <b style='color:#ffaaaa'>" . $error['message'] . "</b><br><hr>";
            }
        }
        echo "<br><a href='panel_control_replicas.php' style='color:white;'>Volver</a>";
        echo "</div>";
    }
    
    sqlsrv_close($conn_remota);
}
?>