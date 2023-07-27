<?php




$sedes_tiendas = array(
    "DEV_EMP",
    "PREVIA_A",
    "CARACAS1",
    "CARACAS2",
    "CORINA1",
    "CORINA2",
    "PUFIJO",
    "MATUR",
    "VALENA",
    "TRINA",
    "CAGUA" ,
    "NACHARI",
    "HIGUE",
    "APURA",
    "VALLEPA",
    "OJENA",
    "PUECRUZ",
    "ACARI",
    "CATICA2",
);


$marcas = array(
     '121',
     'A29',
     '123',
     'A33',
     '186',
     '187',
     '188',
     '203',
     '205',
     '327',
     '345',
     'A23',
     '417',
     '466',
     'A30',
     'A27',
     '509',
     '510',
     '511',
     '538',
     '539',
     '601',
     '628',
     'A10',
     'A34',
     '184',
     'A36',
     'A37',
     'A13',
    
);



function getInv_fis($marca,$database)
{


    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    $sql = " SELECT  
    sum(stock_real)as STOCK_ACTUAL, 
    SUM(stock_real * art.prec_vta4)AS COSTO, 
    SUM(stock_real * art.prec_vta5) AS PRECIO
    FROM reng_fis
    join art on art.co_art = reng_fis.co_art 
    where art.co_lin= '$marca'";

    $consulta = sqlsrv_query($conn, $sql);


    while ($row = sqlsrv_fetch_array($consulta)) {

        $inv_fis[] =  $row;
    }

    $res = $inv_fis;
    return $res;
}


function getInv_fis_teorico($marca,$database)
{


    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    $sql = "  SELECT  
    reng_fis.co_art, 
    stock_teor from reng_fis
    inner join art on art.co_art = reng_fis.co_art 
    group by reng_fis.co_art  , reng_fis.stock_teor 
    order by reng_fis.co_art  
    where art.co_lin= '$marca'";

    $consulta = sqlsrv_query($conn, $sql);


    while ($row = sqlsrv_fetch_array($consulta)) {

        $inv_fis[] =  $row;
    }

    $res = $inv_fis;
    return $res;
}

