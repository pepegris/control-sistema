<?php
// RUTA: ../../services/adm/cob-eg-ig/import.php

function ejecutarJob($ip, $jobName) {
    try {
        $connectionInfo = array("Database" => "msdb", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($ip, $connectionInfo);

        if (!$conn) return false;

        $sql = "EXEC dbo.sp_start_job N'$jobName'";
        $consulta = sqlsrv_query($conn, $sql);

        // Si falla, verificamos si es porque ya está corriendo (Error 22022)
        if ($consulta === false) {
            $errors = sqlsrv_errors();
            // Si el error es "Request to run job... refused because the job is already running", retornamos true
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

function getImport() {
    // Iniciar Backup en el servidor .39
    return ejecutarJob("172.16.1.39", "INTEGRACION BACKUPS");
}

function getRestore() {
    // Iniciar Restore en el servidor .19
    return ejecutarJob("172.16.1.19", "INTEGRACION RESTORE");
}
?>