<?php

/* $serverName = "172.16.1.19"; 
$connectionInfo = array( "Database"=>"PREVIA_A", "UID"=>"mezcla", "PWD"=>"Zeus33$");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if (!$conn) {
    die("Connection failed: " . sqlsrv_connect_error());
}

 */
/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA*/
function Database($sede)
{

    $bd = array(
        "Previa Shop" => 'PREVIA_A',
        "Comercial Merina" => 'MERINA21',
        "Comercial Merina III" => 'MRIA3A21',
        "Comercial Corina I" => 'CORINA21',
        "Comercial Corina II" => 'CORI2_21',
        "Comercial Punto Fijo" => 'PUFIJO21',
        "Comercial Matur" => 'MATURA21',
        "Comercial Valena" => 'VALENA21',
        "Comercial Trina" => 'TRAINA21',
        "Comercial Kagu" => 'KAGUA21',
        "Comercial Nachari" => 'NACHAR21',
        "Comercial Higue" => 'HIGUE21',
        "Comercial Apura" => 'APURA21',
        "Comercial Vallepa" => 'VALLEP21',
        "Comercial Ojena" => 'OJENA21',
        "Comercial Puecruz" => 'PUECRU21',
        "Comercial Acari" => 'ACARI21',
        "Comercial Catica II" => 'CATICA21',
    );

    return $bd[$sede];
}

function Cliente($sede)
{

    $bd = array(
        "Comercial Merina"    =>     'T20',
        "Comercial Merina III"    =>     'T23',
        "Comercial Corina I"    =>     'T18',
        "Comercial Corina II"    =>     'T22',
        "Comercial Punto Fijo"    =>     'T13',
        "Comercial Matur"    =>     'T07',
        "Comercial Valena"    =>     'T10',
        "Comercial Trina"    =>     'T16',
        "Comercial Kagu"    =>     'T03',
        "Comercial Nachari"    =>     'T19',
        "Comercial Higue"    =>     'T09',
        "Comercial Apura"    =>     'T17',
        "Comercial Vallepa"    =>     'T06',
        "Comercial Ojena"    =>     'T12',
        "Comercial Puecruz"    =>     'T05',
        "Comercial Acari"    =>     'T04',
        "Comercial Catica II"    =>     'T24',

    );

    return $bd[$sede];
}


/* CONSULTAR TALLA DE ARTICULOS */
function getCat_art($co_cat)
{


    $serverName = "172.16.1.19";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT cat_des FROM cat_art WHERE co_cat = '$co_cat'";
    $consulta = sqlsrv_query($conn, $sql);
    while ($row = sqlsrv_fetch_array($consulta)) {

        $cat_art =  $row['cat_des'];
    }

    $res = $cat_art;

    return $res;
}

/* CONSULTAR MARCA DE ARTICULOS */
function getSub_lin($co_subl)
{


    $serverName = "172.16.1.19";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT subl_des from sub_lin where co_subl ='$co_subl'";
    $consulta = sqlsrv_query($conn, $sql);
    while ($row = sqlsrv_fetch_array($consulta)) {

        $sub_lin =  $row['subl_des'];
    }

    $res = $sub_lin;

    return $res;
}


/* CONSULTAR LINEA DE ARTICULOS*/
function getLin_art($co_lin)
{


    $serverName = "172.16.1.19";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT lin_des from lin_art WHERE co_lin='$co_lin' ";
    $consulta = sqlsrv_query($conn, $sql);


    while ($row = sqlsrv_fetch_array($consulta)) {

        $lin_art =  $row['lin_des'];
    }

    $res = $lin_art;
    return $res;
}
/* CONSULTAR TODAS LAS LINEA DE ARTICULOS*/
function getLin_art_all()
{


    $serverName = "172.16.1.19";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT co_lin,lin_des from lin_art  ";
    $consulta = sqlsrv_query($conn, $sql);


    while ($row = sqlsrv_fetch_array($consulta)) {

        $lin_art[] =  $row;
    }

    $res = $lin_art;
    return $res;
}


