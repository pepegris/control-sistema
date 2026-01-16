<?php




$sedes_tiendas = array(
    "DEV_EMP",
    "TPLIO1A",
    'PREVIA_A',
    'CARACAS1',
    'CAGUA',
    'MATURIN',
    'CORO1',
    'CORO2',
    'CORO3',
    'PTOFIJO1',
    'PTOFIJO2',
    'OJEDA',
    'VAPASCUA',
    'GUIGUE',
    'PUERTO',
    'ACARIGUA',

    'HIGUE',
    'VALENA',
    'APURA',

);




function getLin_art($fecha,$database,$alma)
{




    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

/*     $sql = "SELECT lin_art.lin_des, art.co_lin from reng_fis
    JOIN art ON reng_fis.co_art = art.co_art
    JOIN lin_art ON lin_art.co_lin = art.co_lin
    JOIN fisico ON reng_fis.num_fis = fisico.num_fis
    where fisico.fecha_fis='$fecha'
    group by lin_art.lin_des , art.co_lin order by lin_art.lin_des"; 
    -----------------------------------------------------------------------------------------------------
    $sql = "SELECT lin_art.lin_des ,art.co_lin from st_almac
    inner join art on art.co_Art = st_almac.co_art
    inner join lin_art on lin_art.co_lin = art.co_lin
    where st_almac.stock_act >= 1 and co_alma = '$alma' 
    group by art.co_lin , lin_art.lin_des
    order by  lin_art.lin_des"; */

    $sql = "SELECT DISTINCT lin_art.lin_des, art.co_lin 
    from reng_fis
   JOIN art ON reng_fis.co_art = art.co_art
   JOIN lin_art ON lin_art.co_lin = art.co_lin
   JOIN fisico ON reng_fis.num_fis = fisico.num_fis
   where fisico.fecha_fis>='$fecha'
   UNION
   SELECT DISTINCT lin_art.lin_des ,art.co_lin 
   from st_almac
   inner join art on art.co_Art = st_almac.co_art
   inner join lin_art on lin_art.co_lin = art.co_lin
   where st_almac.stock_act >= 1 and co_alma = '$alma' 
   group by art.co_lin , lin_art.lin_des
   order by  lin_art.lin_des"; 

    

    $consulta = sqlsrv_query($conn, $sql);

    $r=0;
    while ($row = sqlsrv_fetch_array($consulta)) {

        $lin_des[$r]['lin_des'] =  $row['lin_des'];
        $lin_des[$r]['co_lin'] =  $row['co_lin'];
        $r++;
    }

    $res = $lin_des;
    return $res;
}



/* function getLin_art2($marca)
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
 */

function getInv_fis($marca,$database,$fecha1)
{


    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    $sql = " SELECT  
    CONVERT(numeric(10,0),sum(stock_real))as STOCK_ACTUAL, 
    CONVERT(numeric(10,1),SUM(stock_real * art.prec_vta4))AS COSTO, 
    CONVERT(numeric(10,0),SUM(stock_real * art.prec_vta5)) AS PRECIO
    FROM reng_fis
    join art on art.co_art = reng_fis.co_art 
    INNER JOIN fisico ON fisico.num_fis=reng_fis.num_fis
    where art.co_lin= '$marca' and fisico.fecha_fis>='$fecha1'";

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


function getInv_fis_teorico($marca,$database,$fecha1,$alma )
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
    CONVERT(numeric(10,0),ST.stock_act *  art.prec_vta5) AS precio
	FROM st_almac AS ST
	INNER JOIN art 
	ON art.co_art=ST.co_art
	WHERE ST.co_alma='$alma' AND ST.stock_act>0 and art.co_lin='$marca'
	UNION
	SELECT DISTINCT RF.co_art,
    CONVERT(numeric(10,0),RF.stock_teor) as stock_teor ,
    CONVERT(numeric(10,1),stock_teor *  art.prec_vta4) as costo ,
    CONVERT(numeric(10,0),stock_teor *  art.prec_vta5) AS precio
	FROM reng_fis AS RF
	INNER JOIN fisico AS F
	ON F.num_fis=RF.num_fis
	INNER JOIN art 
	ON art.co_art=RF.co_art
	WHERE RF.co_alma='$alma'  and art.co_lin='$marca' and f.fecha_fis>='$fecha1' ";

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



function getreng_stock_teorico($marca,$database,$fecha1,$alma)
{



    if ($database) {
        try {
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $sql = "  SELECT DISTINCT ST.co_art,art.art_des,
            CONVERT(numeric(10,0),ST.stock_act) as stock_teor ,
            CONVERT(numeric(10,1),art.prec_vta4) as costo , 
            CONVERT(numeric(10,1),ST.stock_act *  art.prec_vta4) as total_costo_teorico ,
            CONVERT(numeric(10,0),art.prec_vta5) as precio, 
            CONVERT(numeric(10,0),ST.stock_act *  art.prec_vta5) AS total_precio_teorico
            FROM st_almac AS ST
            INNER JOIN art 
            ON art.co_art=ST.co_art
            WHERE ST.co_alma='$alma' AND ST.stock_act>0 and art.co_lin='$marca'

            UNION

            SELECT DISTINCT RF.co_art,art.art_des,
            CONVERT(numeric(10,0),RF.stock_teor) as stock_teor ,
            CONVERT(numeric(10,1),art.prec_vta4) as costo , 
            CONVERT(numeric(10,1),stock_teor *  art.prec_vta4) as total_costo_teorico ,
            CONVERT(numeric(10,0),art.prec_vta5) as precio,
            CONVERT(numeric(10,0),stock_teor *  art.prec_vta5) AS total_precio_teorico
            FROM reng_fis AS RF
            INNER JOIN fisico AS F
            ON F.num_fis=RF.num_fis
            INNER JOIN art 
            ON art.co_art=RF.co_art
            WHERE RF.co_alma='$alma'  and art.co_lin='$marca' and f.fecha_fis>='$fecha1' ";

            $consulta = sqlsrv_query($conn, $sql);


            while ($row = sqlsrv_fetch_array($consulta)) {

                $inv_fis[] = $row;
            }

                return $inv_fis;

            } catch (\Throwable $th) {

                throw $th;
            }
        } else {
    
            return 0;
        }

}



function getreng_stock_real($marca,$database,$fecha1,$co_art)
{

    if ($database) {
        try {
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = " 	SELECT 
            CONVERT(numeric(10,0),SUM(RF.stock_real)) as stock_real ,
            CONVERT(numeric(10,1),SUM(stock_real *  art.prec_vta4)) as total_costo_real ,
            CONVERT(numeric(10,0),SUM(stock_real *  art.prec_vta5)) AS total_precio_real
            FROM reng_fis AS RF
            INNER JOIN fisico AS F
            ON F.num_fis=RF.num_fis
            INNER JOIN art 
            ON art.co_art=RF.co_art
            WHERE art.co_lin='$marca' and f.fecha_fis>='$fecha1' and rf.co_Art='$co_art' ";

            $consulta = sqlsrv_query($conn, $sql);


            while ($row = sqlsrv_fetch_array($consulta)) {

                $inv_fis['stock_real'] = $row['stock_real'];
                $inv_fis['total_costo_real'] = $row['total_costo_real'];
                $inv_fis['total_precio_real'] = $row['total_precio_real'];
            }

                return $inv_fis;

            } catch (\Throwable $th) {

                throw $th;
            }
        } else {
    
            return 0;
        }

}
