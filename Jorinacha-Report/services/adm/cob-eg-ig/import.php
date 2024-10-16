<?php


function getImport()
{

    try {

        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "master", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);


        $sql = "EXEC msdb.dbo.sp_start_job 'INTEGRACION BACKUPS'";

        $consulta = sqlsrv_query($conn, $sql);


        if ($consulta == null) {

            $res = false;
            return $res;
        }else{

            $res = true;
            return $res;
        }

    } catch (\Throwable $th) {

        throw $th;
    }
}





function getRestore()
{

    try {

        $serverName = "172.16.1.19";
        $connectionInfo = array("Database" => "master", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);


        $sql = "EXEC msdb.dbo.sp_start_job 'INTEGRACION RESTORE'";

        $consulta = sqlsrv_query($conn, $sql);


        if ($consulta == null) {

            $res = false;
            return $res;
        }else{

            $res = true;
            return $res;
        }

    } catch (\Throwable $th) {

        throw $th;
    }
}
