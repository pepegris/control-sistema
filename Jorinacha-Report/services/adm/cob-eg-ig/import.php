<?php
// RUTA: ../../services/adm/cob-eg-ig/import.php

function ejecutarJob($ip, $jobName) {
    try {
        $connectionInfo = array("Database" => "msdb", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($ip, $connectionInfo);

        if (!$conn) return false;

        // Ejecutar el Job específico
        $sql = "EXEC dbo.sp_start_job N'$jobName'";
        $consulta = sqlsrv_query($conn, $sql);

        if ($consulta === false) {
            $errors = sqlsrv_errors();
            // Si el error es 22022 (Ya está corriendo), lo tomamos como éxito
            if (isset($errors[0]['code']) && $errors[0]['code'] == 22022) {
                return true; 
            }
            return false;
        }
        return true;

    } catch (\Throwable $th) {
        return false;
    }
}

// Funciones wrapper simplificadas
function triggerJob($type, $isNeo) {
    $suffix = $isNeo ? " neo" : ""; // Agrega " neo" si no es completo
    
    if ($type == 'backups') {
        // Ejemplo: INTEGRACION BACKUPS neo
        return ejecutarJob("172.16.1.39", "INTEGRACION BACKUPS" . $suffix);
    } 
    elseif ($type == 'restore') {
        // Ejemplo: INTEGRACION RESTORE neo
        return ejecutarJob("172.16.1.19", "INTEGRACION RESTORE" . $suffix);
    }
    return false;
}
?>