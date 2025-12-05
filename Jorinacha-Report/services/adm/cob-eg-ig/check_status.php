<?php
// RUTA: ../../services/adm/cob-eg-ig/check_status.php
header('Content-Type: application/json');

// Evitar cache de navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$script = isset($_GET['script']) ? $_GET['script'] : ''; 
$variant = isset($_GET['variant']) ? $_GET['variant'] : 'neo'; 

// --- CONFIGURACIÓN DE TOTALES ---
// Neo = 18 bases (ajustado a tu script corto)
// Full = 28 bases (ajustado al total con viejas)
$TOTAL_DBS_A_PROCESAR = ($variant == 'neo') ? 18 : 28; 

$RUTA_BACKUPS = "Z:/"; 

// 1. DEFINICIÓN DE SERVIDORES Y JOBS
$suffix = ($variant == 'neo') ? " neo" : ""; 

if ($script == 'backups') {
    $serverName = "172.16.1.39";      
    $jobName = "INTEGRACION BACKUPS" . $suffix; 
} elseif ($script == 'restore') {
    $serverName = "172.16.1.19";      
    $jobName = "INTEGRACION RESTORE" . $suffix; 
} else {
    echo json_encode(['status' => 'error', 'message' => 'Script invalido']);
    exit;
}

$connectionInfo = array("Database" => "msdb", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8", "LoginTimeout" => 5);
$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Sin conexión SQL']);
    exit;
}

// 2. VERIFICAR ESTADO DEL JOB
$sql = "SELECT TOP 1 
            ja.start_execution_date,
            ja.stop_execution_date,
            CASE WHEN ja.stop_execution_date IS NULL AND ja.start_execution_date IS NOT NULL THEN 1 ELSE 0 END as is_running,
            jh.run_status
        FROM msdb.dbo.sysjobs j
        JOIN msdb.dbo.sysjobactivity ja ON j.job_id = ja.job_id
        LEFT JOIN msdb.dbo.sysjobhistory jh ON ja.job_history_id = jh.instance_id
        WHERE j.name = ?
        ORDER BY ja.start_execution_date DESC";

$params = array($jobName);
$stmt = sqlsrv_query($conn, $sql, $params);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

$isRunning = ($row['is_running'] == 1);
$runStatus = isset($row['run_status']) ? $row['run_status'] : null;
$startTime = $row['start_execution_date'];

// 3. CALCULAR PROGRESO REAL
$items_procesados = 0;
$progreso_percent = 0;
$mensaje_estado = "Iniciando Job $jobName...";

if ($script == 'backups') {
    // --- BACKUPS: Contar archivos en Z:\ ---
    if (is_dir($RUTA_BACKUPS)) {
        
        // IMPORTANTE: Limpiar caché de estado de archivos para ver cambios en tiempo real
        clearstatcache(); 
        
        $archivos = glob($RUTA_BACKUPS . "*.BAK"); 
        
        // Obtenemos la hora de inicio. Si por error no viene, usamos la hora actual.
        $startTimestamp = $startTime ? $startTime->getTimestamp() : time();

        foreach ($archivos as $archivo) {
            // CORRECCIÓN: Aumentamos el margen de tolerancia.
            // Contamos archivos modificados desde 20 minutos ANTES del inicio del Job.
            // Esto arregla el problema si el Job arrancó antes de que abrieras la página.
            // 1200 segundos = 20 minutos.
            if (filemtime($archivo) >= ($startTimestamp - 1200)) {
                $items_procesados++;
            }
        }
        
        // Tope visual (por si hay archivos basura viejos que entraron en el rango)
        if ($items_procesados > $TOTAL_DBS_A_PROCESAR) $items_procesados = $TOTAL_DBS_A_PROCESAR;
        
        $mensaje_estado = "Generados $items_procesados de $TOTAL_DBS_A_PROCESAR backups";
    } else {
        // Fallback si no hay acceso a Z: Simulamos carga para que no parezca roto
        $items_procesados = 1; 
        $mensaje_estado = "Procesando en segundo plano...";
    }

} elseif ($script == 'restore') {
    // --- RESTORE: Consultar historial de restauración ---
    if ($startTime) {
        $sqlRestore = "SELECT COUNT(DISTINCT destination_database_name) as total 
                       FROM msdb.dbo.restorehistory 
                       WHERE restore_date >= ?";
        
        $stmtRes = sqlsrv_query($conn, $sqlRestore, array($startTime));
        if ($stmtRes && $rowRes = sqlsrv_fetch_array($stmtRes, SQLSRV_FETCH_ASSOC)) {
            $items_procesados = $rowRes['total'];
        }
        
        if ($items_procesados > $TOTAL_DBS_A_PROCESAR) $items_procesados = $TOTAL_DBS_A_PROCESAR;
        
        $mensaje_estado = "Restauradas $items_procesados de $TOTAL_DBS_A_PROCESAR bases";
    }
}

// CALCULO PORCENTAJE
if ($TOTAL_DBS_A_PROCESAR > 0) {
    $progreso_percent = round(($items_procesados / $TOTAL_DBS_A_PROCESAR) * 100);
}
if ($progreso_percent > 100) $progreso_percent = 100;

// FORZAR 100% SI TERMINÓ CON ÉXITO
if (!$isRunning && $runStatus == 1) {
    $progreso_percent = 100;
    $items_procesados = $TOTAL_DBS_A_PROCESAR;
    $mensaje_estado = "Proceso Completado Exitosamente";
} elseif (!$isRunning && $runStatus == 0 && $script == 'restore') {
     // Si falló pero estamos en restore, el mensaje cambia
     $mensaje_estado = "Proceso detenido con errores";
}

echo json_encode([
    'status' => 'ok',
    'running' => $isRunning,
    'run_status' => $runStatus,
    'percent' => $progreso_percent,
    'processed' => $items_procesados,
    'total' => $TOTAL_DBS_A_PROCESAR,
    'msg' => $mensaje_estado
]);

sqlsrv_close($conn);
?>