<?php
// services/adm/fallas/fallas-report.php

// 1. CARGAMOS LA CONFIGURACIÓN CENTRALIZADA
require_once "../../services/empresas.php"; 

// =============================================================================
// GESTOR DE CONEXIONES (Cache con Timeout Inteligente)
// =============================================================================
$GLOBALS['db_connections'] = [];

function getConnectionDinamica($ip, $dbName, $timeout = 3) {
    global $db_connections;
    $cacheKey = $ip . "_" . $dbName; // Clave única

    if (isset($db_connections[$cacheKey]) && $db_connections[$cacheKey]) {
        return $db_connections[$cacheKey];
    }

    $connectionInfo = array(
        "Database" => $dbName,
        "UID" => "mezcla",
        "PWD" => "Zeus33$",
        "CharacterSet" => "UTF-8",
        "LoginTimeout" => $timeout 
    );

    // Usamos @ para suprimir errores visuales si la VPN falla
    $conn = @sqlsrv_connect($ip, $connectionInfo);
    
    if ($conn) {
        $db_connections[$cacheKey] = $conn;
        return $conn;
    } else {
        return false;
    }
}

// Wrapper legacy (Compatible con reportes viejos)
function getConnection($dbName) {
    return getConnectionDinamica("172.16.1.19", $dbName, 10);
}

// =============================================================================
// CONSULTA MASIVA DE STOCK (DOBLE VERIFICACIÓN: VPN vs LOCAL)
// =============================================================================
function getBatchStock($sede, $listaArticulos) {
    global $lista_replicas; // Usamos el array que viene de empresas.php
    
    if (empty($listaArticulos)) return [];

    $cleanList = array_map(function($code) { return str_replace("'", "''", $code); }, $listaArticulos);
    $inList = "'" . implode("','", $cleanList) . "'";
    $sql = "SELECT RTRIM(co_art) as co_art, stock_act, prec_vta5 FROM art WHERE co_art IN ($inList)";

    $dataRemota = [];
    $dataLocal = [];
    $dataFinal = [];

    // --- A) INTENTO REMOTO (VPN) ---
    if (isset($lista_replicas[$sede])) {
        $conf = $lista_replicas[$sede];
        // Timeout corto (4s) para no congelar el reporte si no hay internet
        $connRemota = getConnectionDinamica($conf['ip'], $conf['db'], 4); 
        
        if ($connRemota) {
            $stmt = sqlsrv_query($connRemota, $sql);
            if ($stmt) {
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $dataRemota[trim($row['co_art'])] = [
                        'stock'  => (float)$row['stock_act'],
                        'precio' => (float)$row['prec_vta5']
                    ];
                }
            }
        }
    }

    // --- B) INTENTO LOCAL (SQL2K8 - REPLICA) ---
    // Si existe en el array usamos 'db_local', sino usamos Database2() legacy
    $dbNameLocal = isset($lista_replicas[$sede]) ? $lista_replicas[$sede]['db_local'] : Database2($sede);
    
    if ($dbNameLocal) {
        $connLocal = getConnectionDinamica("172.16.1.19", $dbNameLocal, 10);
        if ($connLocal) {
            $stmt = sqlsrv_query($connLocal, $sql);
            if ($stmt) {
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $dataLocal[trim($row['co_art'])] = [
                        'stock'  => (float)$row['stock_act'],
                        'precio' => (float)$row['prec_vta5']
                    ];
                }
            }
        }
    }

    // --- C) FUSIÓN INTELIGENTE (MAX STOCK) ---
    foreach ($listaArticulos as $codigo) {
        $codigo = trim($codigo);
        
        $stockR = isset($dataRemota[$codigo]) ? $dataRemota[$codigo]['stock'] : 0;
        $stockL = isset($dataLocal[$codigo])  ? $dataLocal[$codigo]['stock']  : 0;
        
        // REGLA DE ORO: Nos quedamos con el stock mayor (Asumiendo que la réplica puede estar atrasada o la VPN caída)
        $stockFinal = max($stockR, $stockL);
        
        // PRECIO: Prioridad Remoto > Local
        $precioFinal = 0;
        if (isset($dataRemota[$codigo])) {
            $precioFinal = $dataRemota[$codigo]['precio'];
        } elseif (isset($dataLocal[$codigo])) {
            $precioFinal = $dataLocal[$codigo]['precio'];
        }

        if ($stockFinal != 0 || $precioFinal != 0) {
            $dataFinal[$codigo] = [
                'stock' => $stockFinal,
                'precio' => $precioFinal
            ];
        }
    }

    return $dataFinal;
}

