<?php


/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA*/
$serverName = "172.16.1.39";
$connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");

$sedes_ar = array(
    "Previa Shop",
    "Comercial Merina",
    "Comercial Merina III",
    "Comercial Corina I",
    "Comercial Corina II",
    "Comercial Punto Fijo",
    "Comercial Matur",
    "Comercial Valena",
    "Comercial Trina",
    "Comercial Kagu",
    "Comercial Nachari",
    "Comercial Higue",
    "Comercial Apura",
    "Comercial Vallepa",
    "Comercial Ojena",
    "Comercial Puecruz",
    "Comercial Acari",
    "Comercial Catica II",
);



function Database2($sede)
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
function Database($sede)
{

    $bd = array(
        "Previa Shop" => 'PREVIA_A',
        "Comercial Merina" => 'MERINA',
        "Comercial Merina III" => 'MERINA3',
        "Comercial Corina I" => 'CORINA1',
        "Comercial Corina II" => 'CORINA2',
        "Comercial Punto Fijo" => 'PUFIJO',
        "Comercial Matur" => 'MATUR',
        "Comercial Valena" => 'VALENA',
        "Comercial Trina" => 'TRINA',
        "Comercial Kagu" => 'KAGU',
        "Comercial Nachari" => 'NACHARI',
        "Comercial Higue" => 'HIGUE',
        "Comercial Apura" => 'APURA',
        "Comercial Vallepa" => 'VALLEPA',
        "Comercial Ojena" => 'OJENA',
        "Comercial Puecruz" => 'PUECRUZ',
        "Comercial Acari" => 'ACARI',
        "Comercial Catica II" => 'CATICA2',
    );

    return $bd[$sede];
}

