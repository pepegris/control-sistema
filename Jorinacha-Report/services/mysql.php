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


function getTiendas(){

    
    $servername = "localhost";
    $database = "control_sistema";
    $username = "root";
    $password = "";
    $conn = mysqli_connect($servername, $username, $password, $database);
    $sql = "SELECT sedes_nom FROM sedes WHERE estado_sede <> 'inactivo'";
    $res = mysqli_query($conn,$sql);

    return $res;

}