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


/* CONSULTAR TALLA DE ARTICULOS */
function getCat_art()
{


    $serverName = "172.16.1.19";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$","CharacterSet" =>"UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT co_cat,cat_des FROM cat_art ";
    $res = sqlsrv_query($conn, $sql);

    return $res;
}
/* CONSULTAR LINEA DE ARTICULOS*/
function getLin_art()
{


    $serverName = "172.16.1.19";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$","CharacterSet" =>"UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT co_lin,lin_des from lin_art";
    $consulta = sqlsrv_query($conn, $sql);


    while ($row = sqlsrv_fetch_array($consulta)) {

        $lin_art[] =  $row;
    }

    $res = $lin_art;
    return $res;
}

/* CONSULTAR ARTICULOS */
function getArt($sede, $linea)
{

    $database = Database($sede);
    if ($database) {
        try {

            $serverName = "172.16.1.19";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$","CharacterSet" =>"UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($linea === 'todos') {

                $sql = "SELECT LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(art_des)) as  art_des  , 
                co_color , co_cat , co_lin , stock_act , prec_vta1 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5 
                from art ";
            } else {

                $sql = "SELECT LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(art_des)) as  art_des  ,
                co_color , co_cat , co_lin , stock_act , prec_vta1 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5 
                from art  where co_lin= '$linea' AND prec_vta1 > 1
                order by co_art";
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
function getReng_fac($sede, $linea, $fecha1, $fecha2)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.19";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$","CharacterSet" =>"UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($linea = 'todos') {

                $sql = "SELECT LTRIM(RTRIM(reng_fac.co_art)) AS  co_art, sum (reng_fac.total_art ) as  total_art
                FROM reng_fac left join art
                ON reng_fac.co_art = art.co_art  
                WHERE reng_fac.fec_lote BETWEEN '$fecha1'  AND '$fecha2' 
                GROUP BY  reng_fac.co_art
                order by reng_fac.co_art ";
            } else {

                $sql = "SELECT LTRIM(RTRIM(reng_fac.co_art)) AS  co_art, sum (reng_fac.total_art ) as  total_art
                FROM reng_fac left join art
                ON reng_fac.co_art = art.co_art  
                WHERE reng_fac.fec_lote BETWEEN '$fecha1'  AND '$fecha2' AND art.co_lin = '$linea'
                GROUP BY  reng_fac.co_art";
            }


            $consulta = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($consulta)) {

                $reng_fac[] = $row;
            }
            $res = $reng_fac;
            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return null;
    }
}
