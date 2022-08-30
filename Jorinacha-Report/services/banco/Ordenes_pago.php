<?php


function getOrdenes_Pag($sede, $fecha)
{

    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.19";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($database == 'MRIA3A21' or $database == 'MERINA21') {
                $sql = "SELECT monto from ord_pago where cod_ben ='47' and fecha ='$fecha'";
            } elseif ($database == 'KAGUA21' or $database == 'TRAINA21' or $database == 'CORINA21' or $database == 'CORI2_21') {
                $sql = "SELECT monto from ord_pago where cod_ben ='95' and fecha ='$fecha'";
            } else {
                $sql = "SELECT monto from ord_pago where cod_ben ='65' and fecha ='$fecha'";
            }


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