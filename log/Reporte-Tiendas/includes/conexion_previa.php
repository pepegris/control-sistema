<?php

            $serverName = "SQL"; 
            $connectionInfo = array( "Database"=>"PREVIA_A", "UID"=>"mezcla", "PWD"=>"Zeus33$");
            $conn_sql = sqlsrv_connect( $serverName, $connectionInfo);
if (!$conn) {
    die("Connection failed: " . sqlsrv_connect_error());
}








?>