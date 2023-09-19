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




function getLin_art($marca)
{


    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    $sql = "  SELECT lin_des FROM lin_art WHERE co_lin='$marca'";

    $consulta = sqlsrv_query($conn, $sql);


    while ($row = sqlsrv_fetch_array($consulta)) {

        $lin_des['lin_des'] =  $row['lin_des'];
    }

    $res = $lin_des;
    return $res;
}


function getInv_fis($marca,$database,$fecha1)
{


    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    $sql = " SELECT  
    CONVERT(numeric(10,0),sum(stock_real))as STOCK_ACTUAL, 
    CONVERT(numeric(10,1),SUM(stock_real * art.prec_vta4))AS COSTO, 
    CONVERT(numeric(10,1),SUM(stock_real * art.prec_vta5)) AS PRECIO
    FROM reng_fis
    join art on art.co_art = reng_fis.co_art 
    INNER JOIN fisico ON fisico.num_fis=reng_fis.num_fis
    where art.co_lin= '$marca' and fisico.fecha_fis='$fecha1'";

    $consulta = sqlsrv_query($conn, $sql);


    while ($row = sqlsrv_fetch_array($consulta)) {

        $inv_fis['STOCK_ACTUAL'] = $row['STOCK_ACTUAL'];
        $inv_fis['COSTO'] =  $row['COSTO'];
        $inv_fis['PRECIO'] =  $row['PRECIO'];
    }

    if ($inv_fis['STOCK_ACTUAL'] != null) {
        $res = $inv_fis;
        return $res;
    }else{
        $inv_fis['STOCK_ACTUAL'] = 0;
        $inv_fis['COSTO'] =  0;
        $inv_fis['PRECIO'] =  0;
        $res = $inv_fis;
        return $res;
    }


}


function getInv_fis_teorico($marca,$database,$fecha1 )
{


    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    /*$sql = "  SELECT   
    CONVERT(numeric(10,0),reng_fis.stock_teor) as stock_teor ,
    CONVERT(numeric(10,0),stock_teor *  art.prec_vta4) as costo , 
    CONVERT(numeric(10,0),stock_teor *  art.prec_vta5) AS precio
    from reng_fis
    inner join art on art.co_art = reng_fis.co_art 
    where art.co_lin= '$marca'
    group by reng_fis.co_art  , reng_fis.stock_teor , art.prec_vta4, art.prec_vta5
    order by reng_fis.co_art  
    ";*/

    $sql = "  SELECT DISTINCT ST.co_art,
    CONVERT(numeric(10,0),ST.stock_act) as stock_teor ,
    CONVERT(numeric(10,1),ST.stock_act *  art.prec_vta4) as costo ,
    CONVERT(numeric(10,1),ST.stock_act *  art.prec_vta5) AS precio
	FROM st_almac AS ST
	INNER JOIN art 
	ON art.co_art=ST.co_art
	WHERE ST.co_alma=1 AND ST.stock_act>0 and art.co_lin='$marca'
	UNION
	SELECT DISTINCT RF.co_art,
    CONVERT(numeric(10,0),RF.stock_teor) as stock_teor ,
    CONVERT(numeric(10,1),stock_teor *  art.prec_vta4) as costo ,
    CONVERT(numeric(10,1),stock_teor *  art.prec_vta5) AS precio
	FROM reng_fis AS RF
	INNER JOIN fisico AS F
	ON F.num_fis=RF.num_fis
	INNER JOIN art 
	ON art.co_art=RF.co_art
	WHERE RF.co_alma=1  and art.co_lin='$marca' and f.fecha_fis='$fecha1' ";

    $consulta = sqlsrv_query($conn, $sql);

    $inv_fis['stock_teor'] = 0;
    $inv_fis['costo'] =  0;
    $inv_fis['precio'] =  0;

    while ($row = sqlsrv_fetch_array($consulta)) {

        $inv_fis['stock_teor'] += $row['stock_teor'];
        $inv_fis['costo'] +=  $row['costo'];
        $inv_fis['precio'] +=  $row['precio'];
    }

    if ($inv_fis['stock_teor'] != null) {
        $res = $inv_fis;
        return $res;
    }else{
        $inv_fis['stock_teor'] = 0;
        $inv_fis['costo'] =  0;
        $inv_fis['precio'] =  0;
        $res = $inv_fis;
        return $res;
    }
}

/* MARCAS QUE SE TRABAJARON CONSULTA PARA SABER QUE MARCAS SE TRABAJARON*/
/* MARCAS QUE SE TRABAJARON CONSULTA PARA SABER QUE MARCAS SE TRABAJARON*/
/*
select  lin_art.co_lin from reng_fis 
inner join art on art.co_art = reng_fis.co_art 
inner join lin_art on art.co_lin = lin_art.co_lin 
group by  lin_art.co_lin 
*/
/* MARCAS QUE SE TRABAJARON CONSULTA PARA SABER QUE MARCAS SE TRABAJARON*/
/* MARCAS QUE SE TRABAJARON CONSULTA PARA SABER QUE MARCAS SE TRABAJARON*/
