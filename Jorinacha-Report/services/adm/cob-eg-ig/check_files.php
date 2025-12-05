<?php
// Configuración para que el JSON salga limpio
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

// 1. CONEXIÓN DIRECTA (Usando credenciales de sysadmin)
$serverName = "172.16.1.39";
$connectionInfo = array(
    "Database" => "msdb", // Conectamos a la base de datos de sistema
    "UID" => "mezcla", 
    "PWD" => "Zeus33$", 
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    $errors = sqlsrv_errors();
    echo json_encode(['status' => 'error', 'msg' => 'Error Conexión SQL: ' . ($errors[0]['message'] ?? 'Desconocido')]);
    exit;
}

// 2. CONFIGURACIÓN
$total_esperado = 18; 

// 3. CONSULTA AL HISTORIAL (La forma más segura)
// Cuenta cuántas bases de datos distintas han terminado su backup en los últimos 60 minutos.
// type = 'D' asegura que sean Backups Completos (Full)
$sql = "
    SELECT COUNT(DISTINCT database_name) as TotalBackups
    FROM msdb.dbo.backupset
    WHERE type = 'D' 
    AND backup_finish_date >= DATEADD(minute, -60, GETDATE())
";

$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    echo json_encode(['status' => 'error', 'msg' => 'Error Query MSDB: ' . ($errors[0]['message'] ?? 'Desconocido')]);
    exit;
}

// 4. LEER RESULTADO
$procesados = 0;
if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $procesados = $row['TotalBackups'];
}

// Liberar recursos
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// 5. RESPUESTA JSON
$response = [
    'status'    => 'ok',
    'running'   => true,
    'percent'   => 0,
    'processed' => $procesados,
    'total'     => $total_esperado,
    'msg'       => "Verificando historial..."
];

// Lógica de porcentaje
if ($procesados >= $total_esperado) {
    $response['percent']    = 100;
    $response['running']    = false; 
    $response['run_status'] = 1;     
    $response['msg']        = "¡Todos los backups registrados!";
} else {
    // Calculamos porcentaje visual
    $porcentaje = ($total_esperado > 0) ? round(($procesados / $total_esperado) * 100) : 0;
    
    // Si matemáticamente da 100% (por redondeo) pero faltan archivos, forzamos 99%
    if ($porcentaje >= 100 && $procesados < $total_esperado) {
        $porcentaje = 99;
    }

    $response['percent'] = $porcentaje;
    $response['msg']     = "Backups finalizados: $procesados de $total_esperado";
}

echo json_encode($response);
?>