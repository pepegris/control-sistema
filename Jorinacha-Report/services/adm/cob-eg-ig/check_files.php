<?php
header('Content-Type: application/json');

// 1. INCLUIR CONEXIÓN SQL SERVER
require '../../services/sqlserver.php'; 

// =======================================================================
// 2. CONFIGURACIÓN
// =======================================================================
// CORRECCIÓN AQUÍ: Usamos doble barra al final (\\) para no escapar la comilla
$ruta_local_sql = 'C:\\BAK\\'; 

$total_esperado = 18; 

// =======================================================================
// 3. CONSULTAR ARCHIVOS A TRAVÉS DE SQL
// =======================================================================
$sql = "
    DECLARE @FileList TABLE (FileName NVARCHAR(255), Depth INT, IsFile INT);
    INSERT INTO @FileList
    EXEC master.sys.xp_dirtree '$ruta_local_sql', 1, 1;
    
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
    'msg' => 'Consultando disco del servidor...'
];

if ($stmt === false) {
    $response['status'] = 'error';
    // Imprimimos el error real de SQL para saber qué pasa si falla
    if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            $msj_error = $error[ 'message'];
        }
    } else {
        $msj_error = "Error desconocido";
    }
    
    $response['msg'] = "Error SQL: " . $msj_error;
    echo json_encode($response);
    exit;
}

// =======================================================================
// 4. CONTAR ARCHIVOS
// =======================================================================
$archivos_encontrados = 0;

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $archivos_encontrados++;
}

// =======================================================================
// 5. RESPUESTA FINAL
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
    
    // Tope visual 99%
    if ($porcentaje >= 100 && $archivos_encontrados < $total_esperado) {
        $porcentaje = 99;
    }

    $response['percent'] = $porcentaje;
    $response['msg']     = "Archivos en disco: $archivos_encontrados de $total_esperado...";
}

echo json_encode($response);
?>