/* CONSULTAR COLOR DE ARTICULOS*/
function getColores($co_col)
{


    $serverName = "172.16.1.19";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT des_col FROM colores WHERE co_col='$co_col'";
    $consulta = sqlsrv_query($conn, $sql);


    while ($row = sqlsrv_fetch_array($consulta)) {

        $co_col =  $row['des_col'];
    }

    $res = $co_col;
    return $res;
}

/* CONSULTAR ARTICULOS */
function getArt($sede, $linea, $co_art)
{

    $database = Database($sede);
    if ($database) {
        try {

            $serverName = "172.16.1.19";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($linea === 'todos') {

                if ($database == 'PREVIA_A') {

                    $sql = "SELECT LTRIM(RTRIM(art.co_art)) as  co_art ,LTRIM(RTRIM(art.co_subl)) as  co_subl,LTRIM(RTRIM(art.co_cat)) as  co_cat,
                    prec_vta1,prec_vta5,st_almac.stock_act, co_color , co_lin
                    from st_almac inner join art on st_almac.co_art=art.co_art 
                    where   st_almac.co_alma='BOLE' AND art.prec_vta5 >=1
                    order by art.co_subl  desc ";
                } else {

                    $sql = "SELECT LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat) as  co_cat  , 
                    co_color , co_cat , co_lin , stock_act , prec_vta1 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5 
                    from art WHERE prec_vta5 >= 1 ORDER BY co_subl  DESC";
                }
            } elseif ($co_art == 0) {

                if ($database == 'PREVIA_A') {

                    $sql = "SELECT LTRIM(RTRIM(art.co_art)) as  co_art ,LTRIM(RTRIM(art.co_subl)) as  co_subl,LTRIM(RTRIM(art.co_cat)) as  co_cat,
                    prec_vta1,prec_vta5,st_almac.stock_act , co_color, co_lin
                    from st_almac inner join art on st_almac.co_art=art.co_art 
                    where art.co_lin='$linea' and st_almac.co_alma='BOLE' AND art.prec_vta5 >=1
                    order by art.co_subl   desc";
                } else {

                    $sql = "SELECT LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat)) as  co_cat  ,
                    co_color , co_lin , stock_act , prec_vta1 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5 
                    from art  where co_lin= '$linea' AND prec_vta5 >= 1 ORDER BY co_subl  DESC";
                }
            } else {

                $sql = "SELECT LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat)) as  co_cat  ,
                co_color , co_lin , stock_act , prec_vta1 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5 
                from art  where co_lin= '$linea' AND prec_vta5 >= 1 AND co_art='$co_art'";
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


function getArt_stock_tiendas($sede,  $co_art)
{

    $database = Database($sede);
    if ($database) {
        try {

            $serverName = "172.16.1.19";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT  stock_act from art WHERE prec_vta5 >= 1 AND co_art='$co_art' AND prec_vta5 >= 1 ";

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

            $serverName = "172.16.1.19";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT co_art,sum(total_art) as total_art from reng_fac where co_art='$co_art' and fec_lote BETWEEN '$fecha1'  AND '$fecha2' GROUP BY co_art";

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

/* CONSULTAR FACTURA DE COMPRA DE LOS ARTTICULOS*/
function getCompras($co_art)
{


    $serverName = "172.16.1.19";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT top 1  co_art,fact_num,fec_lote,total_art,prec_vta from reng_com  where co_art ='$co_art' order by fec_lote desc";
    $consulta = sqlsrv_query($conn, $sql);

    if ($consulta) {
        while ($row = sqlsrv_fetch_array($consulta)) {

            $co_col['co_art'] =  $row['co_art'];
            $co_col['fact_num'] =  $row['fact_num'];
            $co_col['fec_lote'] =   $row['fec_lote'];
            $co_col['total_art'] =   number_format($row['total_art'], 0, ',', '.');
            $co_col['prec_vta'] =   number_format($row['prec_vta'], 2, ',', '.');
            break;
        }
        $res = $co_col;
    } else {


        $res = 0;
    }

    return $res;
}

/* FACTURA DE EL ULTIMO ARTICULOS VENDIDO EN PREVIA */

function getFactura($sede, $co_art, $fecha1, $fecha2)
{
    $cliente = Cliente($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.19";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT top 1  factura.fact_num,total_art 
            FROM reng_fac
            INNER JOIN factura ON reng_fac.fact_num=factura.fact_num
            WHERE reng_fac.co_art='$co_art' and factura.co_cli='$cliente' and factura.fe_us_in BETWEEN '$fecha1'  AND '$fecha2'
            ORDER BY fe_us_in DESC";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art = number_format($row['total_art'], 2, ',', '.');
                    break;
                }
                $res = $total_art;
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

/* OBTENER LAS COTIZACIONES Y PEDIDOS DE LOS ARTICULOS POR DESPACHAR */
    /*
    ESTATUS DE LOS PEDIDOS Y COTIZACION
0 sin procesar
1 Parc/Procesada
2 Procesada
*/
/* 
function getCot_Ped($sede, $co_art)
{



    #$database = Database($sede);
    $cliente = Cliente($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.19";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            #consulto pedidos primero a ver si existe un pedido 
            $sql_pedido = "SELECT top 1 pedidos.fact_num , reng_ped.total_art,pedidos.status 
            FROM reng_ped INNER JOIN pedidos ON reng_ped.fact_num=reng_ped.total_art
            WHERE reng_ped.co_art ='$co_art'  AND  pedidos.co_cli='$cliente'
            ORDER BY fe_us_in DESC";

            $consulta_pedido = sqlsrv_query($conn, $sql_pedido);

            if ($consulta_pedido != null) {

                while ($row = sqlsrv_fetch_array($consulta_pedido)) {

                    $total_art['total_art'] = number_format($row['total_art'], 2, ',', '.');
                    $total_art['status'] = $row['status'];
                    $total_art['doc'] = 'Ped';
                    break;
                }
                $res = $total_art;
            } else {
                # si no existe un pedido consulto si hay una cotizacion 
                $sql_cotizacion = "SELECT top 1 cotiz_c.fact_num,reng_cac.total_art,cotiz_c.status  
                FROM reng_cac INNER JOIN cotiz_c ON reng_cac.fact_num=cotiz_c.fact_num
                WHERE reng_cac.co_art ='$co_art' and cotiz_c.co_cli='$cliente'
                ORDER BY fe_us_in DESC";

                $consulta_cotizacion = sqlsrv_query($conn, $sql_cotizacion);

                if ($consulta_cotizacion) {
                    while ($row = sqlsrv_fetch_array($consulta_cotizacion)) {

                        $total_art['total_art'] = number_format($row['total_art'], 2, ',', '.');
                        $total_art['status'] = $row['status'];
                        $total_art['doc'] = 'Cot';
                        break;
                    }
                    $res = $total_art;
                }else{
                    return 0;
                }

            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
} */

function getCotizacion ($sede, $co_art)
{

    #$database = Database($sede);
    $cliente = Cliente($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.19";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT top 1 cotiz_c.fact_num,reng_cac.total_art,cotiz_c.status  
            FROM reng_cac INNER JOIN cotiz_c ON reng_cac.fact_num=cotiz_c.fact_num
            WHERE reng_cac.co_art ='$co_art' and cotiz_c.co_cli='$cliente'
            ORDER BY fe_us_in DESC";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art['total_art'] = number_format($row['total_art'], 0, ',', '.');
                    $total_art['status'] = $row['status'];
                    break;
                }
                $res = $total_art;
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


function getPedidos ($sede, $co_art)
{

    #$database = Database($sede);
    $cliente = Cliente($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.19";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT top 1 pedidos.fact_num , reng_ped.total_art,pedidos.status 
            FROM reng_ped INNER JOIN pedidos ON reng_ped.fact_num=reng_ped.total_art
            WHERE reng_ped.co_art ='$co_art'  AND  pedidos.co_cli='$cliente'
            ORDER BY fe_us_in DESC";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art['total_art'] = number_format($row['total_art'], 0, ',', '.');
                    $total_art['status'] = $row['status'];
                    $total_art['doc'] = 'Ped';
                    break;
                }
                $res = $total_art;
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
