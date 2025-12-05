<?php
// RUTA: ../../services/adm/cob-eg-ig/check_status.php
header('Content-Type: application/json');

// Recibimos qué script estamos monitoreando
$script = isset($_GET['script']) ? $_GET['script'] : '';

// CONFIGURACIÓN DINÁMICA DE CONEXIÓN
// Dependiendo de la acción, monitoreamos un servidor u otro
if ($script == 'backups') {
    $serverName = "172.16.1.39";      // Servidor donde corre el Backup
    $jobName = "INTEGRACION BACKUPS"; // Nombre exacto del Job en SQL
} elseif ($script == 'restore') {
    $serverName = "172.16.1.19";      // Servidor donde corre el Restore
    $jobName = "INTEGRACION RESTORE"; // Nombre exacto del Job en SQL
} else {
    echo json_encode(['status' => 'error', 'message' => 'Script no valido']);
    exit;
}

$connectionInfo = array(
    "Database" => "msdb", // CONECTAMOS A MSDB OBLIGATORIAMENTE
    "UID" => "mezcla",
    "PWD" => "Zeus33$",
    "CharacterSet" => "UTF-8",
    "LoginTimeout" => 5
);

$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'No hay conexión al servidor SQL ' . $serverName]);
    exit;
}

// CONSULTA INTELIGENTE AL AGENTE DE SQL
// Busca el estado actual del Job
$sql = "
    SELECT TOP 1 
        ja.start_execution_date,
        ja.stop_execution_date,
        CASE 
            -- Si hay fecha de inicio pero NO de fin, está corriendo
            WHEN ja.stop_execution_date IS NULL AND ja.start_execution_date IS NOT NULL THEN 1 
            ELSE 0 
        END as is_running,
        jh.run_status -- 0=Fallo, 1=Exito, 3=Cancelado (Solo tiene valor si terminó)
    FROM msdb.dbo.sysjobs j
    JOIN msdb.dbo.sysjobactivity ja ON j.job_id = ja.job_id
    LEFT JOIN msdb.dbo.sysjobhistory jh ON ja.job_history_id = jh.instance_id
    WHERE j.name = ?
    ORDER BY ja.start_execution_date DESC
";

$params = array($jobName);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Error consultando MSDB']);
    exit;
}

if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    
    $isRunning = $row['is_running'];
    $finalStatus = isset($row['run_status']) ? $row['run_status'] : null;
    
    // Calcular tiempo transcurrido para mostrarlo en pantalla
    $inicio = $row['start_execution_date'];
    $tiempoStr = "00:00:00";
    
    if ($inicio) {
        $ahora = new DateTime();
        $diff = $inicio->diff($ahora);
        $tiempoStr = $diff->format('%H:%I:%S');
    }

    echo json_encode([
        'status' => 'ok',
        'running' => ($isRunning == 1),
        'run_status' => $finalStatus, 
        'time_elapsed' => $tiempoStr
    ]);

} else {
    // El job nunca ha corrido
    echo json_encode(['status' => 'ok', 'running' => false, 'run_status' => -1]);
}

sqlsrv_close($conn);
?>