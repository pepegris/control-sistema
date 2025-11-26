<?php
/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA */
require_once "../../services/empresas.php"; // Usamos require_once para seguridad

// =============================================================================
// GESTOR DE CONEXIONES (Cache)
// Evita abrir 1700 conexiones. Reutiliza la conexión si la tienda ya está abierta.
// =============================================================================
$GLOBALS['db_connections'] = [];

function getConnection($dbName) {
    global $db_connections;

    // Si ya existe una conexión abierta y válida, la devolvemos
    if (isset($db_connections[$dbName]) && $db_connections[$dbName]) {
        return $db_connections[$dbName];
    }

    $serverName = "172.16.1.39"; 
    $connectionInfo = array(
        "Database" => $dbName,
        "UID" => "mezcla",
        "PWD" => "Zeus33$",
        "CharacterSet" => "UTF-8",
        "LoginTimeout" => 8 // 4 segundos maximo de espera por tienda
    );

    $conn = sqlsrv_connect($serverName, $connectionInfo);
    
    if ($conn) {
        $db_connections[$dbName] = $conn;
        return $conn;
    } else {
        return false;
    }
}

// =============================================================================
// CONSULTA MAESTRA (Lista de Artículos)
// =============================================================================

function getLin_art_all() {
    $conn = getConnection("PREVIA_A");
    if(!$conn) return [];

    $sql = "SELECT lin_art.co_lin, lin_art.lin_des 
            FROM lin_art 
            INNER JOIN art ON lin_art.co_lin=art.co_lin
            WHERE art.anulado = 0 
            GROUP BY lin_art.co_lin, lin_art.lin_des";

    $consulta = sqlsrv_query($conn, $sql);
    $lin_art = [];

    if($consulta){
        while ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
            $lin_art[] = $row;
        }
    }
    return $lin_art;
}

function getArt($sede, $linea, $co_art, $almacen) {
    $database = Database($sede);
    $conn = getConnection($database);

    if (!$conn) return [];

    $params = array();

    // Caso: Listado General (El más usado en el reporte)
    if ($sede == 'Previa Shop' and $almacen == 0 and $co_art == 0) {
        // USO DE LEFT JOIN: Evita que desaparezcan articulos si falta color/cat/subl
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

// =============================================================================
// FUNCIONES BATCH (CARGA MASIVA OPTIMIZADA)
// Estas son las funciones que hacen que el reporte vuele
// =============================================================================

/* 1. CONSULTA MASIVA DE STOCK EN TIENDAS */
function getBatchStock($sede, $listaArticulos) {
    if (empty($listaArticulos)) return [];
    
    $database = Database($sede);
    if (!$database) return [];
    
    $conn = getConnection($database);
    if (!$conn) return [];

    // Limpieza de comillas simples para seguridad
    $cleanList = array_map(function($code) {
        return str_replace("'", "''", $code); 
    }, $listaArticulos);
    
    // Creamos una lista separada por comas: 'COD1','COD2','COD3'
    $inList = "'" . implode("','", $cleanList) . "'";
    
    $sql = "SELECT RTRIM(co_art) as co_art, stock_act, prec_vta5 FROM art WHERE co_art IN ($inList)";
    
    $consulta = sqlsrv_query($conn, $sql);
    $data = [];
    
    if ($consulta) {
        while ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
            // Guardamos con el codigo como CLAVE para acceso instantaneo
            $data[trim($row['co_art'])] = [
                'stock' => $row['stock_act'],
                'precio' => $row['prec_vta5']
            ];
        }
    }
    return $data;
}

/* 2. CONSULTA MASIVA DE VENTAS */
function getBatchVentas($sede, $listaArticulos, $fecha1, $fecha2) {
    if (empty($listaArticulos)) return [];

    $database = Database($sede);
    if (!$database) return [];
    
    $conn = getConnection($database);
    if (!$conn) return [];

    $cleanList = array_map(function($code) { return str_replace("'", "''", $code); }, $listaArticulos);
    $inList = "'" . implode("','", $cleanList) . "'";

    $sql = "SELECT reng_fac.co_art, SUM(reng_fac.total_art) as total_art 
            FROM reng_fac 
            INNER JOIN factura ON reng_fac.fact_num = factura.fact_num
            WHERE reng_fac.co_art IN ($inList) 
            AND reng_fac.fec_lote BETWEEN ? AND ? 
            AND factura.anulada = 0
            GROUP BY reng_fac.co_art";

    $params = array($fecha1, $fecha2);
    $consulta = sqlsrv_query($conn, $sql, $params);
    $data = [];

    if ($consulta) {
        while ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
            $data[trim($row['co_art'])] = $row['total_art'];
        }
    }
    return $data;
}

