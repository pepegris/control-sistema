<?php



require "../../services/empresas.php";




function Factura_Ordenes($sede,$fecha)
{


    $database = Database($sede);
    $cliente = Cliente($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "DEV_EMP", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            if ($cliente =='S04' or $cliente =='S03' 
                or $cliente =='S02' or $cliente =='S01'  ) {

                $sql = "SELECT fact_num,contrib,CONVERT(numeric(10,2), saldo) AS saldo ,CONVERT(numeric(10,2), iva)   AS iva
                FROM not_ent 
                WHERE co_cli='$cliente' AND FEC_EMIS='$fecha'  AND anulada=0";


            }else{

                $sql = "SELECT fact_num,contrib,CONVERT(numeric(10,2), saldo) AS saldo,CONVERT(numeric(10,2), iva)   AS iva
                        FROM factura 
                        WHERE co_cli='$cliente' AND FEC_EMIS = '$fecha' AND anulada=0";

            }

            $consulta = sqlsrv_query($conn, $sql);
            $r=0;
            while ($row = sqlsrv_fetch_array($consulta)) {

                $ordenes_facturas[$r]['fact_num'] = $row['fact_num'] ;
                $ordenes_facturas[$r]['contrib'] = $row['contrib'] ;
                $ordenes_facturas[$r]['saldo'] = $row['saldo'] ;
                $ordenes_facturas[$r]['iva'] = $row['iva'] ;
                $r++;
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


function Reng_Factura($sede,$fecha,$fact_num)
{


    $database = Database($sede);
    $cliente = Cliente($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "DEV_EMP", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);



            if ($cliente =='S04' or $cliente =='S03' 
            or $cliente =='S02' or $cliente =='S01'  ) {

                $sql = "SELECT not_ent.fact_num,reng_num,co_art, CONVERT(numeric(10,0), total_art) AS total_art, CONVERT(numeric(10,2), prec_vta) AS prec_vta,CONVERT(numeric(10,2), reng_neto) AS reng_neto
                FROM reng_nde
                INNER JOIN not_ent  ON reng_nde.fact_num = not_ent.fact_num
                WHERE co_cli='$cliente' AND FEC_EMIS>'$fecha' AND not_ent.fact_num='$fact_num' AND anulada=0";


        }else{



            $sql = "SELECT not_ent.fact_num,reng_num,co_art, CONVERT(numeric(10,0), total_art) AS total_art, CONVERT(numeric(10,2), prec_vta) AS prec_vta,CONVERT(numeric(10,2), reng_neto) AS reng_neto
                    FROM reng_fac
                    INNER JOIN factura  ON reng_fac.fact_num = factura.fact_num
                    WHERE co_cli='$cliente' AND FEC_EMIS>'$fecha' AND not_ent.fact_num='$fact_num' AND anulada=0";

        }



            $consulta = sqlsrv_query($conn, $sql);

            $r=0;
            while ($row = sqlsrv_fetch_array($consulta)) {

                $Reng_Factura[$r]['fact_num'] = $row['fact_num'] ;
                $Reng_Factura[$r]['reng_num'] = $row['reng_num'] ;
                $Reng_Factura[$r]['co_art'] = $row['co_art'] ;
                $Reng_Factura[$r]['total_art'] = $row['total_art'] ;
                $Reng_Factura[$r]['prec_vta'] = $row['prec_vta'] ;
                $Reng_Factura[$r]['reng_neto'] = $row['reng_neto'] ;
                $r++;
            }
            $res = $Reng_Factura;

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
            $connectionInfo = array("Database" => "DEV_EMP", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);



            $sql = "INSERT into ordenes (fact_num,contrib,status,comentario,descrip,co_sucu,forma_pag,moneda,co_cli)
            values(789,1,0,'prueba','descripcion',1,'CRED','BSD','002')";


            $consulta = sqlsrv_query($conn, $sql);

            $r=0;
            while ($row = sqlsrv_fetch_array($consulta)) {

                $Ordenes_Compra[$r]['reng_num'] = $row['reng_num'] ;
                $Ordenes_Compra[$r]['total_art'] = $row['total_art'] ;
                $Ordenes_Compra[$r]['prec_vta'] = $row['prec_vta'] ;
                $Ordenes_Compra[$r]['reng_neto'] = $row['reng_neto'] ;
                $r++;
            }
            $res = $Ordenes_Compra;

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
            $connectionInfo = array("Database" => "DEV_EMP", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
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





