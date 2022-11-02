<?php





function getFacturaCompras($sede,  $fecha1, $fecha2, $co_lin)
{
    $cliente = Cliente($sede);
    $database = Database($sede);


    if ($cliente != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT CONVERT(numeric(10,0), SUM(reng_com.total_art)) as total_art
            FROM reng_com
            JOIN compras ON reng_com.fact_num=compras.fact_num
            JOIN art ON art.co_art = reng_com.co_art
            WHERE compras.fec_emis BETWEEN '$fecha1' AND '$fecha2' AND compras.anulada=0 AND art.co_lin = '$co_lin'";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art = $row['total_art'];
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

/* FACTURA DE EL ULTIMO ARTICULOS VENDIDO EN PREVIA */

function getFactura($sede, $co_art, $fecha1, $fecha2, $co_lin)
{
    $cliente = Cliente($sede);


    if ($cliente != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);



            if ($co_lin == null) {

                $sql = "SELECT CONVERT(numeric(10,0), SUM(total_art ) )as total_art
                FROM reng_fac
                INNER JOIN factura ON reng_fac.fact_num=factura.fact_num
                WHERE reng_fac.co_art='$co_art' and factura.co_cli='$cliente' and anulada = 0  and factura.fe_us_in BETWEEN '$fecha1'  AND '$fecha2'";
            } else {

                $sql = "SELECT CONVERT(numeric(10,0), SUM(reng_fac.total_art ) ) as total_art
                FROM reng_fac
                JOIN factura ON reng_fac.fact_num=factura.fact_num 
                JOIN art ON art.co_art = reng_fac.co_art
                WHERE art.co_lin='$co_lin'  AND  factura.fec_emis BETWEEN '$fecha1'  AND '$fecha2'  AND factura.co_cli='$cliente' AND factura.anulada=0";
            }



            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art = $row['total_art'];
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


function getAjustes($sede,  $fecha1, $fecha2, $co_lin, $tipo)
{
    $cliente = Cliente($sede);
    $database = Database($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            if ($tipo == 'EN') {

                $sql = "SELECT CONVERT(numeric(10,0), SUM(reng_aju.total_art) )as total_art
                FROM ajuste 
                JOIN reng_aju ON ajuste.ajue_num = reng_aju.ajue_num
                JOIN art ON art.co_art = reng_aju.co_art
                WHERE  ajuste.fecha BETWEEN '$fecha1' AND '$fecha2' AND art.co_lin='$co_lin'  AND reng_aju.tipo='EN' AND ajuste.anulada =0";
            } else {

                $sql = "SELECT CONVERT(numeric(10,0), SUM(reng_aju.total_art) )as total_art
                FROM ajuste 
                JOIN reng_aju ON ajuste.ajue_num = reng_aju.ajue_num
                JOIN art ON art.co_art = reng_aju.co_art
                WHERE  ajuste.fecha BETWEEN '$fecha1' AND '$fecha2' AND art.co_lin='$co_lin'  AND reng_aju.tipo='SAL' AND ajuste.anulada =0";
            }


            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art = $row['total_art'];
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



function getDevProveedor($sede,  $fecha1, $fecha2, $co_lin)
{

    $cliente = Cliente($sede);
    $database = Database($sede);

    if ($cliente != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT CONVERT(numeric(10,0), SUM(reng_dvp.total_art ) )as total_art
            from dev_pro
            JOIN reng_dvp ON dev_pro.fact_num=reng_dvp.fact_num
            JOIN art ON art.co_art=reng_dvp.co_art
            WHERE dev_pro.fec_emis BETWEEN '$fecha1' AND '$fecha2'  AND art.co_lin='$co_lin' AND dev_pro.anulada=0";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $total_art = $row['total_art'];
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