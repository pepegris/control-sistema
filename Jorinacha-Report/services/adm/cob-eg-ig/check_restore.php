<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

// 1. CONEXIÓN (VERIFICA LA IP)
$serverName = "172.16.1.19"; 
$connectionInfo = array("Database" => "master", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    echo json_encode(['status' => 'error', 'msg' => 'Error Conexión SQL']);
    exit;
}

// 2. CONFIGURACIÓN
$total_esperado = 18; 
$jobName = "INTEGRACION RESTORE neo"; 
$last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 999999999;

// =======================================================================
// A. VERIFICAR SI EL JOB SIGUE VIVO
// =======================================================================
$sqlJob = "
    SELECT top 1 ja.stop_execution_date, jh.run_status
    FROM msdb.dbo.sysjobs j
    INNER JOIN msdb.dbo.sysjobactivity ja ON j.job_id = ja.job_id
    LEFT JOIN msdb.dbo.sysjobhistory jh ON ja.job_history_id = jh.instance_id
    WHERE j.name = '$jobName'
    AND ja.session_id = (SELECT MAX(session_id) FROM msdb.dbo.syssessions)
    ORDER BY ja.start_execution_date DESC
";
$stmtJob = sqlsrv_query($conn, $sqlJob);
$job_running = false;
$job_error = false;

if ($stmtJob && $rowJob = sqlsrv_fetch_array($stmtJob, SQLSRV_FETCH_ASSOC)) {
    if ($rowJob['stop_execution_date'] === null) {
        $job_running = true;
    } else {
        if (isset($rowJob['run_status']) && $rowJob['run_status'] == 0) $job_error = true;
    }
}

// =======================================================================
// B. CONTAR ÉXITOS
// =======================================================================
$sqlHistory = "
    SELECT COUNT(DISTINCT destination_database_name) as TotalRestores
    FROM msdb.dbo.restorehistory
    WHERE restore_history_id > $last_id
";
$stmt = sqlsrv_query($conn, $sqlHistory);
$procesados = 0;
if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $procesados = $row['TotalRestores'];
}

// =======================================================================
// C. VERIFICAR ACTIVIDAD ACTUAL
// =======================================================================
$msg_extra = "";
$percent_current = 0;

if ($job_running) {
    $sqlLive = "
        SELECT DB_NAME(database_id) as db_name, percent_complete
        FROM sys.dm_exec_requests
        WHERE command LIKE 'RESTORE%' AND percent_complete > 0
    ";
    $stmtLive = sqlsrv_query($conn, $sqlLive);
    
    if ($stmtLive && $rowLive = sqlsrv_fetch_array($stmtLive, SQLSRV_FETCH_ASSOC)) {
        // Actividad de Restore detectada
        $db_actual = $rowLive['db_name'];
        if($db_actual == 'master') $db_actual = "Sistema";
        $percent_current = round($rowLive['percent_complete']);
        $msg_extra = " (Actual: $db_actual $percent_current%)";
    } else {
        // Job corre, pero no restaura (Fase final o Mantenimiento)
        if ($procesados > 0) {
            $percent_current = 99;
            // Mensaje informativo si ya acabamos la lista pero el job sigue
            if ($procesados >= $total_esperado - 1) { 
                 $msg_extra = " (Finalizando Job / Mantenimiento...)";
            } else {
                 $msg_extra = " (Cargando siguiente archivo...)";
            }
        } else {
            $msg_extra = " (Iniciando Job...)";
        }
    }
}

sqlsrv_close($conn);

// =======================================================================
// RESPUESTAS
// =======================================================================

// Si el job paró y faltan archivos, asumimos que terminó (con posibles fallos)
if (!$job_running && $procesados < $total_esperado) {
     echo json_encode([
        'status' => 'ok', 'running' => false, 'percent' => 100, 'processed' => $procesados, 'total' => $total_esperado,
        'msg' => "⚠️ Proceso finalizado. Se restauraron $procesados de $total_esperado bases.", 'run_status' => 1 
    ]);
    exit;
}

$response = [
    'status' => 'ok', 'running' => $job_running, 'percent' => 0, 'processed' => $procesados, 'total' => $total_esperado, 
    'msg' => "Restaurando..."
];

if ($procesados >= $total_esperado) {
    $response['percent'] = 100;
    $response['running'] = false; 
    $response['run_status'] = 1;     
    $response['msg'] = "¡Restauración Completa!";
} else {
    $calculo_global = ($procesados * 100 / $total_esperado);
    if ($percent_current > 0) $calculo_global += ($percent_current / $total_esperado);
    
    $porcentaje = round($calculo_global);
    if ($porcentaje >= 100) $porcentaje = 99;

    $response['percent'] = $porcentaje;
    $response['msg'] = "Restauradas: $procesados de $total_esperado" . $msg_extra;
}

echo json_encode($response);
?>