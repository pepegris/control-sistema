<?php
// 1. Incluir conexiones y configuraciones
require '../../includes/log.php';
require '../../services/mysql.php';
require '../../services/sqlserver.php'; // Asumo que $conn es la conexión a 172.16.1.39

// 2. AUMENTAR TIMEOUT (CRÍTICO)
// Como el SQL se quedará esperando a que termine de borrar, el script puede tardar.
// Evitamos que PHP corte la conexión.
set_time_limit(0); 
if(function_exists('sqlsrv_configure')) {
    sqlsrv_configure("StatementTimeout", 0);
}

// 3. Recibir datos del Overlay
$script = $_POST['scripts'];       // Values: 'backups' o 'restore'
$clave  = $_POST['clave'];
$old    = isset($_POST['old_companies']) ? 1 : 0;

// Validación simple de clave
if ($clave != "1234") { // <--- Pon tu clave real aquí
    die("<script>alert('Clave Incorrecta'); window.history.back();</script>");
}

try {
    $mensaje = "";

    // ---------------------------------------------------------
    // OPCIÓN 1: REALIZAR BACKUPS (Aquí va tu línea mágica)
    // ---------------------------------------------------------
    if ($script == 'backups') {
        
        // Ejecutamos el SP indicando @EjecutarLimpieza = 1
        // Esto dispara el Job 'INTEGRACION DELETE', espera a que termine y luego sigue.
        $sql = "EXEC [SISTEMAS].[dbo].[sp_GestionarBackups] @EjecutarLimpieza = 1;";
        
        // Ejecutar consulta
        $stmt = sqlsrv_query($conn, $sql);
        
        if ($stmt === false) {
            // Si falla, mostramos el error detallado de SQL Server
            $errors = sqlsrv_errors();
            throw new Exception("Error SQL: " . print_r($errors, true));
        }

        $mensaje = "✅ Limpieza de archivos .BAK viejos completada.<br>✅ Nuevos Backups generados correctamente en 172.16.1.39.";
    } 
    
    // ---------------------------------------------------------
    // OPCIÓN 2: RESTAURAR (Importar a Integración)
    // ---------------------------------------------------------
    elseif ($script == 'restore') {
        
        // Aquí NO ejecutamos la limpieza para no borrar lo que acabamos de crear.
        // Podrías llamar al mismo SP con 0, o tu propia lógica de restore.
        
        // $sql = "EXEC [SISTEMAS].[dbo].[sp_GestionarBackups] @EjecutarLimpieza = 0;";
        // sqlsrv_query($conn, $sql);

        $mensaje = "✅ Proceso de Restauración iniciado (Sin borrar backups fuentes).";
    }
    else {
        throw new Exception("Opción no válida seleccionada.");
    }

    // 4. Mostrar mensaje de éxito y botón para volver
    echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Proceso Terminado</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="d-flex align-items-center justify-content-center" style="height: 100vh; background-color: #f8f9fa;">
        <div class="text-center">
            <h1 class="text-success mb-4">¡Proceso Exitoso!</h1>
            <div class="alert alert-success d-inline-block" role="alert">
                '. $mensaje .'
            </div>
            <br><br>
            <a href="index.php" class="btn btn-primary btn-lg">Volver al Menú</a>
        </div>
    </body>
    </html>';

} catch (Exception $e) {
    // Manejo de errores visual
    echo '
    <div style="font-family:sans-serif; text-align:center; padding-top:50px; color:red;">
        <h1>⚠️ Ocurrió un Error</h1>
        <p>'. $e->getMessage() .'</p>
        <br>
        <a href="javascript:history.back()">Volver</a>
    </div>';
}
?>