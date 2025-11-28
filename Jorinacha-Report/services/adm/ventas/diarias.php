<?php
// Nota: Se asume que la función Database($sede) y la lista $sedes_ar se cargan
// desde otro archivo (por ejemplo, ../../services/mysql.php).

// --- Función genérica para Totales de Factura ---
function getFactura($conn, $fecha1, $fecha2, $tipo, $linea) {
    if (!$conn) return ['total_art' => 0, 'tot_neto' => 0];

    // Definir qué sumamos
    $campoSumar = ($tipo == 'ven' || $tipo == 'ven2') ? "SUM(rf.total_art) as total" : "SUM(f.tot_neto) as total";
    
    $sql = "SELECT $campoSumar FROM factura f ";
    
    // Joins solo si son necesarios
    if ($linea != 'todos' || $tipo == 'ven' || $tipo == 'ven2') {
        $sql .= " JOIN reng_fac rf ON f.fact_num = rf.fact_num ";
        if ($linea != 'todos') $sql .= " JOIN art a ON a.co_art = rf.co_art ";
    }

    $sql .= " WHERE f.anulada = 0 ";
    $params = [];

    // Filtro de fechas (CORRECCIÓN YYYYMMDD)
    if ($tipo == 'ven' || $tipo == 'sin') {
        $sql .= " AND CONVERT(VARCHAR(8), f.fec_emis, 112) = ? ";
        $params[] = $fecha1;
    } else {
        $sql .= " AND CONVERT(VARCHAR(8), f.fec_emis, 112) BETWEEN ? AND ? ";
        $params[] = $fecha1;
        $params[] = $fecha2;
    }

    // Filtro de linea
    if ($linea != 'todos') {
        $sql .= " AND a.co_lin = ? ";
        $params[] = $linea;
    }

    $stmt = sqlsrv_query($conn, $sql, $params);
    
    $valor = 0;
    if ($stmt && sqlsrv_fetch($stmt)) {
        $valor = sqlsrv_get_field($stmt, 0);
    }
    
    // Normalizamos la salida
    return [
        'total_art' => ($tipo == 'ven' || $tipo == 'ven2') ? $valor : 0,
        'tot_neto'  => ($tipo == 'sin' || $tipo == 'sin2') ? $valor : 0
    ];
}

// --- Función Devoluciones ---
function getDev_cli($conn, $fecha1, $fecha2, $tipo, $linea) {
    if (!$conn) return ['total_art' => 0, 'tot_neto' => 0];

    $campoSumar = ($tipo == 'ven' || $tipo == 'ven2') ? "SUM(rd.total_art)" : "SUM(d.tot_neto)";
    
    $sql = "SELECT $campoSumar FROM dev_cli d ";
    if ($linea != 'todos' || $tipo == 'ven') {
        $sql .= " JOIN reng_dvc rd ON d.fact_num = rd.fact_num ";
        if ($linea != 'todos') $sql .= " JOIN art a ON a.co_art = rd.co_art ";
    }

    $sql .= " WHERE d.anulada = 0 ";
    $params = [];

    // Filtro de fechas (CORRECCIÓN YYYYMMDD)
    if ($tipo == 'ven' || $tipo == 'sin') {
        $sql .= " AND CONVERT(VARCHAR(8), d.fec_emis, 112) = ? ";
        $params[] = $fecha1;
    } else {
        $sql .= " AND CONVERT(VARCHAR(8), d.fec_emis, 112) BETWEEN ? AND ? ";
        $params[] = $fecha1; 
        $params[] = $fecha2;
    }
    
    if ($linea != 'todos') {
        $sql .= " AND a.co_lin = ? ";
        $params[] = $linea;
    }

    $stmt = sqlsrv_query($conn, $sql, $params);
    $valor = ($stmt && sqlsrv_fetch($stmt)) ? sqlsrv_get_field($stmt, 0) : 0;

    return [
        'total_art' => ($tipo == 'ven') ? $valor : 0,
        'tot_neto'  => ($tipo == 'sin') ? $valor : 0
    ];
}

// --- Depósitos de Caja (Z) ---
function getDep_caj($conn, $fecha1) {
    if (!$conn) return ['total_efec' => 0, 'total_tarj' => 0];
    
    // CORRECCIÓN YYYYMMDD
    $sql = "SELECT SUM(total_efec), SUM(total_tarj) FROM dep_caj WHERE CONVERT(VARCHAR(8), fecha, 112) = ?";
    $stmt = sqlsrv_query($conn, $sql, [$fecha1]);
    
    if ($stmt && sqlsrv_fetch($stmt)) {
        return ['total_efec' => sqlsrv_get_field($stmt, 0), 'total_tarj' => sqlsrv_get_field($stmt, 1)];
    }
    return ['total_efec' => 0, 'total_tarj' => 0];
}

// --- Movimientos Banco (Depósitos confirmados) ---
function getMov_ban($conn, $fecha1) {
    if (!$conn) return ['monto_h' => 0];
    
    // CORRECCIÓN YYYYMMDD
    // OJO: Asumo que cta_egre='045' es tu cuenta puente o caja principal
    $sql = "SELECT SUM(monto_h) FROM mov_ban WHERE CONVERT(VARCHAR(8), fecha, 112) = ? AND anulado = 0 AND origen = 'DEP' AND cta_egre='045'";
    $stmt = sqlsrv_query($conn, $sql, [$fecha1]);
    
    return ['monto_h' => ($stmt && sqlsrv_fetch($stmt)) ? sqlsrv_get_field($stmt, 0) : 0];
}

// --- Ordenes de Pago (Gastos y Vales) ---
function getOrd_pago($conn, $fecha1, $tipo) {
    if (!$conn) return ['monto' => 0];

    // CORRECCIÓN YYYYMMDD
    $sql = "SELECT SUM(o.monto) FROM ord_pago o JOIN benefici b ON b.cod_ben = o.cod_ben WHERE CONVERT(VARCHAR(8), o.fecha, 112) = ? AND o.anulada = 0 AND o.ord_num < 6000000 ";
    
    if ($tipo == 'sin') { // Gastos generales
        $sql .= " AND o.cta_egre = '878'";
    } else { // Proveedores u otros
        $sql .= " AND b.ben_des <> 'PREVIA SHOP' AND o.cta_egre <> '878'";
    }

    $stmt = sqlsrv_query($conn, $sql, [$fecha1]);
    return ['monto' => ($stmt && sqlsrv_fetch($stmt)) ? sqlsrv_get_field($stmt, 0) : 0];
}

// --- Tasas (Dólar) ---
function getTasas($conn, $fecha) {
    if (!$conn) return ['tasa_v' => 1];
    
    // CORRECCIÓN YYYYMMDD
    // Busca la tasa de ese día o la más reciente anterior
    $sql = "SELECT TOP 1 tasa_v FROM tasas WHERE CONVERT(VARCHAR(8), fecha, 112) <= ? ORDER BY fecha DESC";
    $stmt = sqlsrv_query($conn, $sql, [$fecha]);
    
    return ['tasa_v' => ($stmt && sqlsrv_fetch($stmt)) ? sqlsrv_get_field($stmt, 0) : 1];
}
?>