<?php
/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA */
require "../../services/empresas.php";

// Variable global para cachear conexiones y no abrir miles
$GLOBALS['db_connections'] = [];

function getConnection($dbName) {
    global $db_connections;

    // Si ya existe una conexión abierta para esta base de datos, la reusamos
    if (isset($db_connections[$dbName]) && $db_connections[$dbName]) {
        return $db_connections[$dbName];
    }

    $serverName = "172.16.1.39"; // IP FIJA
    $connectionInfo = array(
        "Database" => $dbName,
        "UID" => "mezcla",
        "PWD" => "Zeus33$",
        "CharacterSet" => "UTF-8",
        "LoginTimeout" => 3 // Timeout corto para no colgar el reporte si una tienda está offline
    );

    $conn = sqlsrv_connect($serverName, $connectionInfo);
    
    if ($conn) {
        $db_connections[$dbName] = $conn;
        return $conn;
    } else {
        return false; // Retorna false si la tienda no conecta
    }
}

/* CONSULTAR TODAS LAS LINEAS */
function getLin_art_all()
{
    $conn = getConnection("PREVIA_A");
    if(!$conn) return [];

    $sql = "SELECT lin_art.co_lin, lin_art.lin_des 
            FROM lin_art 
            INNER JOIN art ON lin_art.co_lin=art.co_lin
            WHERE art.anulado = 0 -- Solo validar que no esté anulado
            GROUP BY lin_art.co_lin,lin_art.lin_des";

    $consulta = sqlsrv_query($conn, $sql);
    $lin_art = [];

    if($consulta){
        while ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
            $lin_art[] = $row;
        }
    }
    return $lin_art;
}

/* CONSULTAR ARTICULOS (MASTER) */
function getArt($sede, $linea, $co_art, $almacen)
{
    $database = Database($sede);
    $conn = getConnection($database);

    if (!$conn) return [];

    // Parametrización para evitar inyección SQL
    $params = array();

    if ($sede == 'Previa Shop' and $almacen == 0 and $co_art == 0) {
        // CORRECCIÓN: Usamos LEFT JOIN para que si falta el color o categoria, el articulo salga igual
        // CORRECCIÓN: Quitamos el filtro de fecha y precio estricto que ocultaba modelos
        $sql = "SELECT 
                    RTRIM(art.co_art) as co_art,
                    ISNULL(RTRIM(sub_lin.subl_des), 'S/D') as co_subl, 
                    ISNULL(RTRIM(cat_art.cat_des), 'S/D') as co_cat,
                    art.prec_vta3, art.prec_vta4, art.prec_vta5, 
                    art.stock_act, 
                    ISNULL(RTRIM(colores.des_col), 'S/D') as co_color, 
                    ISNULL(RTRIM(lin_art.lin_des), 'S/D') as co_lin, 
                    art.ubicacion
                FROM art 
                LEFT JOIN lin_art ON art.co_lin = lin_art.co_lin
                LEFT JOIN sub_lin ON art.co_subl = sub_lin.co_subl
                LEFT JOIN cat_art ON art.co_cat = cat_art.co_cat
                LEFT JOIN colores ON art.co_color = colores.co_col
                WHERE art.co_lin = ? AND art.anulado = 0
                ORDER BY art.co_subl DESC";
        
        $params[] = $linea;

    } elseif ($almacen == 'BOLE' and $sede == 'Previa Shop' and $co_art != 0) {
        $sql = "SELECT stock_act FROM st_almac WHERE co_art = ? AND co_alma='BOLE'";
        $params[] = $co_art;
    } else {
        // Consulta individual
        $sql = "SELECT RTRIM(art.co_art) as co_art, art.stock_act, art.prec_vta5, art.ubicacion
                FROM art 
                WHERE art.co_lin = ? AND art.co_art = ?";
        $params[] = $linea;
        $params[] = $co_art;
    }

    $consulta = sqlsrv_query($conn, $sql, $params);
    $art = [];

    if ($consulta) {
        while ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
            $art[] = $row;
        }
    }
    return $art;
}

/* CONSULTAR PEDIDOS PENDIENTES */
function getPedidos($sede, $co_art)
{
    // Optimizacion: Si no hay cliente, retornamos 0 rapido
    $cliente = Cliente($sede);
    $conn = getConnection("PREVIA_A"); // Pedidos siempre busca en Previa?

    if(!$conn) return ['total_art' => 0, 'status' => 3];

    $params = array($co_art);
    
    if ($cliente != null) {
        $sql = "SELECT SUM(reng_ped.total_art) as total_art 
                FROM reng_ped 
                INNER JOIN pedidos ON reng_ped.fact_num = pedidos.fact_num
                WHERE reng_ped.co_art = ? 
                AND pedidos.co_cli = ? 
                AND pedidos.anulada = 0 AND pedidos.status <= 1 AND reng_ped.co_alma = 'BOLE'";
        $params[] = $cliente;
    } else {
        $sql = "SELECT SUM(reng_ped.total_art) as total_art 
                FROM reng_ped 
                INNER JOIN pedidos ON reng_ped.fact_num = pedidos.fact_num
                WHERE reng_ped.co_art = ? 
                AND pedidos.status <= 1 AND pedidos.anulada = 0 AND reng_ped.co_alma = 'BOLE'";
    }

    $consulta = sqlsrv_query($conn, $sql, $params);
    
    $pedidos = ['total_art' => 0, 'status' => 0];

    if ($consulta && $row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
        $pedidos['total_art'] = $row['total_art'] ? $row['total_art'] : 0;
    }

    return $pedidos;
}

/* CONSULTAR VENTAS (RENG_FAC) */
function getReng_fac($sede, $co_art, $fecha1, $fecha2)
{
    $database = Database($sede);
    if (!$database) return 0;

    $conn = getConnection($database);
    if (!$conn) return 0; // Si la tienda no conecta, retornamos 0, no error

    $sql = "SELECT SUM(reng_fac.total_art) as total_art 
            FROM reng_fac 
            INNER JOIN factura ON reng_fac.fact_num = factura.fact_num
            WHERE reng_fac.co_art = ? 
            AND reng_fac.fec_lote BETWEEN ? AND ? 
            AND factura.anulada = 0";

    $params = array($co_art, $fecha1, $fecha2);
    $consulta = sqlsrv_query($conn, $sql, $params);

    if ($consulta && $row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
        return $row['total_art'] ? $row['total_art'] : 0;
    }

    return 0;
}

/* STOCK EN TIENDAS */
function getArt_stock_tiendas($sede, $co_art)
{
    $database = Database($sede);
    if (!$database) return [];

    $conn = getConnection($database);
    if (!$conn) return [];

    // Simplificado para solo traer lo necesario
    $sql = "SELECT stock_act, prec_vta5 FROM art WHERE co_art = ?";
    $params = array($co_art);

    $consulta = sqlsrv_query($conn, $sql, $params);
    $art = [];

    if ($consulta) {
        while ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
            $art[] = $row;
        }
    }
    return $art;
}
?>