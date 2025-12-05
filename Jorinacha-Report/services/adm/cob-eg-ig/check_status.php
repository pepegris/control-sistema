<?php
// RUTA: ../../services/adm/cob-eg-ig/check_status.php
header('Content-Type: application/json');

// --- CONFIGURACIÓN ---
// Lista de bases de datos que procesas (Basado en tu script SQL anterior)
// Es importante que este número sea cercano a la realidad para que la barra sea precisa.
$TOTAL_DBS_A_PROCESAR = 28; 
$RUTA_BACKUPS = "Z:/"; // Asegurate que PHP tenga permiso de lectura aquí

$script = isset($_GET['script']) ? $_GET['script'] : '';

// 1. DEFINICIÓN DE SERVIDORES
if ($script == 'backups') {
    $serverName = "172.16.1.39";      
    $jobName = "INTEGRACION BACKUPS"; 
} elseif ($script == 'restore') {
    $serverName = "172.16.1.19";      
    $jobName = "INTEGRACION RESTORE"; 
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
$mensaje_estado = "Iniciando...";

if ($script == 'backups') {
    // --- LÓGICA BACKUPS (LEYENDO ARCHIVOS EN Z:\) ---
    // Buscamos archivos .BAK modificados desde que arrancó el Job
    
    if (is_dir($RUTA_BACKUPS)) {
        $archivos = glob($RUTA_BACKUPS . "*.BAK"); // O *.bak dependiendo del sistema
        $contador = 0;
        $startTimestamp = $startTime ? $startTime->getTimestamp() : time(); // Hora inicio Job

        foreach ($archivos as $archivo) {
            // Si la fecha de modificación del archivo es MAYOR a la hora de inicio del Job
            if (filemtime($archivo) >= $startTimestamp) {
                $contador++;
            }
        }
        $items_procesados = $contador;
        $mensaje_estado = "Generados $items_procesados de $TOTAL_DBS_A_PROCESAR backups";
    } else {
        $mensaje_estado = "No se puede leer Z:/";
        // Si no lee Z, simulamos un 10% para que sepa que corre
        $items_procesados = 1; 
    }

} elseif ($script == 'restore') {
    // --- LÓGICA RESTORE (CONSULTANDO SQL HISTORY) ---
    // Contamos cuántas DB se han restaurado desde la hora de inicio del Job
    
    if ($startTime) {
        $sqlRestore = "SELECT COUNT(DISTINCT destination_database_name) as total 
                       FROM msdb.dbo.restorehistory 
                       WHERE restore_date >= ?";
        
        $stmtRes = sqlsrv_query($conn, $sqlRestore, array($startTime));
        if ($stmtRes && $rowRes = sqlsrv_fetch_array($stmtRes, SQLSRV_FETCH_ASSOC)) {
            $items_procesados = $rowRes['total'];
        }
        $mensaje_estado = "Restauradas $items_procesados de $TOTAL_DBS_A_PROCESAR bases";
    }
}

// CALCULO PORCENTAJE
if ($TOTAL_DBS_A_PROCESAR > 0) {
    $progreso_percent = round(($items_procesados / $TOTAL_DBS_A_PROCESAR) * 100);
}
if ($progreso_percent > 100) $progreso_percent = 100;

// Si el Job ya terminó (éxito), forzamos 100%
if (!$isRunning && $runStatus == 1) {
    $progreso_percent = 100;
    $mensaje_estado = "Proceso Completado";
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