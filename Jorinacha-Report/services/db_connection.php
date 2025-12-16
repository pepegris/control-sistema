<?php
// ../../services/db_connection.php

function ConectarSQLServer($nombre_bd) {
    $serverName = "172.16.1.39"; 
    $connectionInfo = array(
        "Database" => $nombre_bd,
        "UID" => "mezcla",
        "PWD" => "Zeus33$", // Tu clave centralizada
        "CharacterSet" => "UTF-8",
        "LoginTimeout" => 3 // Si una tienda está caída, falla rápido y sigue con la otra
    );

    $conn = sqlsrv_connect($serverName, $connectionInfo);
    return $conn; // Retorna false si falla
}


function ConectarSQLServer_local_vpn($nombre_bd,$ip) {
    $serverName = "$ip"; 
    $connectionInfo = array(
        "Database" => $nombre_bd,
        "UID" => "mezcla",
        "PWD" => "Zeus33$", // Tu clave centralizada
        "CharacterSet" => "UTF-8",
        "LoginTimeout" => 3 // Si una tienda está caída, falla rápido y sigue con la otra
    );

    $conn = sqlsrv_connect($serverName, $connectionInfo);
    return $conn; // Retorna false si falla
}
?>