<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

// 1. CONEXIÓN DIRECTA MSDB
$serverName = "172.16.1.39";
$connectionInfo = array("Database" => "msdb", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    echo json_encode(['status' => 'error', 'msg' => 'Error Conexión SQL']);
    exit;
}

$total_esperado = 18; 

// 2. RECIBIR HORA DE INICIO
// Esta hora ahora viene sincronizada desde el servidor .39 (gracias a Import.php)
$timestamp_inicio = isset($_GET['since']) ? intval($_GET['since']) : (time() - 600);
$fecha_sql = date("Y-m-d H:i:s", $timestamp_inicio);

// 3. CONSULTA CON FILTRO DE TIEMPO
// Solo contamos los backups terminados DESPUÉS de la hora que capturamos
$sql = "
    SELECT COUNT(DISTINCT database_name) as TotalBackups
    FROM msdb.dbo.backupset
    WHERE type = 'D' 
    AND backup_finish_date >= '$fecha_sql'
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
    'msg'       => "Procesando bases de datos..."
];

if ($procesados >= $total_esperado) {
    $response['percent']    = 100;
    $response['running']    = false; 
    $response['run_status'] = 1;     
    $response['msg']        = "¡Backups Completados!";
} else {
    $porcentaje = ($total_esperado > 0) ? round(($procesados / $total_esperado) * 100) : 0;
    
    if ($porcentaje >= 100 && $procesados < $total_esperado) {
        $porcentaje = 99;
    }

    $response['percent'] = $porcentaje;
    $response['msg']     = "Generando: $procesados de $total_esperado backups...";
}

echo json_encode($response);
?>