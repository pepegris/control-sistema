

<?php

/* $servername = "localhost";
$database = "control_sistema";
$username = "root";
$password = "";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
    
    die("Connection failed: " . mysqli_connect_error());
}
  */


   

  $serverName = "sql"; 
  $connectionInfo = array( "Database"=>"KAGU_A", "UID"=>"syncro", "PWD"=>"syncro");
  $conn = sqlsrv_connect( $serverName, $connectionInfo);
  
if (!$conn) {
die("Connection failed: " . sqlsrv_connect_error());
}



 






?>
