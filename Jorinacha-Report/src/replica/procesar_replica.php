<?php
// procesar_replica.php
// Aumentamos tiempo de ejecución para evitar timeout en conexiones lentas
ini_set('max_execution_time', 600); 

require '../../includes/log.php';

// --- CARGA DE CONFIGURACIÓN INTELIGENTE ---
$ruta_config = '../../services/adm/replica/config_replicas.php';
// Si no lo encuentra en la ruta larga, busca en la carpeta actual
if (!file_exists($ruta_config)) {
    $ruta_config = 'config_replicas.php';
}
include $ruta_config;

// --- CREDENCIALES ---
$usuario_admin = "mezcla";
$clave_admin   = "Zeus33$";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $tienda_key = $_POST['tienda_key'];
    
    // Validar que la tienda exista en el array
    if (!isset($lista_replicas[$tienda_key])) {
        die("<h3 style='color:red'>Error: La tienda '$tienda_key' no existe en la configuración.</h3>");
    }

    $datos = $lista_replicas[$tienda_key];
    $ip_destino  = $datos['ip'];
    $db_destino  = $datos['db'];

    // 1. CONEXIÓN A LA TIENDA (PUBLICADOR)
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
                <p>No se pudo conectar a <b>$tienda_key</b> ($ip_destino).</p>
                <a href='panel_control_replicas.php' style='color:white;'>Volver</a>
             </div>");
    }

    // --- FASE 1: DETECTAR NOMBRE REAL DE LA PUBLICACIÓN ---
    // Consultamos la tabla sysmergepublications para obtener el nombre exacto (con espacios si los tiene)
    $sql_detect_pub = "SELECT TOP 1 name FROM sysmergepublications";
    $stmt_pub = sqlsrv_query($conn_remota, $sql_detect_pub);
    
    $publicacion_real = null;
    if ($stmt_pub && $row = sqlsrv_fetch_array($stmt_pub, SQLSRV_FETCH_ASSOC)) {
        $publicacion_real = $row['name']; 
    }

    if (!$publicacion_real) {
        die("<div style='background:black; color:white; padding:20px;'>
                <h2 style='color:red;'>❌ Error Fatal</h2>
                <p>No se encontró ninguna publicación de mezcla en la base de datos <b>$db_destino</b>.</p>
             </div>");
    }

    // --- FASE 2: REINICIALIZAR TODAS LAS SUSCRIPCIONES ---
    // Usamos @subscriber = 'all' para que reinicie a la Madre sin importar cómo se llame (SRVPREV, SQL2K8, IP, etc.)
    $sql_reinit = "EXEC sp_reinitmergesubscription 
                   @publication = ?, 
                   @subscriber = 'all', 
                   @upload_first = 'FALSE'"; 

    $stmt_reinit = sqlsrv_query($conn_remota, $sql_reinit, array($publicacion_real));

    if (!$stmt_reinit) {
        // Si falla el reinicio, mostramos error
        echo "<div style='background:black; color:white; padding:20px;'>";
        echo "<h2 style='color:red;'>❌ Error al marcar Reinicialización</h2>";
        if (($errors = sqlsrv_errors()) != null) {
            foreach ($errors as $error) echo "<p>" . $error['message'] . "</p>";
        }
        echo "</div>";
        exit;
    }

    // --- FASE 3: GENERAR NUEVA INSTANTÁNEA (SNAPSHOT) ---
    // Este comando inicia el JOB del agente de snapshot
    $sql_snapshot = "EXEC sp_startpublication_snapshot @publication = ?";
    $stmt_snapshot = sqlsrv_query($conn_remota, $sql_snapshot, array($publicacion_real));

    // --- LÓGICA DE CORRECCIÓN PARA EL MENSAJE ---
    $mensaje_servidor = "";
    $es_exito = false;

    if ($stmt_snapshot) {
        // Si PHP dice que pasó directo, es éxito
        $es_exito = true;
    } else {
        // Si PHP capturó una respuesta de texto, SQL Server lo marca como "error" de datos,
        // pero debemos leer el texto para ver si dice "inició correctamente".
        if (($errors = sqlsrv_errors()) != null) {
            foreach ($errors as $error) {
                $msg = $error['message'];
                $mensaje_servidor .= $msg . "<br>";
                
                // Buscamos palabras clave de éxito en español o inglés
                // "inici" cubre "inició" e "inicio" (por si hay problemas de tildes)
                if (stripos($msg, 'inici') !== false && stripos($msg, 'correctamente') !== false) {
                    $es_exito = true;
                }
                if (stripos($msg, 'started') !== false && stripos($msg, 'successfully') !== false) {
                    $es_exito = true;
                }
            }
        }
    }

    // --- MOSTRAR RESULTADO ---
    if ($es_exito) {
        // PANTALLA VERDE
        echo "
        <div style='background:#111; color:#fff; padding:40px; text-align:center; font-family:Segoe UI, sans-serif; height:100vh;'>
            <h1 style='color:#00ff99; font-size: 60px; margin-bottom:10px;'>✅ PROCESO INICIADO</h1>
            <h2 style='color:#ccc;'>Tienda: $tienda_key</h2>
            <hr style='border:1px solid #333; width:50%; margin: 20px auto;'>
            
            <div style='text-align:left; display:inline-block; background:#222; padding:20px; border-radius:10px; border: 1px solid #444;'>
                <p style='margin-bottom:5px;'><b>Resumen de Acciones:</b></p>
                <ul style='color:#ccc;'>
                    <li>Publicación detectada: <b>'$publicacion_real'</b></li>
                    <li>Suscripciones reinicializadas: <b>TODAS ('all')</b></li>
                    <li>Generación de Instantánea: <b>INICIADA</b></li>
                </ul>
                <div style='background:#1a1a1a; padding:10px; border-radius:5px; margin-top:10px;'>
                    <span style='color:#00ff99; font-size:0.9em;'>Respuesta del Servidor:<br> $mensaje_servidor</span>
                </div>
            </div>
            
            <p style='color:#ffff99; margin-top:30px; font-size: 1.1em;'>
                ⏳ <b>Atención:</b> La tienda está generando los archivos de la nueva base de datos.<br>
                Este proceso puede tardar varios minutos. Espera antes de sincronizar.
            </p>
            
            <br><br>
            <a href='panel_control_replicas.php' style='background:#0066cc; color:white; padding:15px 30px; text-decoration:none; border-radius:5px; font-weight:bold;'>
                Volver al Panel
            </a>
        </div>";
    } else {
        // PANTALLA ROJA (Error Real)
        echo "<div style='font-family:sans-serif; padding:40px; background:#222; color:white; height:100vh;'>";
        echo "<h1 style='color: #ff5555;'>❌ Error al iniciar Snapshot</h1>";
        echo "<p style='color:#ccc;'>La reinicialización se marcó, pero falló el arranque del Agente de Snapshot.</p>";
        echo "<div style='background:#333; padding:20px; border-left:4px solid #ff5555; margin:20px 0;'>
                $mensaje_servidor
              </div>";
        echo "<br><a href='panel_control_replicas.php' style='color:white; text-decoration:underline;'>Volver al Panel</a></div>";
    }
    
    sqlsrv_close($conn_remota);
}
?>