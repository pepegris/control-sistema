<?php

function getPedidos_t ($sede,  $co_art)
{

    
    $cliente = Cliente($sede);

    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

           # $sql = "EXEC getPedidos_t ,'$cliente', '$co_art' ";

           $sql = "SELECT CONVERT(numeric(10,0),SUM(reng_ped.total_art)) AS  total_art
           from pedidos
           JOIN reng_ped ON pedidos.fact_num=reng_ped.fact_num
           where pedidos.anulada=0 AND pedidos.status = 0 AND pedidos.co_cli='$cliente' AND reng_ped.co_art='$co_art'";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $reng_ped = $row['total_art'];
                    break;
                }
                $res = $reng_ped;
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

$getPedidos_t= getPedidos_t("Comercial Merina",  '5103624386772');
$pedido_tienda = $getPedidos_t['total_Art'];


var_dump($getPedidos_t);
var_dump($pedido_tienda);
echo $pedido_tienda;
echo $getPedidos_t;
var_dump(getPedidos_t("Comercial Merina",  '5103624386772'));


$serverName = "172.16.1.39";
$connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect($serverName, $connectionInfo);

# $sql = "EXEC getPedidos_t ,'$cliente', '$co_art' ";

$sql = "SELECT CONVERT(numeric(10,0),SUM(reng_ped.total_art)) AS  total_art
from pedidos
JOIN reng_ped ON pedidos.fact_num=reng_ped.fact_num
where pedidos.anulada=0 AND pedidos.status = 0 AND pedidos.co_cli='T15' AND reng_ped.co_art='5103624386772'";

$consulta = sqlsrv_query($conn, $sql);

if ($consulta != null) {
    while ($row = sqlsrv_fetch_array($consulta)) {

        $reng_ped = $row['total_art'];
        echo $row['total_art'];
        echo $reng_ped;
        break;
    }
    $res = $reng_ped;
    echo "$res";
} else {
    $res = 0;
}