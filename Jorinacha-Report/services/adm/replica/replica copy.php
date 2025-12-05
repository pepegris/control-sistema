<?php
require "../../services/empresas.php";

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


function Replica_detal($sede,$doc)
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $fecha_actual = date("Ymd");

            if ($doc=='factura') {
                $sql = "SELECT top 1 fec_emis as fec_emis from factura where fec_emis <='$fecha_actual' order by fec_emis desc";           
            }elseif ($doc=='cobros') {
                $sql = "SELECT top 1 fec_cob as fec_emis FROM cobros where fec_cob <='$fecha_actual' order by fec_cob desc";
            }elseif ($doc=='ord_pago') {
                $sql = "SELECT top 1 fecha as fec_emis from ord_pago where fecha <='$fecha_actual' order by fecha desc";
            }elseif ($doc=='mov_ban') {
                $sql = "SELECT top 1 fecha as fec_emis from mov_ban  where fecha <='$fecha_actual' order by fecha desc";
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



function Inventario($sede)
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT TOP 1  cerrado from  fisico where cerrado = 0";


            $consulta = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($consulta)) {

                $cerrado['cerrado'] = $row['cerrado'];
                break;
            }
            $res = $cerrado;

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}
