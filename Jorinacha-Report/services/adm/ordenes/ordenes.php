<?php

require "../../services/empresas.php";




function getTasa( $fecha)
{


    $database='CAGUA';

    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT TOP 1 tasa_v from tasas 
            where Convert(char(10), fecha, 111) <='$fecha' 
            ORDER BY fecha DESC";

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


function getOrdenes_Pag($sede, $fecha)
{

    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT SUM(monto) as monto from ord_pago
                JOIN benefici ON benefici.cod_ben = ord_pago.cod_ben
                WHERE  anulada = 0   AND ord_num < 6000000 AND cta_egre ='878'
                AND  fecha ='$fecha'";



            $consulta = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($consulta)) {

                $ordenes['monto'] = $row['monto'];

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

