<?php

  
$sedes_ar = array(
    "Previa Shop",
    "Sucursal Caracas I",
    "Sucursal Caracas II",
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
        "Sucursal Caracas I" => 'CARACAS1',
        "Sucursal Caracas II" => 'CARACAS2',
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
        "Sucursal Caracas I" => 'CARACAS1',
        "Sucursal Caracas II" => 'CARACAS2',
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
        "Sucursal Caracas I"    =>     'S01',
        "Sucursal Caracas II"    =>     'S02',
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
                    prec_vta1,prec_vta5,st_almac.stock_act, co_color , co_lin,art.ubicacion
                    from st_almac inner join art on st_almac.co_art=art.co_art 
                    where   st_almac.co_alma='BOLE' AND art.prec_vta5 >=1
                    order by art.co_subl  desc ";
                } else {

                    $sql = "SELECT LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat) as  co_cat  , 
                    co_color , co_cat , co_lin , stock_act , prec_vta1 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5,art.ubicacion 
                    from art WHERE prec_vta5 >= 1 ORDER BY co_subl  DESC";
                }
            } elseif ($co_art == 0) {

                if ($database == 'PREVIA_A') {

                    $sql = "SELECT LTRIM(RTRIM(art.co_art)) as  co_art ,LTRIM(RTRIM(art.co_subl)) as  co_subl,LTRIM(RTRIM(art.co_cat)) as  co_cat,
                    prec_vta1,prec_vta5,st_almac.stock_act , co_color, co_lin,art.ubicacion
                    from st_almac inner join art on st_almac.co_art=art.co_art 
                    where art.co_lin='$linea' and st_almac.co_alma='BOLE' AND art.prec_vta5 >=1
                    order by art.co_subl   desc";
                } else {

                    $sql = "SELECT LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat)) as  co_cat  ,
                    co_color , co_lin , stock_act , prec_vta1 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5,art.ubicacion 
                    from art  where co_lin= '$linea' AND prec_vta5 >= 1 ORDER BY co_subl  DESC";
                }
            } else {

                $sql = "SELECT LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat)) as  co_cat  ,
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


