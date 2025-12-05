<?php
// 1. CONFIGURACIÓN DE ERRORES (Para evitar pantalla blanca)
ini_set('display_errors', 0); // Lo ponemos en 0 para no ensuciar el JSON
error_reporting(E_ALL);
header('Content-Type: application/json');

// =======================================================================
// 2. CONEXIÓN DIRECTA A SQL SERVER
// =======================================================================
// Como sqlserver.php no nos da una conexión global, la creamos aquí mismo.
$serverName = "172.16.1.39";
// Usamos 'master' para comandos de sistema, con las credenciales que me mostraste
$connectionInfo = array(
    "Database" => "master", 
    "UID" => "mezcla", 
    "PWD" => "Zeus33$", 
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($serverName, $connectionInfo);

// Si falla la conexión, devolvemos JSON de error y salimos
if ($conn === false) {
    $errores = sqlsrv_errors();
    $msg = "Error conectando a SQL (172.16.1.39): " . ($errores[0]['message'] ?? 'Desconocido');
    echo json_encode(['status' => 'error', 'msg' => $msg]);
    exit;
}

// =======================================================================
// 3. CONFIGURACIÓN DE LA BÚSQUEDA
// =======================================================================
// Ruta LOCAL en el servidor 172.16.1.39 (Doble barra invertida es OBLIGATORIA)
$ruta_local_sql = 'C:\\BAK\\'; 
$total_esperado = 18; 

// =======================================================================
// 4. CONSULTA INTELIGENTE (xp_dirtree)
// =======================================================================
// Le pedimos a SQL Server que mire su propio disco C:
$sql = "
    DECLARE @FileList TABLE (FileName NVARCHAR(255), Depth INT, IsFile INT);
    
    -- El 1, 1 indica profundidad 1 (solo esa carpeta) y que incluya archivos
    INSERT INTO @FileList
    EXEC master.sys.xp_dirtree '$ruta_local_sql', 1, 1;
    
    -- Filtramos solo los .BAK
    SELECT FileName FROM @FileList WHERE IsFile = 1 AND FileName LIKE '%.BAK';
";

$stmt = sqlsrv_query($conn, $sql);

// Respuesta inicial
$response = [
    'status' => 'ok',
    'running' => true,
    'percent' => 0,
    'processed' => 0,
    'total' => $total_esperado,
    'msg' => 'Escaneando disco C:\BAK\ en el servidor...'
];

if ($stmt === false) {
    $response['status'] = 'error';
    $errors = sqlsrv_errors();
    $response['msg'] = "Error ejecutando xp_dirtree: " . ($errors[0]['message'] ?? 'Error SQL');
    echo json_encode($response);
    exit;
}

// =======================================================================
// 5. CONTAR RESULTADOS
// =======================================================================
$archivos_encontrados = 0;

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $archivos_encontrados++;
}

// Cerrar conexión para liberar recursos
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// =======================================================================
// 6. RESPUESTA FINAL
// =======================================================================
$response['processed'] = $archivos_encontrados;

if ($archivos_encontrados >= $total_esperado) {
    $response['percent']    = 100;
    $response['running']    = false; 
    $response['run_status'] = 1;     
    $response['msg']        = "¡Backups Completados!";
} else {
    // Calculamos porcentaje
    $porcentaje = ($total_esperado > 0) ? round(($archivos_encontrados / $total_esperado) * 100) : 0;
    
    // Tope visual 99% si falta poco
    if ($porcentaje >= 100 && $archivos_encontrados < $total_esperado) {
        $porcentaje = 99;
    }

    $response['percent'] = $porcentaje;
    $response['msg']     = "Archivos creados: $archivos_encontrados de $total_esperado...";
}

echo json_encode($response);
?>