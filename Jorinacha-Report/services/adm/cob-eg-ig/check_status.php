<?php
// RUTA: ../../services/adm/cob-eg-ig/check_status.php
header('Content-Type: application/json');

$script = isset($_GET['script']) ? $_GET['script'] : ''; // 'backups' o 'restore'
$variant = isset($_GET['variant']) ? $_GET['variant'] : 'neo'; // 'neo' o 'full'

// --- CONFIGURACIÓN DE TOTALES (CORREGIDA) ---
// Neo = 18 bases de datos (Según tu script de restauración reducido)
// Full = 28 bases de datos (Total con las viejas)
$TOTAL_DBS_A_PROCESAR = ($variant == 'neo') ? 18 : 28; 

$RUTA_BACKUPS = "Z:/"; 

// 1. DEFINICIÓN DE SERVIDORES Y JOBS
$suffix = ($variant == 'neo') ? " neo" : ""; // Si es neo, el Job se llama "INTEGRACION ... neo"

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
        $archivos = glob($RUTA_BACKUPS . "*.BAK"); 
        $startTimestamp = $startTime ? $startTime->getTimestamp() : time();

        foreach ($archivos as $archivo) {
            // Contamos solo los modificados DESPUES de iniciar el job
            // Le damos 1 minuto de holgura por diferencias de reloj entre servidores
            if (filemtime($archivo) >= ($startTimestamp - 60)) {
                $items_procesados++;
            }
        }
        // Tope visual para que no pase del 100% si hay archivos basura
        if ($items_procesados > $TOTAL_DBS_A_PROCESAR) $items_procesados = $TOTAL_DBS_A_PROCESAR;
        
        $mensaje_estado = "Generados $items_procesados de $TOTAL_DBS_A_PROCESAR backups";
    } else {
        $items_procesados = 1; 
        $mensaje_estado = "Ejecutando en servidor...";
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