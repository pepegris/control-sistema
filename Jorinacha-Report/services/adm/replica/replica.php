<?php
require_once "../../services/empresas.php"; 

// ---------------------------------------------------
// FUNCION 1: Para el listado general (form.php)
// ---------------------------------------------------
function Replica($sede)
{
    $database = Database($sede);
    $res = null;

    if ($database) {
        $serverName = "172.16.1.39";
        $connectionInfo = array(
            "Database" => "$database", 
            "UID" => "mezcla", 
            "PWD" => "Zeus33$", 
            "CharacterSet" => "UTF-8",
            "LoginTimeout" => 3 
        );
        
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if ($conn) {
            $fecha_actual = date("Ymd");
            $sql = "SELECT TOP 1 fec_emis FROM factura WHERE fec_emis <= '$fecha_actual' ORDER BY fec_emis DESC";
            
            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta) {
                if ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
                    $res = $row; 
                }
            }
            sqlsrv_close($conn); 
        }
    }
    return $res;
}

// ---------------------------------------------------
// FUNCION 2: Para el icono de inventario (form.php)
// ---------------------------------------------------
function Inventario($sede)
{
    $database = Database($sede);
    $res = null;

    if ($database) {
        $serverName = "172.16.1.39";
        $connectionInfo = array(
            "Database" => "$database", 
            "UID" => "mezcla", 
            "PWD" => "Zeus33$", 
            "CharacterSet" => "UTF-8",
            "LoginTimeout" => 3
        );
        
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if ($conn) {
            $sql = "SELECT TOP 1 cerrado FROM fisico WHERE cerrado = 0";
            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta) {
                if ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
                    $res = $row;
                }
            }
            sqlsrv_close($conn);
        }
    }
    return $res;
}

// ---------------------------------------------------
// FUNCION 3: NECESARIA para que funcione detal.php
// ---------------------------------------------------
function Replica_detal($sede, $tabla)
{
    $database = Database($sede);
    $res = null;

    if ($database) {
        $serverName = "172.16.1.39";
        $connectionInfo = array(
            "Database" => "$database", 
            "UID" => "mezcla", 
            "PWD" => "Zeus33$", 
            "CharacterSet" => "UTF-8",
            "LoginTimeout" => 3
        );
        
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if ($conn) {
            $fecha_actual = date("Ymd");
            $sql = "";

            // Seleccionamos la consulta según lo que pida detal.php
            // IMPORTANTE: Usamos 'AS fec_emis' para que detal.php siempre encuentre el dato
            switch ($tabla) {
                case 'factura':
                    $sql = "SELECT TOP 1 fec_emis FROM factura WHERE fec_emis <= '$fecha_actual' ORDER BY fec_emis DESC";
                    break;
                case 'cobros':
                    $sql = "SELECT TOP 1 fec_cob AS fec_emis FROM cobros WHERE fec_cob <= '$fecha_actual' ORDER BY fec_cob DESC";
                    break;
                case 'ord_pago':
                    $sql = "SELECT TOP 1 fecha AS fec_emis FROM ord_pago WHERE fecha <= '$fecha_actual' ORDER BY fecha DESC";
                    break;
                case 'mov_ban':
                    $sql = "SELECT TOP 1 fecha AS fec_emis FROM mov_ban WHERE fecha <= '$fecha_actual' ORDER BY fecha DESC";
                    break;
            }

            if ($sql != "") {
                $consulta = sqlsrv_query($conn, $sql);
                if ($consulta) {
                    if ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
                        $res = $row; // Retorna array ['fec_emis' => Object]
                    }
                }
            }
            sqlsrv_close($conn);
        }
    }
    return $res;
}
?>