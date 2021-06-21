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


$sql_sedes="SELECT * FROM sedes ";

$consulta_sedes=mysqli_query($conn,$sql_sedes);

$contadores_sedes=array();

while ($sede=mysqli_fetch_array($consulta_sedes)) {
    $contadores_sedes[]=$sede['sedes_nom'];
    $contadores_sedes[]=$sede['contadores'];

    

    
    
}

for ($i=0; $i < 40 ; $i++) { 
    
   echo  "$contadores_sedes[$i]<br>";
}














?>