function Cliente($sede)
{

    $bd = array(
        "Comercial Merina"    =>     'T15',
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


    $serverName = "172.16.1.39";
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


    $serverName = "172.16.1.39";
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


    $serverName = "172.16.1.39";
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


    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT lin_art.co_lin, lin_art.lin_des from lin_art 
            INNER JOIN art ON lin_art.co_lin=art.co_lin
            WHERE art.fe_us_in >='20180101'
            GROUP BY lin_art.co_lin,lin_art.lin_des";

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


    $serverName = "172.16.1.39";
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
function getArt($sede, $linea, $co_art, $almacen)
{

    $database = Database($sede);
    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($linea === 'todos') {

                if ($database == 'PREVIA_A') {

                    /*                     if ($almacen == 'todos') {
                        $sql="SELECT LTRIM(RTRIM(co_art)) as  co_art ,LTRIM(RTRIM(co_subl)) as  co_subl,LTRIM(RTRIM(co_cat)) as  co_cat,
                        prec_vta3,prec_vta5,stock_act, co_color , co_lin,art.ubicacion from art  WHERE prec_vta5 >=1 AND co_lin='$linea' order by co_subl  desc";

                    } else { */
                    $sql = "SELECT  LTRIM(RTRIM(art.co_art)) as  co_art ,LTRIM(RTRIM(art.co_subl)) as  co_subl,LTRIM(RTRIM(art.co_cat)) as  co_cat,
                        prec_vta3, CONVERT(numeric(10,0), prec_vta5),st_almac.stock_act, co_color , co_lin,art.ubicacion
                        from st_almac inner join art on st_almac.co_art=art.co_art 
                        where   st_almac.co_alma='BOLE' AND art.prec_vta5 >=1
                        order by art.co_subl  desc ";
                    /*                     } */
                } else {

                    $sql = "SELECT  LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat) as  co_cat  , 
                    co_color , co_cat , co_lin , stock_act , prec_vta3 , prec_vta2 , prec_vta3 ,prec_vta4 ,CONVERT(numeric(10,0), prec_vta5),art.ubicacion 
                    from art WHERE prec_vta5 >= 1 ORDER BY co_subl  DESC";
                }
            } elseif ($co_art == 0) {

                if ($database == 'PREVIA_A') {


                    /*                     if ($almacen == 'todos') {
                        $sql="SELECT LTRIM(RTRIM(co_art)) as  co_art ,LTRIM(RTRIM(co_subl)) as  co_subl,LTRIM(RTRIM(co_cat)) as  co_cat,
                        prec_vta3,prec_vta5,stock_act, co_color , co_lin,art.ubicacion from art  WHERE prec_vta5 >=1 AND co_lin='$linea' order by co_subl  desc";
                        
                    } else { */
                    $sql = "SELECT  LTRIM(RTRIM(art.co_art)) as  co_art ,LTRIM(RTRIM(art.co_subl)) as  co_subl,LTRIM(RTRIM(art.co_cat)) as  co_cat,
                    prec_vta3,prec_vta5,st_almac.stock_act , co_color, co_lin,art.ubicacion
                    from st_almac inner join art on st_almac.co_art=art.co_art 
                    where art.co_lin='$linea' and st_almac.co_alma='BOLE' AND art.prec_vta5 >=1
                    order by art.co_subl   desc";
                    /*                     } */
                } else {

                    $sql = "SELECT  LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat)) as  co_cat  ,
                    co_color , co_lin , stock_act , prec_vta3 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5,art.ubicacion 
                    from art  where co_lin= '$linea' AND prec_vta5 >= 1 ORDER BY co_subl  DESC";
                }
            } else {

                $sql = "SELECT  LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat)) as  co_cat  ,
                co_color , co_lin , stock_act , prec_vta1 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5 ,art.ubicacion
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



function getArt_todos($sede, $linea, $co_art, $almacen)
{

    $database = Database($sede);
    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($linea === 'todos') {

                if ($database == 'PREVIA_A') {

                    /*                     if ($almacen == 'todos') {
                        $sql="SELECT LTRIM(RTRIM(co_art)) as  co_art ,LTRIM(RTRIM(co_subl)) as  co_subl,LTRIM(RTRIM(co_cat)) as  co_cat,
                        prec_vta3,prec_vta5,stock_act, co_color , co_lin,art.ubicacion from art  WHERE prec_vta5 >=1 AND co_lin='$linea' order by co_subl  desc";

                    } else { */
                    $sql = "SELECT LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat)) as  co_cat  ,
                        co_color , co_lin , stock_act , prec_vta3 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5 ,art.ubicacion
                        from art  where co_lin= '$linea' AND prec_vta5 >= 1 ORDER BY co_subl  DESC";
                    /*                     } */
                } else {

                    $sql = "SELECT LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat) as  co_cat  , 
                    co_color , co_cat , co_lin , stock_act , prec_vta3 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5,art.ubicacion 
                    from art WHERE prec_vta5 >= 1 ORDER BY co_subl  DESC";
                }
            } elseif ($co_art == 0) {

                if ($database == 'PREVIA_A') {


                    /*                     if ($almacen == 'todos') {
                        $sql="SELECT LTRIM(RTRIM(co_art)) as  co_art ,LTRIM(RTRIM(co_subl)) as  co_subl,LTRIM(RTRIM(co_cat)) as  co_cat,
                        prec_vta3,prec_vta5,stock_act, co_color , co_lin,art.ubicacion from art  WHERE prec_vta5 >=1 AND co_lin='$linea' order by co_subl  desc";
                        
                    } else { */
                    $sql = "SELECT LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat)) as  co_cat  ,
                        co_color , co_lin , stock_act , prec_vta3 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5 ,art.ubicacion
                        from art  where co_lin= '$linea' AND prec_vta5 >= 1 ORDER BY co_subl  DESC";
                    /*                     } */
                } else {

                    $sql = "SELECT LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat)) as  co_cat  ,
                    co_color , co_lin , stock_act , prec_vta3 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5,art.ubicacion 
                    from art  where co_lin= '$linea' AND prec_vta5 >= 1 ORDER BY co_subl  DESC";
                }
            } else {

                $sql = "SELECT LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat)) as  co_cat  ,
                co_color , co_lin , stock_act , prec_vta3 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5 ,art.ubicacion
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

            $serverName = "172.16.1.39";
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

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

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

/* CONSULTAR FACTURA DE COMPRA DE LOS ARTTICULOS*/
function getCompras($co_art)
{


    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT top 1  co_art,fact_num,fec_lote,total_art,prec_vta from reng_com  where co_art ='$co_art'  order by fec_lote desc";
    $consulta = sqlsrv_query($conn, $sql);

    if ($consulta) {
        while ($row = sqlsrv_fetch_array($consulta)) {

            $co_col['co_art'] =  $row['co_art'];
            $co_col['fact_num'] =  $row['fact_num'];
            $co_col['fec_lote'] =   $row['fec_lote'];
            $co_col['total_art'] =   $row['total_art'];
            $co_col['prec_vta'] =   $row['prec_vta'];
            break;
        }
        $res = $co_col;
    } else {


        $res = 0;
    }

    return $res;
}

/* FACTURA DE EL ULTIMO ARTICULOS VENDIDO EN PREVIA */

function getFactura($sede, $co_art, $fecha1, $fecha2, $co_lin)
{
    $cliente = Cliente($sede);


    if ($cliente != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);



            if ($co_lin == null) {

                $sql = "SELECT CONVERT(numeric(10,0), SUM(total_art ) )as total_art
                FROM reng_fac
                INNER JOIN factura ON reng_fac.fact_num=factura.fact_num
                WHERE reng_fac.co_art='$co_art' and factura.co_cli='$cliente' and anulada = 0  and factura.fe_us_in BETWEEN '$fecha1'  AND '$fecha2'";
            } else {

                $sql = "SELECT CONVERT(numeric(10,0), SUM(reng_fac.total_art ) ) as total_art
                FROM reng_fac
                JOIN factura ON reng_fac.fact_num=factura.fact_num 
                JOIN art ON art.co_art = reng_fac.co_art
                WHERE art.co_lin='$co_lin'  AND  factura.fec_emis BETWEEN '$fecha1'  AND '$fecha2'  AND factura.co_cli='$cliente' AND factura.anulada=0";
            }



            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art = $row['total_art'];
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


function getFacturaCompras($sede,  $fecha1, $fecha2, $co_lin)
{
    $cliente = Cliente($sede);
    $database = Database($sede);


    if ($cliente != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT CONVERT(numeric(10,0), SUM(reng_com.total_art)) as total_art
            FROM reng_com
            JOIN compras ON reng_com.fact_num=compras.fact_num
            JOIN art ON art.co_art = reng_com.co_art
            WHERE compras.fec_emis BETWEEN '$fecha1' AND '$fecha2' AND compras.anulada=0 AND art.co_lin = '$co_lin'";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art = $row['total_art'];
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



function getAjustes($sede,  $fecha1, $fecha2, $co_lin, $tipo)
{
    $cliente = Cliente($sede);
    $database = Database($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            if ($tipo == 'EN') {

                $sql = "SELECT CONVERT(numeric(10,0), SUM(reng_aju.total_art) )as total_art
                FROM ajuste 
                JOIN reng_aju ON ajuste.ajue_num = reng_aju.ajue_num
                JOIN art ON art.co_art = reng_aju.co_art
                WHERE  ajuste.fecha BETWEEN '$fecha1' AND '$fecha2' AND art.co_lin='$co_lin'  AND reng_aju.tipo='EN' AND ajuste.anulada =0";
            } else {

                $sql = "SELECT CONVERT(numeric(10,0), SUM(reng_aju.total_art) )as total_art
                FROM ajuste 
                JOIN reng_aju ON ajuste.ajue_num = reng_aju.ajue_num
                JOIN art ON art.co_art = reng_aju.co_art
                WHERE  ajuste.fecha BETWEEN '$fecha1' AND '$fecha2' AND art.co_lin='$co_lin'  AND reng_aju.tipo='SAL' AND ajuste.anulada =0";
            }


            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art = $row['total_art'];
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


function getDevProveedor($sede,  $fecha1, $fecha2, $co_lin)
{

    $cliente = Cliente($sede);
    $database = Database($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT CONVERT(numeric(10,0), SUM(reng_dvp.total_art ) )as total_art
            from dev_pro
            JOIN reng_dvp ON dev_pro.fact_num=reng_dvp.fact_num
            JOIN art ON art.co_art=reng_dvp.co_art
            WHERE dev_pro.fec_emis BETWEEN '$fecha1' AND '$fecha2'  AND art.co_lin='$co_lin' AND dev_pro.anulada=0";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art = $row['total_art'];
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

function getCotizacion($sede, $co_art)
{

    #$database = Database($sede);
    $cliente = Cliente($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.39";
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
                    $total_art['doc'] = 'Cot';
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


function getPedidos($sede, $co_art)
{

    #$database = Database($sede);
    $cliente = Cliente($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT top 1 pedidos.fact_num ,CONVERT(numeric(10,0), reng_ped.total_art) as total_art ,pedidos.status 
            FROM reng_ped INNER JOIN pedidos ON reng_ped.fact_num=pedidos.fact_num
            WHERE reng_ped.co_art ='$co_art'  AND  pedidos.co_cli='$cliente'  and  pedidos.anulada = 0 
            ORDER BY fe_us_in DESC";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art['total_art'] = $row['total_art'];
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

        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT SUM(CONVERT(numeric(10,0), reng_ped.total_art)) as total_art 
            FROM reng_ped INNER JOIN pedidos ON reng_ped.fact_num=pedidos.fact_num
            WHERE reng_ped.co_art ='$co_art' and   pedidos.status <=1 and  pedidos.anulada = 0
            GROUP BY  pedidos.status ";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art['total_art'] = $row['total_art'];
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
    }
}


function getOrdenes_Pag($sede, $fecha)
{

    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($database == 'MERINA3' or $database == 'MERINA') {
                $sql = "SELECT monto,cod_ben from ord_pago where cod_ben ='47' and fecha ='$fecha' and anulada=0";
            } elseif ($database == 'KAGU' or $database == 'TRINA' or $database == 'CORINA1' or $database == 'CORINA2') {
                $sql = "SELECT monto,cod_ben from ord_pago where cod_ben ='95' and fecha ='$fecha' and anulada=0";
            } else {
                $sql = "SELECT monto,cod_ben from ord_pago where cod_ben ='65' and fecha ='$fecha' and anulada=0";
            }


            $consulta = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($consulta)) {

                $ordenes['monto'] = $row['monto'];
                $ordenes['cod_ben'] = $row['cod_ben'];
                break;
            }
            $res = $ordenes;

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


function getTasa($sede, $fecha)
{

    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "KAGUA21", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $sql = "SELECT TOP 1 tasa_v from tasas where fe_us_in >='$fecha' order by fecha";


            $consulta = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($consulta)) {

                $tasa['tasa_v'] = $row['tasa_v'];
                break;
            }
            $res = $tasa;

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


/* 
function getCot_Ped($sede, $co_art)
{



    #$database = Database($sede);
    $cliente = Cliente($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.39";
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


function Replica($sede)
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $fecha_actual = date("Ymd");
            $sql = "SELECT top 1 fec_emis from factura where fec_emis <='$fecha_actual' order by fec_emis desc";


            $consulta = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($consulta)) {

                $fecha['fec_emis'] = $row['fec_emis'];
                break;
            }
            $res = $fecha;

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}



function Art_Global($sede)
{

    $database = Database($sede);


    try {

        $serverName = "172.16.1.39";

        if ($database==null) {

            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");

        }else{

            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");

        }
        
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        $sql = "SELECT CONVERT(numeric(10,0), SUM(reng_dvp.total_art ) )as total_art
            from dev_pro
            JOIN reng_dvp ON dev_pro.fact_num=reng_dvp.fact_num
            JOIN art ON art.co_art=reng_dvp.co_art
            WHERE dev_pro.fec_emis BETWEEN '$fecha1' AND '$fecha2'  AND art.co_lin='$co_lin' AND dev_pro.anulada=0";

        $consulta = sqlsrv_query($conn, $sql);

        if ($consulta != null) {

            while ($row = sqlsrv_fetch_array($consulta)) {

                $total_art = $row['total_art'];
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
}










function Cerrar($database)
{
    if ($database != null) {
        $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        sqlsrv_close($conn);
    } else {
        $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        sqlsrv_close($conn);
    }
}
