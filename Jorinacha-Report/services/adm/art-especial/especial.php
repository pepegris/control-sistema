<?php


/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA*/
$sedes_ar = array(
    "Previa Shop",
    "Sucursal Caracas I",
    "Sucursal Caracas II",
    "Sucursal Cagua",
    "Sucursal Maturin",
    
    "Comercial Corina I",
    "Comercial Corina II",
    "Comercial Punto Fijo",
    "Comercial Valena",
    "Comercial Trina",
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
        "Sucursal Cagua" => 'CAGUA',
        "Sucursal Matur" => 'MATURIN',

        "Comercial Corina I" => 'CORINA21',
        "Comercial Corina II" => 'CORI2_21',
        "Comercial Punto Fijo" => 'PUFIJO21',
        "Comercial Valena" => 'VALENA21',
        "Comercial Trina" => 'TRAINA21',    
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
        "Sucursal Cagua" => 'CAGUA',
        "Sucursal Matur" => 'MATURIN',

        "Comercial Corina I" => 'CORINA1',
        "Comercial Corina II" => 'CORINA2',
        "Comercial Punto Fijo" => 'PUFIJO',
        "Comercial Valena" => 'VALENA',
        "Comercial Trina" => 'TRINA',
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
        "Sucursal Cagua" => 'S03',
        "Sucursal Maturin"    =>     'S04',

        "Comercial Corina I"    =>     'T18',
        "Comercial Corina II"    =>     'T22',
        "Comercial Punto Fijo"    =>     'T13',
        "Comercial Valena"    =>     'T10',
        "Comercial Trina"    =>     'T16',
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




/* CONSULTAR ARTICULOS */
function getArt($sede, $linea, $co_art, $almacen)
{

    #$database = Database($sede);
    if ($sede) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$sede", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            #$sql ="EXEC getArt '$sede' , '$co_art', '$linea'  ";

            if ($sede== 'PREVIA_A') {
                $sql ="SELECT  LTRIM(RTRIM(art.co_art)) as  co_art ,LTRIM(RTRIM(sub_lin.subl_des)) as  co_subl,LTRIM(RTRIM(cat_art.cat_des)) as  co_cat,
                prec_vta3,prec_vta4,prec_vta5,st_almac.stock_act , LTRIM(RTRIM(colores.des_col)) as co_color, LTRIM(RTRIM(lin_art.lin_des)) as co_lin,art.ubicacion
                from st_almac 
                JOIN art on st_almac.co_art=art.co_art
                JOIN lin_art on art.co_lin = lin_art.co_lin
                JOIN sub_lin on art.co_subl = sub_lin.co_subl
                JOIN cat_art on art.co_cat=cat_art.co_cat
                JOIN colores on art.co_color=colores.co_col
                where art.co_lin='$linea' and st_almac.co_alma='BOLE' AND art.prec_vta5 >=1
                order by art.co_subl   desc";
            } else {
                $sql="SELECT  LTRIM(RTRIM(art.co_art)) as  co_art ,LTRIM(RTRIM(sub_lin.subl_des)) as  co_subl,LTRIM(RTRIM(cat_art.cat_des)) as  co_cat,
                prec_vta3,prec_vta4,prec_vta5,art.stock_act , LTRIM(RTRIM(colores.des_col)) as co_color, LTRIM(RTRIM(lin_art.lin_des)) as co_lin,art.ubicacion
                from art 
                JOIN lin_art on art.co_lin = lin_art.co_lin
                JOIN sub_lin on art.co_subl = sub_lin.co_subl
                JOIN cat_art on art.co_cat=cat_art.co_cat
                JOIN colores on art.co_color=colores.co_col
                where art.co_lin='$linea' AND art.prec_vta5 >= 1 AND art.stock_act  >= 1 ";
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
function getReng_fac($sede,  $co_art)
{


    #$database = Database($sede);
    if ($sede != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$sede", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            #$sql = "EXEC getReng_fac '$co_art' , '$fecha1' , '$fecha2'";
            $sql = "SELECT TOP 1 reng_fac.total_art , factura.fec_emis 
            FROM reng_fac
            JOIN factura ON reng_fac.fact_num =factura.fact_num
            WHERE factura.anulada=0 
            AND reng_fac.co_art='$co_art'
            ORDER BY factura.fec_emis  DESC";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $reng_fac[] = $row;

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


function getReng_com ($sede,  $co_art  )
{

    
    #$database = Database($sede);

    if ($sede != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$sede", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

           # $sql = "EXEC getPedidos_t ,'$cliente', '$co_art' ";

           $sql = "SELECT TOP 1 reng_com.total_art , compras.fact_num , compras.fec_emis 
           FROM reng_com
           JOIN compras ON reng_com.fact_num =compras.fact_num
           WHERE compras.anulada=0 
           AND reng_com.co_art='$co_art'
           ORDER BY compras.fec_emis  DESC ";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $reng_com[] = $row;
                    break;
                }
                $res = $reng_com;
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





function getReng_ajue($sede, $co_art)
{

    #$database = Database($sede);

    if ($sede != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$sede", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT TOP 1 reng_aju.total_art , ajuste.ajue_num , ajuste.fecha  
            FROM reng_aju
            JOIN ajuste ON reng_aju.ajue_num =ajuste.ajue_num
            WHERE ajuste.anulada=0 
            AND reng_aju.co_art='$co_art'
            ORDER BY ajuste.fecha  DESC  ";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $reng_aju[] = $row;
                    break;
                }
                $res = $reng_aju;
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