/* 3. CONSULTA MASIVA DE PEDIDOS (PREVIA SHOP) */
function getBatchPedidos($listaArticulos) {
    if (empty($listaArticulos)) return [];

    $conn = getConnection("PREVIA_A");
    if (!$conn) return [];

    $cleanList = array_map(function($code) { return str_replace("'", "''", $code); }, $listaArticulos);
    $inList = "'" . implode("','", $cleanList) . "'";

    // Traer sumatoria de pedidos pendientes BOLE
    $sql = "SELECT reng_ped.co_art, SUM(reng_ped.total_art) as total_art 
            FROM reng_ped 
            INNER JOIN pedidos ON reng_ped.fact_num = pedidos.fact_num
            WHERE reng_ped.co_art IN ($inList) 
            AND pedidos.status <= 1 
            AND pedidos.anulada = 0 
            AND reng_ped.co_alma = 'BOLE'
            GROUP BY reng_ped.co_art";

    $consulta = sqlsrv_query($conn, $sql);
    $data = [];

    if ($consulta) {
        while ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
            $data[trim($row['co_art'])] = $row['total_art'];
        }
    }
    return $data;
}

// =============================================================================
// FUNCIONES LEGACY (Mantenidas por compatibilidad, pero optimizadas)
// =============================================================================

function getPedidos($sede, $co_art) {
    $cliente = Cliente($sede);
    $conn = getConnection("PREVIA_A");
    if(!$conn) return ['total_art' => 0, 'status' => 3];

    $params = array($co_art);
    if ($cliente != null) {
        $sql = "SELECT SUM(reng_ped.total_art) as total_art FROM reng_ped INNER JOIN pedidos ON reng_ped.fact_num=pedidos.fact_num WHERE reng_ped.co_art = ? AND pedidos.co_cli=? AND pedidos.anulada=0 AND pedidos.status <= 1 AND reng_ped.co_alma = 'BOLE'";
        $params[] = $cliente;
    } else {
        $sql = "SELECT SUM(reng_ped.total_art) as total_art FROM reng_ped INNER JOIN pedidos ON reng_ped.fact_num=pedidos.fact_num WHERE reng_ped.co_art = ? AND pedidos.status <= 1 AND pedidos.anulada=0 AND reng_ped.co_alma = 'BOLE'";
    }

    $consulta = sqlsrv_query($conn, $sql, $params);
    $pedidos = ['total_art' => 0, 'status' => 0];
    if ($consulta && $row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
        $pedidos['total_art'] = $row['total_art'] ? $row['total_art'] : 0;
    }
    return $pedidos;
}

function getReng_fac($sede, $co_art, $f1, $f2) {
    $db = Database($sede);
    if(!$db) return 0;
    $conn = getConnection($db);
    if(!$conn) return 0;
    
    $sql = "SELECT SUM(reng_fac.total_art) as total_art FROM reng_fac INNER JOIN factura ON reng_fac.fact_num=factura.fact_num WHERE reng_fac.co_art=? AND reng_fac.fec_lote BETWEEN ? AND ? AND factura.anulada=0";
    $params = array($co_art, $f1, $f2);
    $consulta = sqlsrv_query($conn, $sql, $params);
    if ($consulta && $row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
        return $row['total_art'] ? $row['total_art'] : 0;
    }
    return 0;
}

function getArt_stock_tiendas($sede, $co_art) {
    $db = Database($sede);
    if(!$db) return [];
    $conn = getConnection($db);
    if(!$conn) return [];
    
    $sql = "SELECT stock_act, prec_vta5 FROM art WHERE co_art = ?";
    $consulta = sqlsrv_query($conn, $sql, array($co_art));
    $art = [];
    if($consulta){
        while($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)){ $art[]=$row; }
    }
    return $art;
}
?>


