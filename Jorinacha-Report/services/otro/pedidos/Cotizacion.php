<?php


/* OBTENER LAS COTIZACIONES Y PEDIDOS DE LOS ARTICULOS POR DESPACHAR */
/*
    ESTATUS DE LOS PEDIDOS Y COTIZACION
0 sin procesar
1 Parc/Procesada
2 Procesada
*/



function getCotizacion($sede, $co_art)
{

    #$database = Database($sede);
    $cliente = Cliente($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.19";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT top 1 cotiz_c.fact_num,reng_cac.total_art,cotiz_c.status  
            FROM reng_cac INNER JOIN cotiz_c ON reng_cac.fact_num=cotiz_c.fact_num
            WHERE reng_cac.co_art ='$co_art' and cotiz_c.co_cli='$cliente'
            ORDER BY fe_us_in DESC";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art['total_art'] = number_format($row['total_art'], 0, ',', '.');
                    $total_art['status'] = $row['status'];
                    $total_art['doc'] = 'Cot';
                    break;
                }
                $res = $total_art;
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