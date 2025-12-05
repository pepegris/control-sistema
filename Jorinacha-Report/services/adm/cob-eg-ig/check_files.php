<?php
// Configuración para salida JSON limpia
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

// 1. CONEXIÓN A MSDB (Base de datos del Sistema)
$serverName = "172.16.1.39";
$connectionInfo = array(
    "Database" => "msdb", 
    "UID" => "mezcla", 
    "PWD" => "Zeus33$", 
    "CharacterSet" => "UTF-8"
);
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    echo json_encode(['status' => 'error', 'msg' => 'Error de conexión a SQL Server']);
    exit;
}

// 2. CONFIGURACIÓN
$total_esperado = 18; 

// 3. RECIBIR LA FECHA DE INICIO
// Si JS nos envía la fecha, la usamos. Si no, usamos hace 10 minutos por defecto.
$fecha_sql = isset($_GET['since']) ? $_GET['since'] : date('Y-m-d H:i:s', time() - 600);

// Limpieza básica de seguridad para la fecha
$fecha_sql = preg_replace('/[^0-9 \-:.]/', '', $fecha_sql); 

// 4. CONSULTA AL HISTORIAL
// Contamos cuántas bases de datos distintas terminaron su backup DESPUÉS de la fecha recibida
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

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// 5. PREPARAR RESPUESTA
$response = [
    'status'    => 'ok',
    'running'   => true,
    'percent'   => 0,
    'processed' => $procesados,
    'total'     => $total_esperado,
    'msg'       => "Esperando nuevos backups..."
];

if ($procesados >= $total_esperado) {
    // Si ya llegamos al total esperado (18)
    $response['percent']    = 100;
    $response['running']    = false; 
    $response['run_status'] = 1;     
    $response['msg']        = "¡Todos los backups registrados!";
} else {
    // Cálculo de porcentaje
    $porcentaje = ($total_esperado > 0) ? round(($procesados / $total_esperado) * 100) : 0;
    
    // Truco visual: No pasar del 99% si faltan archivos (por ejemplo si tienes 17 de 18)
    if ($porcentaje >= 100 && $procesados < $total_esperado) {
        $porcentaje = 99;
    }

    $response['percent'] = $porcentaje;
    $response['msg']     = "Procesando: $procesados de $total_esperado backups...";
}

echo json_encode($response);
?>