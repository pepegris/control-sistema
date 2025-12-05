<?php
// check_restore.php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

// 1. CONEXIÓN AL SERVIDOR DESTINO (DONDE SE EJECUTA EL RESTORE)
// IMPORTANTE: Cambia esta IP por la del servidor de Integración si es distinta
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
// Conté 18 bases de datos en tu script de restore
$total_esperado = 18; 

// 3. RECIBIR HORA DE INICIO
$fecha_sql = isset($_GET['since']) ? $_GET['since'] : date('Y-m-d H:i:s', time() - 600);
// Limpieza de seguridad
$fecha_sql = preg_replace('/[^0-9 \-:.]/', '', $fecha_sql); 

// 4. CONSULTA AL HISTORIAL DE RESTAURACIONES
// Contamos cuántas bases de datos han terminado de restaurarse desde la hora del clic
$sql = "
    SELECT COUNT(DISTINCT destination_database_name) as TotalRestores
    FROM msdb.dbo.restorehistory
    WHERE restore_date >= '$fecha_sql'
";

$stmt = sqlsrv_query($conn, $sql);
$procesados = 0;

if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $procesados = $row['TotalRestores'];
}

sqlsrv_close($conn);

// 5. RESPUESTA JSON
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