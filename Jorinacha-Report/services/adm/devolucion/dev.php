<?php

/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA*/



require "../../services/empresas.php";


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




function getDev_cli($sede, $fecha1, $fecha2 , $art)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            if ($art != '') {

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
                

            }else {



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
                WHERE dev_cli.fec_emis BETWEEN '$fecha1' AND '$fecha2' AND reng_dvc.co_art='$art' AND dev_cli.anulada =0";
            }



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


function getDev_pro($sede, $fecha1, $fecha2,$art,$linea)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            if ($art !='') {

                    $sql = "SELECT  
                    reng_dvp.co_art,
                    art_des , 
                    prec_vta5,
                    prov_des,
                
                    dev_pro.fact_num as dev_pro_fact,
                    dev_pro.descrip as dev_pro_descrip ,
                    dev_pro.fec_emis as dev_pro_fec_emis , 
                    reng_dvp.total_art as reng_dvp_total_art
                        
            
                    FROM dev_pro 
                    JOIN reng_dvp ON dev_pro.fact_num = reng_dvp.fact_num
                    JOIN art ON art.co_art = reng_dvp.co_art
                    JOIN prov ON dev_pro.co_cli=prov.co_prov  
                    WHERE dev_pro.fec_emis BETWEEN '$fecha1' AND '$fecha2' AND reng_dvp.co_art='$art'  AND dev_pro.anulada =0 ";

            } elseif ($linea != 'todos') {

                $sql = "SELECT  
                reng_dvp.co_art,
                art_des , 
                prec_vta5,
                prov_des,
            
                dev_pro.fact_num as dev_pro_fact,
                dev_pro.descrip as dev_pro_descrip ,
                dev_pro.fec_emis as dev_pro_fec_emis , 
                reng_dvp.total_art as reng_dvp_total_art
                    
        
                FROM dev_pro 
                JOIN reng_dvp ON dev_pro.fact_num = reng_dvp.fact_num
                JOIN art ON art.co_art = reng_dvp.co_art
                JOIN prov ON dev_pro.co_cli=prov.co_prov
                JOIN lin_art ON art.co_lin = lin_art.co_lin  
                WHERE dev_pro.fec_emis BETWEEN '$fecha1' AND '$fecha2' AND art.co_lin='$linea'  AND dev_pro.anulada =0 ";

            }else {

                    $sql = "SELECT  
                    reng_dvp.co_art,
                    art_des , 
                    prec_vta5,
                    prov_des,
                
                    dev_pro.fact_num as dev_pro_fact,
                    dev_pro.descrip as dev_pro_descrip ,
                    dev_pro.fec_emis as dev_pro_fec_emis , 
                    reng_dvp.total_art as reng_dvp_total_art
                        
            
                    FROM dev_pro 
                    JOIN reng_dvp ON dev_pro.fact_num = reng_dvp.fact_num
                    JOIN art ON art.co_art = reng_dvp.co_art
                    JOIN prov ON dev_pro.co_cli=prov.co_prov  

                    
                    WHERE dev_pro.fec_emis BETWEEN '$fecha1' AND '$fecha2' AND dev_pro.anulada =0 ";
            }
            



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




function getCompras($sede,  $fecha2 , $co_art)
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
            reng_com.total_art as com_total_art,
            compras.tot_neto
			FROM reng_com
            JOIN compras ON compras.fact_num = reng_com.fact_num
			WHERE  compras.anulada =0 
			AND co_art ='$co_art'
            AND fec_emis <= '$fecha2'
            ORDER BY fec_emis DESC";



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
