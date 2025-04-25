<?php


/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA*/


require "../../services/empresas.php";



function getCobros_Cashea($sede, $fecha1, $fecha2)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT cobros.cob_num,cobros.fec_cob,reng_cob.doc_num  ,CONVERT(numeric(10,2), reng_tip.mont_doc) as mont_doc, 
                    factura.tot_neto ,clientes.co_cli,clientes.cli_des from cobros
                    join reng_tip on cobros.cob_num=reng_tip.cob_num
                    join reng_cob on cobros.cob_num=reng_cob.cob_num
					join factura on reng_cob.doc_num=factura.fact_num
					join clientes on clientes.co_cli=factura.co_cli
                    where cod_caja='CASHEA' and cobros.fec_cob between '$fecha1' and '$fecha2' and cobros.anulado=0";



            $consulta = sqlsrv_query($conn, $sql);


            while ($row = sqlsrv_fetch_array($consulta)) {

/*                 
                $cobros['cob_num'] = $row['cob_num'];
                $cobros['fec_cob'] = $row['fec_cob'];
                $cobros['doc_num'] = $row['doc_num'];
                $cobros['mont_doc'] = $row['mont_doc'];
                $cobros['tot_neto'] = $row['tot_neto'];
                $cobros['co_cli'] = $row['co_cli'];
                $cobros['cli_des'] = $row['cli_des'];
 */
                $cobros[] = $row;
            }

            $res = $cobros;

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


function getFactura_Cashea($sede, $fact_num)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT factura.co_cli,cli_des from factura 
                    join clientes on factura.co_cli=clientes.co_cli
                    where fact_num='$fact_num'";



            $consulta = sqlsrv_query($conn, $sql);


            while ($row = sqlsrv_fetch_array($consulta)) {

                $factura['co_cli'] = $row['co_cli'];
                $factura['cli_des'] = $row['cli_des'];
                break;
            }

            $res = $factura;

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}



function getDeposito_Cashea($sede, $cob_num)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT dep_caj.dep_num, reng_dp.descrip from dep_caj  
				join reng_dp on dep_caj.dep_num = reng_dp.dep_num
				where cod_cta='300' and descrip like '%$cob_num%'";



            $consulta = sqlsrv_query($conn, $sql);


            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    
                $dep_caj['dep_num'] = $row['dep_num'];
                $dep_caj['descrip'] = $row['descrip'];
                break;
                }

                $res = $dep_caj;
            } else {

                $dep_caj['dep_num'] = 'NO HAY';
                $dep_caj['descrip'] = 'DEPOSITO BANCARIO';

                $res = $dep_caj;
            }
            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}
