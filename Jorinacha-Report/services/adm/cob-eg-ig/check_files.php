<?php
header('Content-Type: application/json');

// 1. CONFIGURACIÓN DE LA RUTA
// ---------------------------------------------
// PHP maneja mejor las rutas de red con barras normales "/"
// Opción A: Usando el nombre del servidor (Si el hosting lo reconoce)
$directorio = "//Sql2k8/bak/"; 

// Opción B: Si falla el nombre, usa la IP directa (Más seguro)
// $directorio = "//172.16.1.39/bak/"; 

// Total de bases de datos a esperar (ajusta este número)
$total_esperado = 18; 

// 2. INICIALIZAR RESPUESTA
// ---------------------------------------------
$response = [
    'status' => 'ok',
    'running' => true,
    'percent' => 0,
    'processed' => 0,
    'total' => $total_esperado,
    'msg' => 'Conectando a carpeta compartida...'
];

// 3. VALIDACIÓN DE ACCESO (DEBUG)
// ---------------------------------------------
// Intentamos abrir el directorio para ver si PHP tiene permisos
if (!is_dir($directorio)) {
    // Si falla, intentamos ver si es un problema de error exacto
    $error = error_get_last();
    $response['status'] = 'error';
    $response['msg'] = "No se puede leer la ruta: $directorio. Verifique permisos de red para el usuario IUSR/Apache.";
    echo json_encode($response);
    exit;
}

// 4. LÓGICA DE CONTEO
// ---------------------------------------------
// Usamos glob para buscar archivos .BAK
// NOTA: glob() a veces falla en rutas de red en Windows.
// Usamos scandir como alternativa más robusta.
$archivos_encontrados = 0;
$archivos = scandir($directorio);
$ahora = time();
$ventana_tiempo = 20 * 60; // 20 minutos atrás

if ($archivos === false) {
    $response['status'] = 'error';
    $response['msg'] = "La carpeta existe pero no se pueden leer los archivos.";
    echo json_encode($response);
    exit;
}

foreach ($archivos as $archivo) {
    // Ignorar . y ..
    if ($archivo == '.' || $archivo == '..') continue;

    // Verificar extensión
    if (strtoupper(pathinfo($archivo, PATHINFO_EXTENSION)) == 'BAK') {
        
        $ruta_completa = $directorio . $archivo;
        
        // Verificar fecha de modificación
        // El @ evita errores si el archivo está bloqueado por SQL escribiendo
        $fecha_mod = @filemtime($ruta_completa);
        
        if ($fecha_mod && ($ahora - $fecha_mod) < $ventana_tiempo) {
            $archivos_encontrados++;
        }
    }
}

// 5. CALCULO FINAL
// ---------------------------------------------
$response['processed'] = $archivos_encontrados;
$response['msg'] = "Monitoreando: $archivos_encontrados de $total_esperado archivos listos.";

if ($archivos_encontrados >= $total_esperado) {
    $response['percent'] = 100;
    $response['running'] = false; // Detener monitor
    $response['run_status'] = 1;  // Éxito visual
    $response['msg'] = "¡Backup Completo!";
} else {
    // Evitar división por cero
    $porcentaje = ($total_esperado > 0) ? round(($archivos_encontrados / $total_esperado) * 100) : 0;
    $response['percent'] = $porcentaje;
}

echo json_encode($response);
?>