<?php

 $servername = "localhost";
$database = "control_sistema";
$username = "root";
$password = "";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
    
    die("Connection failed: " . mysqli_connect_error());
}
 

/* $serverName = "SQL"; 
$connectionInfo = array( "Database"=>"control_sistema", "UID"=>"mezcla", "PWD"=>"Zeus33$");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if (!$conn) {
die("Connection failed: " . sqlsrv_connect_error());
}
 */






?>
