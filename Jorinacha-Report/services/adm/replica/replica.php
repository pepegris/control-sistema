<?php
require_once "../../services/empresas.php"; // Usar require_once es mejor

function Replica($sede)
{
    $database = Database($sede);
    $res = null; // Inicializamos en null por defecto

    if ($database) {
        $serverName = "172.16.1.39";
        // Timeout de Login: Si en 3 segundos no conecta, pasa a la siguiente (Para que no se cuelgue la web)
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
            // Top 1 es suficiente
            $sql = "SELECT TOP 1 fec_emis FROM factura WHERE fec_emis <= '$fecha_actual' ORDER BY fec_emis DESC";
            
            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta) {
                if ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
                    $res = $row; // Guardamos el array completo
                }
            }
            // ¡IMPORTANTE! Cerrar la conexión
            sqlsrv_close($conn); 
        }
    }
    return $res;
}

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

// ... (Tus funciones anteriores Replica e Inventario) ...

function Replica_Full_Details($sede)
{
    $database = Database($sede);
    $datos = [
        'factura' => null,
        'cobros' => null,
        'ord_pago' => null,
        'mov_ban' => null
    ];

    if ($database) {
        $serverName = "172.16.1.39";
        $connectionInfo = array(
            "Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", 
            "CharacterSet" => "UTF-8", "LoginTimeout" => 3
        );
        
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if ($conn) {
            $fecha_actual = date("Ymd");
            
            // TRUCO SQL: Usamos subconsultas para traer los 4 datos en 1 solo viaje
            // Esto es 4 veces más rápido que hacer 4 llamadas separadas.
            $sql = "
            SELECT 
                (SELECT TOP 1 fec_emis FROM factura  WHERE fec_emis <= '$fecha_actual' ORDER BY fec_emis DESC) as factura,
                (SELECT TOP 1 fec_cob  FROM cobros   WHERE fec_cob  <= '$fecha_actual' ORDER BY fec_cob DESC)  as cobros,
                (SELECT TOP 1 fecha    FROM ord_pago WHERE fecha    <= '$fecha_actual' ORDER BY fecha DESC)    as ord_pago,
                (SELECT TOP 1 fecha    FROM mov_ban  WHERE fecha    <= '$fecha_actual' ORDER BY fecha DESC)    as mov_ban
            ";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta) {
                if ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
                    $datos = $row; // Devuelve un array con las 4 fechas (Objetos DateTime)
                }
            }
            sqlsrv_close($conn);
        }
    }
    return $datos;
}
?>
?>