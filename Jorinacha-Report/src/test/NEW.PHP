<?php

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';


$serverName = "172.16.1.39";
$connectionInfo = array("Database" => "TPLIO1A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect($serverName, $connectionInfo);

$sql ="EXEC getArt 'Previa Shop' , '0' , '603'";


$consulta = sqlsrv_query($conn, $sql);

while ($row = sqlsrv_fetch_array($consulta)) {

    $art[] = $row;
}

echo "probando <br>";
var_dump($art);
