<?php

/* $serverName = "172.16.1.19"; 
$connectionInfo = array( "Database"=>"PREVIA_A", "UID"=>"mezcla", "PWD"=>"Zeus33$");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if (!$conn) {
    die("Connection failed: " . sqlsrv_connect_error());
}

 */
function getCat_art()
{


    $serverName = "172.16.1.19";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT co_cat,cat_des FROM cat_art ";
    $res = sqlsrv_query($conn, $sql);

    return $res;
}

function getLin_art()
{


    $serverName = "172.16.1.19";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT co_lin,lin_des from lin_art";
    $res = sqlsrv_query($conn, $sql);


    while ($row2 = sqlsrv_fetch_array($res)) {
        $i=0;
        $co_lin[$i] = $row2[co_lin];
        $co_lin[$i] = $row2[lin_des];
    }


    return $co_lin;
}
