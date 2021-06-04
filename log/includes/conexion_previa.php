<?php

            $serverName = "SQL"; 
            $connectionInfo = array( "Database"=>"PREVIA_A", "UID"=>"syncro", "PWD"=>"syncro");
            $conn = sqlsrv_connect( $serverName, $connectionInfo);
if (!$conn) {
    die("Connection failed: " . sqlsrv_connect_error());
}








?>