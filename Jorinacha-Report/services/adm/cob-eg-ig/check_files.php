<?php
header('Content-Type: application/json');

// =======================================================================
// 1. CONFIGURACIÓN DE ACCESO (CAMBIOS NUEVOS APLICADOS)
// =======================================================================
$server_ip   = "172.16.1.39"; // Usamos la IP para evitar errores de DNS
$share_name  = "bak";
$domain      = "previa.local";
$username    = "andres.salcedo";
$password    = "7531598426*";

// Rutas: PHP usa barras normales (/), el comando de Windows usa invertidas (\)
$ruta_php    = "//" . $server_ip . "/" . $share_name . "/"; 
$ruta_win    = "\\\\" . $server_ip . "\\" . $share_name;    

// Total de bases de datos a esperar
$total_esperado = 18; 

// =======================================================================
// 2. INICIALIZAR RESPUESTA
// =======================================================================
$response = [
    'status' => 'ok',
    'running' => true,
    'percent' => 0,
    'processed' => 0,
    'total' => $total_esperado,
    'msg' => 'Conectando...'
];

// =======================================================================
// 3. INTENTO DE LECTURA Y AUTENTICACIÓN AUTOMÁTICA
// =======================================================================

// A) Primer intento: Leer el directorio directamente
$archivos = @scandir($ruta_php);

// B) Si falla (devuelve false), ejecutamos el comando de conexión (net use)
if ($archivos === false) {
    
    // Construimos el comando con las credenciales que me diste
    // net use \\172.16.1.39\bak "7531598426*" /user:"previa.local\andres.salcedo" /persistent:no
    $comando = "net use \"$ruta_win\" \"$password\" /user:\"$domain\\$username\" /persistent:no";
    
    // Ejecutamos el comando silenciosamente en la consola del servidor
    shell_exec($comando);

    // C) Segundo intento: Leer de nuevo tras haber enviado las credenciales
    $archivos = @scandir($ruta_php);
}

// D) Si sigue fallando después de todo, enviamos error
if ($archivos === false) {
    $error = error_get_last();
    $response['status'] = 'error';
    $response['msg'] = "Error de acceso a $ruta_win. Verifique permisos o credenciales.";
    echo json_encode($response);
    exit;
}

// =======================================================================
// 4. LÓGICA DE CONTEO
// =======================================================================
$archivos_encontrados = 0;
$ahora = time();
$ventana_tiempo = 30 * 60; // Miramos archivos modificados en los últimos 30 minutos

foreach ($archivos as $archivo) {
    // Ignorar referencias de carpeta . y ..
    if ($archivo == '.' || $archivo == '..') continue;

    // Verificar extensión .BAK
    if (strtoupper(pathinfo($archivo, PATHINFO_EXTENSION)) == 'BAK') {
        
        $ruta_completa = $ruta_php . $archivo;
        
        // Verificar fecha de modificación
        $fecha_mod = @filemtime($ruta_completa);
        
        if ($fecha_mod && ($ahora - $fecha_mod) < $ventana_tiempo) {
            $archivos_encontrados++;
        }
    }
}

// =======================================================================
// 5. CÁLCULO FINAL Y RESPUESTA
// =======================================================================
$response['processed'] = $archivos_encontrados;

if ($archivos_encontrados >= $total_esperado) {
    $response['percent']    = 100;
    $response['running']    = false; // Detener monitor
    $response['run_status'] = 1;     // Éxito visual
    $response['msg']        = "¡Backup Completo!";
} else {
    // Calculamos porcentaje
    $porcentaje = ($total_esperado > 0) ? round(($archivos_encontrados / $total_esperado) * 100) : 0;
    
    // Truco visual: Si llega a 100% por redondeo pero faltan archivos, forzamos 99%
    if ($porcentaje >= 100 && $archivos_encontrados < $total_esperado) {
        $porcentaje = 99;
    }

    $response['percent'] = $porcentaje;
    $response['msg']     = "Procesando: $archivos_encontrados de $total_esperado archivos...";
}

echo json_encode($response);
?>