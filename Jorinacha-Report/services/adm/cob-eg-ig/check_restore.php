<?php
// check_restore.php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

// 1. CONEXIÓN AL SERVIDOR DESTINO (.19)
// Asegúrate que esta IP sea la correcta
$serverName = "172.16.1.19"; 
$connectionInfo = array(
    "Database" => "msdb", 
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

// 3. RECIBIR ÚLTIMO ID
// Si no viene, usamos un numero altísimo para no mostrar nada viejo
$last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 999999999;

// 4. CONSULTA POR ID
// Solo contamos las restauraciones nuevas (cuyo ID sea mayor al que teníamos al inicio)
$sql = "
    SELECT COUNT(DISTINCT destination_database_name) as TotalRestores
    FROM msdb.dbo.restorehistory
    WHERE restore_history_id > $last_id
";

$stmt = sqlsrv_query($conn, $sql);
$procesados = 0;

if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $procesados = $row['TotalRestores'];
}

sqlsrv_close($conn);

// 5. RESPUESTA
$response = [
    'status'    => 'ok',
    'running'   => true,
    'percent'   => 0,
    'processed' => $procesados,
    'total'     => $total_esperado,
    'msg'       => "Esperando restauración..."
];

if ($procesados >= $total_esperado) {
    $response['percent']    = 100;
    $response['running']    = false; 
    $response['run_status'] = 1;     
    $response['msg']        = "¡Restauración Completa!";
} else {
    $porcentaje = ($total_esperado > 0) ? round(($procesados / $total_esperado) * 100) : 0;
    
    if ($porcentaje >= 100 && $procesados < $total_esperado) {
        $porcentaje = 99;
    }

    $response['percent'] = $porcentaje;
    $response['msg']     = "Restauradas: $procesados de $total_esperado bases...";
}

echo json_encode($response);
?>