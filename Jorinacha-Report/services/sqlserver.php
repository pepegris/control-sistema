<?php

/* $serverName = "172.16.1.19"; 
$connectionInfo = array( "Database"=>"PREVIA_A", "UID"=>"mezcla", "PWD"=>"Zeus33$");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if (!$conn) {
    die("Connection failed: " . sqlsrv_connect_error());
}

 */
function Tiendas ($sede){

    $bd = array (
        "Previa Shop"=> 'PREVIA_A',
        "Comercial Merina"=> 'MERINA21',
        "Comercial Merina III"=> 'MRIA3A21',
        "Comercial Corina I"=>'CORINA21',
        "Comercial Corina II"=>'CORI2_21',
        "Comercial Punto Fijo"=> 'PUFIJO21',
        "Comercial Matur"=> 'MATURA21',
        "Comercial Valena"=> 'VALENA21',
        "Comercial Trina"=> 'TRAINA21',
        "Comercial Kagu"=> 'KAGUA21',
        "Comercial Nachari"=> 'NACHAR21',
        "Comercial Higue"=> 'HIGUE21',
        "Comercial Apura"=>'APURA21',
        "Comercial Vallepa"=> 'VALLEP21',
        "Comercial Ojena"=> 'OJENA21',
        "Comercial Puecruz"=> 'PUECRU21',
        "Comercial Acari"=> 'ACARI21',
        "Comercial Catica II" => 'CATICA21',
    );

}



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
    $consulta = sqlsrv_query($conn, $sql);

    $i=0;
    while ($row = sqlsrv_fetch_array($consulta)) {
        
        $co_lin[] =  $row;
        $i++;
    }

    $res = $co_lin;
    return $res ;
}


function getArt ($sede,$linea){


    try {

        $serverName = "172.16.1.19";
        $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        $sql = "SELECT co_lin,lin_des from lin_art";
        $consulta = sqlsrv_query($conn, $sql);

    } catch (\Throwable $th) {
        
        throw $th;
    }





}