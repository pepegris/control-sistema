<?php
// check_restore.php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

// 1. CONEXIÓN AL SERVIDOR DESTINO (OJO CON LA IP)
// Asegúrate de que esta sea la IP donde se están restaurando las bases
$serverName = "172.16.1.19"; 
$connectionInfo = array(
    "Database" => "master", // Conectamos a master para ver procesos
    "UID" => "mezcla", 
    "PWD" => "Zeus33$", 
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    echo json_encode(['status' => 'error', 'msg' => 'Error Conexión SQL Destino']);
    exit;
}

// 2. CONFIGURACIÓN
$total_esperado = 18; 
$last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 999999999;

// =======================================================================
// PASO A: VERIFICAR CUÁNTAS HAN TERMINADO (HISTORIAL)
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
// PASO B: VERIFICAR QUÉ ESTÁ HACIENDO AHORA (EN VIVO)
// =======================================================================
// Si llevamos pocos procesados, miramos qué proceso está activo para dar feedback
$msg_extra = "";
$percent_current = 0;

if ($procesados < $total_esperado) {
    $sqlLive = "
        SELECT DB_NAME(database_id) as db_name, percent_complete
        FROM sys.dm_exec_requests
        WHERE command LIKE 'RESTORE%' AND percent_complete > 0
    ";
    
    $stmtLive = sqlsrv_query($conn, $sqlLive);
    if ($stmtLive && $rowLive = sqlsrv_fetch_array($stmtLive, SQLSRV_FETCH_ASSOC)) {
        $db_actual = $rowLive['db_name'];
        $percent_current = round($rowLive['percent_complete']);
        $msg_extra = " (Actual: $db_actual $percent_current%)";
    } else {
        // Si no hay restore activo y tampoco historial nuevo, quizás terminó todo o no ha empezado
        if ($procesados == 0) $msg_extra = " (Iniciando...)";
    }
}

sqlsrv_close($conn);

// =======================================================================
// RESPUESTA
// =======================================================================
$response = [
    'status'    => 'ok',
    'running'   => true,
    'percent'   => 0,
    'processed' => $procesados,
    'total'     => $total_esperado,
    'msg'       => "Restaurando..."
];

if ($procesados >= $total_esperado) {
    $response['percent']    = 100;
    $response['running']    = false; 
    $response['run_status'] = 1;     
    $response['msg']        = "¡Restauración Completa!";
} else {
    // Calculamos porcentaje global
    // Fórmula: (Procesados * 100 / Total) + (PorcentajeActual / Total)
    // Esto hace que la barra se mueva suavemente incluso dentro de una misma BD
    $calculo_global = ($procesados * 100 / $total_esperado);
    
    if ($percent_current > 0) {
        $calculo_global += ($percent_current / $total_esperado);
    }
    
    $porcentaje = round($calculo_global);
    
    if ($porcentaje >= 100 && $procesados < $total_esperado) {
        $porcentaje = 99;
    }

    $response['percent'] = $porcentaje;
    $response['msg']     = "Restauradas: $procesados de $total_esperado" . $msg_extra;
}

echo json_encode($response);
?>