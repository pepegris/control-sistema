<?php

$serverName = "172.16.1.19"; 
$connectionInfo = array( "Database"=>"PREVIA_A", "UID"=>"mezcla", "PWD"=>"Zeus33$");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if (!$conn) {
    die("Connection failed: " . sqlsrv_connect_error());
}


function getCat_art(){

    
    $serverName = "172.16.1.19"; 
    $connectionInfo = array( "Database"=>"PREVIA_A", "UID"=>"mezcla", "PWD"=>"Zeus33$");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);
    $sql = "SELECT co_cat,cat_des FROM cat_art ";
    $res = sqlsrv_query($conn,$sql);

    return $res;

}

function getCo_lin(){

    
    $serverName = "172.16.1.19"; 
    $connectionInfo = array( "Database"=>"PREVIA_A", "UID"=>"mezcla", "PWD"=>"Zeus33$");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);
    $sql = "SELECT co_lin from art group by co_lin ";
    $res = sqlsrv_query($conn,$sql);
    

    return $res;

}