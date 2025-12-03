<?php
// ../../services/adm/ventas/cashea.php

require_once "../../services/empresas.php";

function getCobros_Cashea($sede, $fecha1, $fecha2)
{
    $database = Database($sede);
    
    if ($database != null) {
        try {
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);
            
            if (!$conn) return [];

            // SQL OPTIMIZADO: Busca por cod_caja O por banco 'CASHEA' en una sola consulta.
            // Tambien incluye la corrección de fecha CONVERT(..., 112)
            $sql = "SELECT 
                        cobros.cob_num,
                        cobros.fec_cob,
                        reng_cob.doc_num,
                        CONVERT(numeric(10,2), reng_tip.mont_doc) as mont_doc, 
                        factura.tot_neto,
                        clientes.co_cli,
                        clientes.cli_des 
                    FROM cobros
                    JOIN reng_tip ON cobros.cob_num = reng_tip.cob_num
                    JOIN reng_cob ON cobros.cob_num = reng_cob.cob_num
                    JOIN factura ON reng_cob.doc_num = factura.fact_num
                    JOIN clientes ON clientes.co_cli = factura.co_cli
                    WHERE 
                        (reng_tip.cod_caja = 'CASHEA' OR reng_tip.banco = 'CASHEA') 
                        AND cobros.anulado = 0
                        AND CONVERT(VARCHAR(8), cobros.fec_cob, 112) BETWEEN '$fecha1' AND '$fecha2'";

            $consulta = sqlsrv_query($conn, $sql);
            $cobros = [];

            if ($consulta) {
                while ($row = sqlsrv_fetch_array($consulta)) {
                    $cobros[] = $row;
                }
            }
            
            sqlsrv_close($conn);
            return $cobros;

        } catch (\Throwable $th) {
            return [];
        }
    } 
    return [];
}

// Las otras funciones se mantienen limpias por si las usas en el futuro
function getFactura_Cashea($sede, $fact_num)
{
    $database = Database($sede);
    if ($database != null) {
        try {
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT factura.co_cli, clientes.cli_des FROM factura 
                    JOIN clientes ON factura.co_cli = clientes.co_cli
                    WHERE fact_num='$fact_num'";

            $consulta = sqlsrv_query($conn, $sql);
            $factura = [];

            if ($consulta && $row = sqlsrv_fetch_array($consulta)) {
                $factura['co_cli'] = $row['co_cli'];
                $factura['cli_des'] = $row['cli_des'];
            }
            sqlsrv_close($conn);
            return $factura;
        } catch (\Throwable $th) { return 0; }
    }
    return 0;
}

function getDeposito_Cashea($sede, $cob_num)
{
    $database = Database($sede);
    if ($database != null) {
        try {
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT dep_caj.dep_num, reng_dp.descrip FROM dep_caj  
                    JOIN reng_dp ON dep_caj.dep_num = reng_dp.dep_num
                    WHERE cod_cta='300' AND descrip LIKE '%$cob_num%'";

            $consulta = sqlsrv_query($conn, $sql);
            $dep_caj = [];

            if ($consulta && $row = sqlsrv_fetch_array($consulta)) {
                $dep_caj['dep_num'] = $row['dep_num'];
                $dep_caj['descrip'] = $row['descrip'];
            } else {
                $dep_caj['dep_num'] = 'NO HAY';
                $dep_caj['descrip'] = 'DEPOSITO BANCARIO';
            }
            sqlsrv_close($conn);
            return $dep_caj;
        } catch (\Throwable $th) { return 0; }
    }
    return 0;
}
?>