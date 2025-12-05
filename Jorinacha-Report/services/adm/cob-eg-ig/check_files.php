<?php
// Archivo: services/adm/cob-eg-ig/check_files.php

header('Content-Type: application/json');

// 1. CONFIGURACIÓN
// ---------------------------------------------
// OJO: Si Z: no funciona (por permisos de servicio), usa la ruta de red directa:
 $directorio = "//172.16.1.39/CarpetaCompartida/"; 
//$directorio = "Z:/"; 
$total_esperado = 36; // Ajusta esto al total real de tus bases de datos (según tu lista de exclusión)

// 2. LÓGICA
// ---------------------------------------------
$response = [
    'status' => 'ok',
    'running' => true,
    'percent' => 0,
    'processed' => 0,
    'total' => $total_esperado,
    'msg' => 'Buscando archivos en Z: ...'
];

// Verificar si el directorio es accesible
if (!is_dir($directorio)) {
    echo json_encode([
        'status' => 'error', 
        'msg' => 'No se puede leer la unidad Z:\ (Revise permisos del servicio Web)'
    ]);
    exit;
}

// Obtener todos los .BAK
$archivos = glob($directorio . "*.BAK");
$archivos_nuevos = 0;
$ahora = time();

// Contamos solo los archivos modificados en los últimos 20 minutos
// (Así sabemos que son de ESTE proceso y no del backup de ayer)
$ventana_tiempo = 20 * 60; // 20 minutos en segundos

foreach ($archivos as $archivo) {
    if (file_exists($archivo)) {
        $fecha_mod = filemtime($archivo);
        // Si el archivo fue modificado recientemente, cuenta como "Procesado"
        if (($ahora - $fecha_mod) < $ventana_tiempo) {
            $archivos_nuevos++;
        }
    }
}

// 3. CÁLCULO DE PORCENTAJE
// ---------------------------------------------
$response['processed'] = $archivos_nuevos;

if ($archivos_nuevos >= $total_esperado) {
    $response['percent'] = 100;
    $response['running'] = false; // Terminó
    $response['run_status'] = 1;  // Éxito
    $response['msg'] = "Backup Finalizado.";
} else {
    $porcentaje = round(($archivos_nuevos / $total_esperado) * 100);
    $response['percent'] = $porcentaje;
    $response['msg'] = "Generando Backups ($archivos_nuevos de $total_esperado)...";
}

echo json_encode($response);
?>