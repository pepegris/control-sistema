<?php
// procesar_replica.php
ini_set('max_execution_time', 600); // Damos más tiempo por si acaso

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

    // --- FASE 1: DETECTAR NOMBRE DE PUBLICACIÓN ---
    $sql_detect_pub = "SELECT TOP 1 name FROM sysmergepublications";
    $stmt_pub = sqlsrv_query($conn_remota, $sql_detect_pub);
    
    $publicacion_real = null;
    if ($stmt_pub && $row = sqlsrv_fetch_array($stmt_pub, SQLSRV_FETCH_ASSOC)) {
        $publicacion_real = $row['name']; 
    }

    if (!$publicacion_real) {
        die("<h2 style='color:red;'>❌ Error: No se encontró publicación en $db_destino</h2>");
    }

    // --- FASE 2: REINICIALIZAR TODAS LAS SUSCRIPCIONES ---
    // Usamos 'all' para reinicializar a todos los suscriptores de esta publicación
    $sql_reinit = "EXEC sp_reinitmergesubscription 
                   @publication = ?, 
                   @subscriber = 'all', 
                   @upload_first = 'FALSE'"; 

    $stmt_reinit = sqlsrv_query($conn_remota, $sql_reinit, array($publicacion_real));

    if (!$stmt_reinit) {
        die("<h2 style='color:red;'>❌ Error al Marcar Reinicialización</h2>");
    }

    // --- FASE 3: GENERAR NUEVA INSTANTÁNEA AHORA ---
    // Este comando despierta al Agente de Instantáneas inmediatamente
    $sql_snapshot = "EXEC sp_startpublication_snapshot @publication = ?";
    $stmt_snapshot = sqlsrv_query($conn_remota, $sql_snapshot, array($publicacion_real));

    if ($stmt_snapshot) {
        // --- ÉXITO ---
        echo "
        <div style='background:#111; color:#fff; padding:40px; text-align:center; font-family:Segoe UI, sans-serif; height:100vh;'>
            <h1 style='color:#00ff99; font-size: 60px;'>✅ PROCESO INICIADO</h1>
            <h2 style='color:#ccc;'>Tienda: $tienda_key</h2>
            <hr style='border:1px solid #333; width:50%; margin: 20px auto;'>
            
            <div style='text-align:left; display:inline-block; background:#222; padding:20px; border-radius:10px;'>
                <p><b>Acciones Ejecutadas:</b></p>
                <ul>
                    <li>Publicación: <b>'$publicacion_real'</b></li>
                    <li>Reinicialización: <b>TODOS LOS SUSCRIPTORES ('all')</b></li>
                    <li>Generación de Instantánea: <b>SOLICITADA (Iniciando Agente...)</b></li>
                </ul>
            </div>
            
            <p style='color:#ffff99; margin-top:20px;'>
                ⏳ El servidor remoto está generando la nueva instantánea en este momento.<br>
                Esto puede tardar varios minutos dependiendo del tamaño de la base de datos.
            </p>
            
            <br><br>
            <a href='panel_control_replicas.php' style='background:#0066cc; color:white; padding:15px 30px; text-decoration:none; border-radius:5px;'>Volver</a>
        </div>";
    } else {
        // --- ERROR ---
        echo "<div style='font-family:sans-serif; padding:40px; background:#222; color:white;'>";
        echo "<h1 style='color: #ff5555;'>❌ Error al iniciar Snapshot</h1>";
        if (($errors = sqlsrv_errors()) != null) {
            foreach ($errors as $error) echo "<p style='color:#ffaaaa;'>" . $error['message'] . "</p>";
        }
        echo "<br><a href='panel_control_replicas.php' style='color:white;'>Volver</a></div>";
    }
    
    sqlsrv_close($conn_remota);
}
?>