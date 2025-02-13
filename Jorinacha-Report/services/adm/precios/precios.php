<?php


/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA*/


require "../../services/empresas.php";

function Cliente($sede)
{

    $bd = array(
        "Sucursal Caracas I"    =>     'S01',
        "Sucursal Caracas II"    =>     'S02',
        "Sucursal Cagua" => 'S03',
        "Sucursal Maturin" => 'S04',
        "Sucursal Coro1" => 'S05',
        "Sucursal Coro2" => 'S06',
        "Sucursal Coro3" => 'S07',


        "Comercial Acari"    =>     'T04',
        "Comercial Puecruz"    =>     'T05',
        "Comercial Vallepa"    =>     'T06',

        "Comercial Higue"    =>     'T09',
        "Comercial Valena"    =>     'T10',
        "Comercial Ojena"    =>     'T12',
        "Comercial Punto Fijo"    =>     'T13',
        "Comercial Trina"    =>     'T16',
        "Comercial Apura"    =>     'T17',
        "Comercial Corina I"    =>     'T18',
        "Comercial Nachari"    =>     'T19',
        "Comercial Corina II"    =>     'T22',
        "Comercial Catica II"    =>     'T24',
    );

    return $bd[$sede];
}




/* CONSULTAR TODAS LAS LINEA DE ARTICULOS*/
function getLin_art_all()
{


    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT LTRIM(RTRIM(lin_art.co_lin)) as co_lin, LTRIM(RTRIM(lin_art.lin_des)) as lin_des from lin_art 
    INNER JOIN art ON lin_art.co_lin=art.co_lin
    WHERE art.fe_us_in >='20180101'
    GROUP BY lin_art.co_lin,lin_art.lin_des
    ORDER BY lin_art.lin_des";

    $consulta = sqlsrv_query($conn, $sql);


    while ($row = sqlsrv_fetch_array($consulta)) {

        $lin_art[] =  $row;
    }

    $res = $lin_art;
    return $res;
}




/* CONSULTAR ARTICULOS */


/* CONSULTAR ARTICULOS */
function getArt($sede, $linea, $co_art, $almacen)
{

    $database = Database($sede);
    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            #$sql ="EXEC getArt '$sede' , '$co_art', '$linea'  ";

            if ($sede == 'Previa Shop' and $almacen == 0 and $co_art == 0) {

                $sql = "SELECT   LTRIM(RTRIM(art.co_art)) as  co_art ,LTRIM(RTRIM(sub_lin.subl_des)) as  co_subl,LTRIM(RTRIM(cat_art.cat_des)) as  co_cat,
                prec_vta3,prec_vta4,prec_vta5,art.stock_act , LTRIM(RTRIM(colores.des_col)) as co_color, LTRIM(RTRIM(lin_art.lin_des)) as co_lin,art.ubicacion
                from art 
                JOIN lin_art on art.co_lin = lin_art.co_lin
                JOIN sub_lin on art.co_subl = sub_lin.co_subl
                JOIN cat_art on art.co_cat=cat_art.co_cat
                JOIN colores on art.co_color=colores.co_col
                where art.co_lin='$linea' AND art.prec_vta5 >= 1 AND art.anulado=0 AND art.fe_us_in >= '20190101'
                order by art.co_subl   desc";
            } elseif ($almacen == 'BOLE' and $sede == 'Previa Shop' and $co_art != 0) {

                $sql = "SELECT stock_act FROM st_almac WHERE co_art='$co_art' AND co_alma='BOLE'";
            } else {

                    $sql = "SELECT  LTRIM(RTRIM(art.co_art)) as  co_art ,LTRIM(RTRIM(sub_lin.subl_des)) as  co_subl,LTRIM(RTRIM(cat_art.cat_des)) as  co_cat,
                    prec_vta3,prec_vta4,prec_vta5,art.stock_act , LTRIM(RTRIM(colores.des_col)) as co_color, LTRIM(RTRIM(lin_art.lin_des)) as co_lin,art.ubicacion
                    from art 
                    JOIN lin_art on art.co_lin = lin_art.co_lin
                    JOIN sub_lin on art.co_subl = sub_lin.co_subl
                    JOIN cat_art on art.co_cat=cat_art.co_cat
                    JOIN colores on art.co_color=colores.co_col
                    where  art.prec_vta5 >= 1 AND art.co_art='$co_art' ";

            }




            $consulta = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($consulta)) {

                $art[] = $row;
            }
            $res = $art;
            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return null;
    }
}



