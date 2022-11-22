<?php

/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA*/


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

$consultas = array(
    "1-Cliente",
    "2-Proveedores",

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





function getDev_cli($sede, $fecha1, $fecha2)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $sql = "SELECT  art.co_art, sub_lin.subl_des , cat_art.cat_des , colores.des_col, lin_art.lin_des , art.ubicacion, art.stock_act , 

            dev_cli.fact_num as dev_cli_fact,
            dev_cli.comentario as dev_cli_comentario ,
            dev_cli.fec_emis as dev_cli_fec_emis , 
            reng_dvc.total_art as reng_dvc_total_art

            FROM dev_cli 
            JOIN reng_dvc ON dev_cli.fact_num = reng_dvc.fact_num
            JOIN art ON art.co_art = reng_dvc.co_art

            JOIN lin_art ON art.co_lin = lin_art.co_lin
            JOIN sub_lin ON art.co_subl = sub_lin.co_subl
            JOIN cat_art ON art.co_cat=cat_art.co_cat
            JOIN colores ON art.co_color=colores.co_col
            WHERE dev_cli.fec_emis BETWEEN '$fecha1' AND '$fecha2' AND dev_cli.anulada =0";



            $consulta = sqlsrv_query($conn, $sql);

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $dev[] = $row;

                }

                $res = $dev;

                return $res;

        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


function getDev_pro($sede, $fecha1, $fecha2)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $sql = "SELECT  art.co_art, sub_lin.subl_des , cat_art.cat_des , colores.des_col, lin_art.lin_des , art.ubicacion, art.stock_act , 
            
			dev_pro.fact_num as dev_pro_fact,
            dev_pro.descrip as dev_pro_descrip ,
			dev_pro.fec_emis as dev_pro_fec_emis , 
			reng_dvp.total_art as reng_dvp_total_art

            FROM dev_pro 
            JOIN reng_dvp ON dev_pro.fact_num = reng_dvp.fact_num
            JOIN art ON art.co_art = reng_dvp.co_art
            
            JOIN lin_art ON art.co_lin = lin_art.co_lin
            JOIN sub_lin ON art.co_subl = sub_lin.co_subl
            JOIN cat_art ON art.co_cat=cat_art.co_cat
            JOIN colores ON art.co_color=colores.co_col
            WHERE dev_pro.fec_emis BETWEEN '$fecha1' AND '$fecha2' AND dev_pro.anulada =0 ";



            $consulta = sqlsrv_query($conn, $sql);

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $dev[] = $row;

                }

                $res = $dev;

                return $res;
                
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}




function getCompras($sede, $fecha1, $fecha2 , $co_art)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $sql = "SELECT  TOP 1
			compras.fact_num as compras_fact,
            compras.fec_emis as com_fecha,
            reng_com.total_art as com_total_art
			FROM reng_com
            JOIN compras ON compras.fact_num = reng_com.fact_num
			WHERE  compras.anulada =0 
			AND co_art ='$co_art'
            AND com_fecha >= '$fecha2'
            ORDER BY com_fecha DESC";



            $consulta = sqlsrv_query($conn, $sql);

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $compras[] = $row;

                }

                $res = $compras;

                return $res;
                
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}




?>