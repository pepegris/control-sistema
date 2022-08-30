<?php



/* CONSULTAR FACTURA DE COMPRA DE LOS ARTTICULOS*/
function getCompras($co_art)
{


    $serverName = "172.16.1.19";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $sql = "SELECT top 1  co_art,fact_num,fec_lote,total_art,prec_vta from reng_com  where co_art ='$co_art' order by fec_lote desc";
    $consulta = sqlsrv_query($conn, $sql);

    if ($consulta) {
        while ($row = sqlsrv_fetch_array($consulta)) {

            $co_col['co_art'] =  $row['co_art'];
            $co_col['fact_num'] =  $row['fact_num'];
            $co_col['fec_lote'] =   $row['fec_lote'];
            $co_col['total_art'] =   number_format($row['total_art'], 0, ',', '.');
            $co_col['prec_vta'] =   number_format($row['prec_vta'], 2, ',', '.');
            break;
        }
        $res = $co_col;
    } else {


        $res = 0;
    }

    return $res;
}
