<?php



require "../../services/empresas.php";




function Factura_Ordenes($sede,$fecha)
{


    $database = Database($sede);
    $cliente = Cliente($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            if ($cliente =='S04' or $cliente =='S03' 
                or $cliente =='S02' or $cliente =='S01'  ) {

                $sql = "SELECT fact_num,contrib,CONVERT(numeric(10,2), saldo),CONVERT(numeric(10,2), iva)  
                FROM not_ent 
                WHERE co_cli='$cliente' AND FEC_EMIS='$fecha'  AND anulada=0";


            }else{

                $sql = "SELECT fact_num,contrib,CONVERT(numeric(10,2), saldo),CONVERT(numeric(10,2), iva)   
                        FROM factura 
                        WHERE co_cli='$cliente' AND FEC_EMIS = '$fecha' AND anulada=0";

            }

            $consulta = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($consulta)) {

                $ordenes_facturas['fact_num'] = $row['fact_num'] ;
                $ordenes_facturas['contrib'] = $row['contrib'] ;
                $ordenes_facturas['saldo'] = $row['saldo'] ;
                $ordenes_facturas['iva'] = $row['iva'] ;
            }
            $res = $ordenes_facturas;

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


function Reng_Factura($sede,$fecha)
{


    $database = Database($sede);
    $cliente = Cliente($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);



            if ($cliente =='S04' or $cliente =='S03' 
            or $cliente =='S02' or $cliente =='S01'  ) {

                $sql = "SELECT reng_num,co_art, CONVERT(numeric(10,0), total_art), CONVERT(numeric(10,2), prec_vta) ,CONVERT(numeric(10,2), reng_neto)
                FROM reng_nde
                INNER JOIN not_ent  ON reng_nde.fact_num = not_ent.fact_num
                WHERE co_cli='S04' AND FEC_EMIS>'20231112' AND anulada=0";


        }else{



            $sql = "SELECT reng_num,co_art,total_art,prec_vta ,reng_neto
                    FROM reng_fac
                    INNER JOIN factura  ON reng_fac.fact_num = factura.fact_num
                    WHERE co_cli='T05' AND FEC_EMIS>'20231112' AND anulada=0";

        }



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



#########################################################################################################################################################
#########################################################################################################################################################
#########################################################################################################################################################


function Ordenes_Compra($sede)
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);



            $sql = "INSERT into ordenes (fact_num,contrib,status,comentario,descrip,co_sucu,forma_pag,moneda,co_cli)
            values(789,1,0,'prueba','descripcion',1,'CRED','BSD','002')";


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


function Reng_Ordenes($sede)
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);



            $sql = "INSERT into reng_ord (fact_num,reng_num,tipo_doc,  co_alma,co_art,total_art,uni_venta)
            values(789,1,'P',1, '2053603107928',2,'PAR')";


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


#########################################################################################################################################################
#########################################################################################################################################################
#########################################################################################################################################################