// =============================================================================
// CONSULTA MASIVA DE VENTAS
// =============================================================================
function getBatchVentas($sede, $listaArticulos, $fecha1, $fecha2) {
    global $lista_replicas;
    if (empty($listaArticulos)) return [];

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
    $data = [];
    $conn = false;

    // 1. Prioridad VPN
    if (isset($lista_replicas[$sede])) {
        $conf = $lista_replicas[$sede];
        $conn = getConnectionDinamica($conf['ip'], $conf['db'], 4);
    }

    // 2. Fallback Local
    if (!$conn) {
        $dbNameLocal = isset($lista_replicas[$sede]) ? $lista_replicas[$sede]['db_local'] : Database2($sede);
        if ($dbNameLocal) {
            $conn = getConnectionDinamica("172.16.1.19", $dbNameLocal, 10);
        }
    }

    if ($conn) {
        $consulta = sqlsrv_query($conn, $sql, $params);
        if ($consulta) {
            while ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
                $data[trim($row['co_art'])] = $row['total_art'];
            }
        }
    }
    return $data;
}

// =============================================================================
// FUNCIONES DE APOYO (Previa Shop Local)
// =============================================================================

function getBatchPedidos($listaArticulos) {
    $conn = getConnection("PREVIA_A"); // Siempre local
    if (!$conn) return [];

    $cleanList = array_map(function($code) { return str_replace("'", "''", $code); }, $listaArticulos);
    $inList = "'" . implode("','", $cleanList) . "'";

    $sql = "SELECT reng_ped.co_art, SUM(reng_ped.total_art) as total_art 
            FROM reng_ped 
            INNER JOIN pedidos ON reng_ped.fact_num = pedidos.fact_num
            WHERE reng_ped.co_art IN ($inList) 
            AND pedidos.status <= 1 AND pedidos.anulada = 0 AND reng_ped.co_alma = 'BOLE'
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

function getArt($sede, $linea, $co_art, $almacen) {
    $conn = getConnection("PREVIA_A");
    if (!$conn) return [];
    $params = array();

    if ($sede == 'Previa Shop' and $almacen == 0 and $co_art == 0) {
        $sql = "SELECT RTRIM(art.co_art) as co_art,
                    ISNULL(RTRIM(sub_lin.subl_des), 'S/D') as co_subl, 
                    ISNULL(RTRIM(cat_art.cat_des), 'S/D') as co_cat,
                    art.prec_vta3, art.prec_vta4, art.prec_vta5, 
                    ISNULL(st_almac.stock_act, 0) as stock_act, 
                    ISNULL(RTRIM(colores.des_col), 'S/D') as co_color, 
                    ISNULL(RTRIM(lin_art.lin_des), 'S/D') as co_lin, 
                    art.ubicacion
                FROM art 
                LEFT JOIN lin_art ON art.co_lin = lin_art.co_lin
                LEFT JOIN sub_lin ON art.co_lin = sub_lin.co_lin AND art.co_subl = sub_lin.co_subl
                LEFT JOIN cat_art ON art.co_cat = cat_art.co_cat
                LEFT JOIN colores ON art.co_color = colores.co_col
                LEFT JOIN st_almac ON art.co_art = st_almac.co_art AND st_almac.co_alma = 'BOLE'
                WHERE art.co_lin = ? AND art.anulado = 0 
                ORDER BY art.co_subl DESC";
        $params[] = $linea;
    } else { return []; }

    $consulta = sqlsrv_query($conn, $sql, $params);
    $art = [];
    if ($consulta) {
        while ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
            $art[] = $row;
        }
    }
    return $art;
}

function getLin_art_all() {
    $conn = getConnection("PREVIA_A");
    if(!$conn) return [];
    $sql = "SELECT lin_art.co_lin, lin_art.lin_des FROM lin_art INNER JOIN art ON lin_art.co_lin=art.co_lin WHERE art.anulado = 0 GROUP BY lin_art.co_lin, lin_art.lin_des";
    $consulta = sqlsrv_query($conn, $sql);
    $lin_art = [];
    if($consulta){
        while ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) { $lin_art[] = $row; }
    }
    return $lin_art;
}
?>