/* CONSULTAR ARTICULOS VENDIDOS*/
function getReng_fac($sede,  $co_art, $fecha1, $fecha2)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            #$sql = "EXEC getReng_fac '$co_art' , '$fecha1' , '$fecha2'";
            $sql = "SELECT reng_fac.co_art,sum(reng_fac.total_art) as total_art 
            from reng_fac INNER JOIN factura ON reng_fac.fact_num=factura.fact_num
            where reng_fac.co_art='$co_art' and reng_fac.fec_lote BETWEEN '$fecha1'  AND '$fecha2' AND factura.anulada=0
            GROUP BY reng_fac.co_art";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $reng_fac = $row['total_art'];
                    break;
                }
                $res = $reng_fac;
            } else {
                $res = 0;
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


function getPedidos_t($sede,  $co_art)
{


    $cliente = Cliente($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            # $sql = "EXEC getPedidos_t ,'$cliente', '$co_art' ";

            $sql = "SELECT CONVERT(numeric(10,0),SUM(reng_ped.total_art)) AS  total_art
           from pedidos
           JOIN reng_ped ON pedidos.fact_num=reng_ped.fact_num
           where pedidos.anulada=0 AND pedidos.status = 0 AND pedidos.co_cli='$cliente' AND reng_ped.co_art='$co_art' AND reng_ped.co_alma = 'BOLE' ";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $reng_ped = $row['total_art'];
                    break;
                }
                $res = $reng_ped;
            } else {
                $res = 0;
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}





function getPedidos($sede, $co_art)
{

    #$database = Database($sede);
    $cliente = Cliente($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT pedidos.fact_num ,CONVERT(numeric(10,0),SUM(reng_ped.total_art)) as total_art ,pedidos.status  
            FROM reng_ped INNER JOIN pedidos ON reng_ped.fact_num=pedidos.fact_num
            WHERE reng_ped.co_art ='$co_art'  AND  pedidos.co_cli='$cliente'  AND  pedidos.anulada = 0 AND pedidos.status <= 1	AND reng_ped.co_alma = 'BOLE'
            GROUP BY pedidos.fact_num,pedidos.status ,fe_us_in
            ORDER BY fe_us_in DESC";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $pedidos['fact_num'] = $row['fact_num'];
                    $pedidos['total_art'] = $row['total_art'];
                    $pedidos['status'] = $row['status'];
                    $pedidos['doc'] = 'Ped';

                    break;
                }
                $res = $pedidos;
            } else {

                $pedidos['status'] = 3;
                $pedidos['total_art'] = 0;
                $res = $pedidos;
            }
            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT SUM(CONVERT(numeric(10,0), reng_ped.total_art)) as total_art 
            FROM reng_ped INNER JOIN pedidos ON reng_ped.fact_num=pedidos.fact_num
            WHERE reng_ped.co_art ='$co_art' and   pedidos.status <=1 and  pedidos.anulada = 0 AND reng_ped.co_alma = 'BOLE'";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {


                    $pedidos['total_art'] = $row['total_art'];


                    break;
                }


                $res = $pedidos;
            } else {

                $pedidos['total_art'] = 0;
                $res = $pedidos;
            }
            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    }
}


function getBultos($co_art)
{


    #$database = Database($sede);
    #$cliente = Cliente($sede);
    #

    try {


        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        $sql = "SELECT TOP 1 total_art from reng_fac 
        where co_art='$co_art'
        order by fact_num desc";

        $consulta = sqlsrv_query($conn, $sql);

        if ($consulta != null) {

            while ($row = sqlsrv_fetch_array($consulta)) {


                $reng_fac = $row['total_art'];


                break;
            }


            $res = $reng_fac;
        } else {



            $reng_fac = 0;
            $res = $reng_fac;
        }

        return $res;
    } catch (\Throwable $th) {

        throw $th;
    }
}







function getArtBultos($co_art)
{


    #$database = Database($sede);
    #$cliente = Cliente($sede);
    #

    try {


        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        $sql = "SELECT uni_relac from art where co_art='$co_art'";

        $consulta = sqlsrv_query($conn, $sql);


        if ($consulta != null) {


            while ($row = sqlsrv_fetch_array($consulta)) {


                $art = $row['uni_relac'];


                break;
            }


            $res = $art;
        } else {



            $art = 0;
            $res = $art;
        }

        return $res;
    } catch (\Throwable $th) {

        throw $th;
    }
}
