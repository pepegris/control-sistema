<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

// 1. CONEXIÓN MSDB
$serverName = "172.16.1.39";
$connectionInfo = array("Database" => "msdb", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    echo json_encode(['status' => 'error', 'msg' => 'Error Conexión SQL']);
    exit;
}

$total_esperado = 18; 

// 2. RECIBIR EL ÚLTIMO ID CONOCIDO
// Si no recibimos nada, asumimos un numero muy alto para no mostrar nada (seguridad)
$last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 999999999;

// 3. CONSULTA INFALIBLE
// Solo cuenta los backups cuyo ID sea MAYOR al que teníamos al principio.
$sql = "
    SELECT COUNT(DISTINCT database_name) as TotalBackups
    FROM msdb.dbo.backupset
    WHERE type = 'D' 
    AND backup_set_id > $last_id
";

$stmt = sqlsrv_query($conn, $sql);
$procesados = 0;
if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $procesados = $row['TotalBackups'];
}

sqlsrv_close($conn);

// 4. RESPUESTA
$response = [
    'status'    => 'ok',
    'running'   => true,
    'percent'   => 0,
    'processed' => $procesados,
    'total'     => $total_esperado,
    'msg'       => "Esperando nuevos archivos..."
];

if ($procesados >= $total_esperado) {
    $response['percent']    = 100;
    $response['running']    = false; 
    $response['run_status'] = 1;     
    $response['msg']        = "¡Backups Completados!";
} else {
    $porcentaje = ($total_esperado > 0) ? round(($procesados / $total_esperado) * 100) : 0;
    
    // Tope visual 99%
    if ($porcentaje >= 100 && $procesados < $total_esperado) {
        $porcentaje = 99;
    }

    $response['percent'] = $porcentaje;
    $response['msg']     = "Generados: $procesados de $total_esperado...";
}

echo json_encode($response);
?>