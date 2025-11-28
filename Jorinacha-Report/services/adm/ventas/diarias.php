<?php
// ../../services/adm/ventas/diarias.php

// Nota: Ya no necesitamos "require empresas.php" AQUI DENTRO, 
// porque la conexión la hará el archivo que llama a estas funciones.

/* CONSULTAR ARTICULOS VENDIDOS / FACTURAS */
function getFactura($conn, $fecha1, $fecha2, $data, $linea)
{
    if (!$conn) return ['total_art' => 0, 'tot_neto' => 0];

    // Definir SELECT
    $select = ($data == 'ven' || $data == 'ven2') ? "SUM(rf.total_art) as total" : "SUM(f.tot_neto) as total";
    
    $sql = "SELECT $select FROM factura f ";
    
    // JOINs Dinámicos
    if ($linea != 'todos' || $data == 'ven' || $data == 'ven2') {
        $sql .= " JOIN reng_fac rf ON f.fact_num = rf.fact_num ";
        if ($linea != 'todos') {
            $sql .= " JOIN art a ON a.co_art = rf.co_art ";
        }
    }

    $sql .= " WHERE f.anulada = 0 ";
    $params = array();

    // Filtro Fechas
    if ($data == 'ven' || $data == 'sin') {
        $sql .= " AND CONVERT(VARCHAR(8), f.fec_emis, 112) = ? ";
        $params[] = $fecha1;
    } else {
        $sql .= " AND CONVERT(VARCHAR(8), f.fec_emis, 112) BETWEEN ? AND ? ";
        $params[] = $fecha1;
        $params[] = $fecha2;
    }

    // Filtro Linea
    if ($linea != 'todos') {
        $sql .= " AND a.co_lin = ? ";
        $params[] = $linea;
    }

    $stmt = sqlsrv_query($conn, $sql, $params);
    
    $valor = 0;
    if ($stmt && sqlsrv_fetch($stmt)) {
        $valor = sqlsrv_get_field($stmt, 0);
    }
    
    // Retorno consistente
    return [
        'total_art' => ($data == 'ven' || $data == 'ven2') ? $valor : 0,
        'tot_neto'  => ($data == 'sin' || $data == 'sin2') ? $valor : 0
    ];
}

/* DEVOLUCIONES */
function getDev_cli($conn, $fecha1, $fecha2, $data, $linea)
{
    if (!$conn) return ['total_art' => 0, 'tot_neto' => 0];

    $select = ($data == 'ven' || $data == 'ven2') ? "SUM(rd.total_art)" : "SUM(d.tot_neto)";
    
    $sql = "SELECT $select FROM dev_cli d ";
    
    if ($linea != 'todos' || $data == 'ven' || $data == 'ven2') {
        $sql .= " JOIN reng_dvc rd ON d.fact_num = rd.fact_num ";
        if ($linea != 'todos') {
            $sql .= " JOIN art a ON a.co_art = rd.co_art ";
        }
    }

    $sql .= " WHERE d.anulada = 0 ";
    $params = array();

    if ($data == 'ven' || $data == 'sin') {
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
        'total_art' => ($data == 'ven' || $data == 'ven2') ? $valor : 0,
        'tot_neto'  => ($data == 'sin' || $data == 'sin2') ? $valor : 0
    ];
}

/* DEPOSITOS CAJA (Z) */
function getDep_caj($conn, $fecha1, $fecha2, $data)
{
    if (!$conn) return ['total_efec' => 0, 'total_tarj' => 0];
    
    $sql = "SELECT SUM(total_efec), SUM(total_tarj) FROM dep_caj WHERE ";
    $params = array();

    if ($data == 'sin') {
        $sql .= " CONVERT(VARCHAR(8), fecha, 112) = ? ";
        $params[] = $fecha1;
    } else {
        $sql .= " CONVERT(VARCHAR(8), fecha, 112) BETWEEN ? AND ? ";
        $params[] = $fecha1;
        $params[] = $fecha2;
    }
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt && sqlsrv_fetch($stmt)) {
        return ['total_efec' => sqlsrv_get_field($stmt, 0), 'total_tarj' => sqlsrv_get_field($stmt, 1)];
    }
    return ['total_efec' => 0, 'total_tarj' => 0];
}

/* MOVIMIENTOS BANCO */
function getMov_ban($conn, $fecha1, $fecha2, $data)
{
    if (!$conn) return ['monto_h' => 0];
    
    $sql = "SELECT SUM(monto_h) FROM mov_ban WHERE anulado = 0 AND origen = 'DEP' AND cta_egre='045' AND ";
    $params = array();

    if ($data == 'sin') {
        $sql .= " CONVERT(VARCHAR(8), fecha, 112) = ? ";
        $params[] = $fecha1;
    } else {
        $sql .= " CONVERT(VARCHAR(8), fecha, 112) BETWEEN ? AND ? ";
        $params[] = $fecha1;
        $params[] = $fecha2;
    }

    $stmt = sqlsrv_query($conn, $sql, $params);
    return ['monto_h' => ($stmt && sqlsrv_fetch($stmt)) ? sqlsrv_get_field($stmt, 0) : 0];
}

/* ORDENES DE PAGO */
function getOrd_pago($conn, $fecha1, $fecha2, $data)
{
    if (!$conn) return ['monto' => 0];

    $sql = "SELECT SUM(o.monto) FROM ord_pago o JOIN benefici b ON b.cod_ben = o.cod_ben WHERE o.anulada = 0 AND o.ord_num < 6000000 ";
    $params = array();

    if ($data == 'sin') { // Gastos
        $sql .= " AND o.cta_egre = '878' AND CONVERT(VARCHAR(8), o.fecha, 112) = ? ";
        $params[] = $fecha1;
    } elseif ($data == 'ven') { // Proveedores dia
        $sql .= " AND b.ben_des <> 'PREVIA SHOP' AND o.cta_egre <> '878' AND CONVERT(VARCHAR(8), o.fecha, 112) = ? ";
        $params[] = $fecha1;
    } elseif ($data == 'ven2') { // Proveedores rango
        $sql .= " AND b.ben_des <> 'PREVIA SHOP' AND o.cta_egre <> '878' AND CONVERT(VARCHAR(8), o.fecha, 112) BETWEEN ? AND ? ";
        $params[] = $fecha1;
        $params[] = $fecha2;
    } else { // Gastos rango
        $sql .= " AND o.cta_egre = '878' AND CONVERT(VARCHAR(8), o.fecha, 112) BETWEEN ? AND ? ";
        $params[] = $fecha1;
        $params[] = $fecha2;
    }

    $stmt = sqlsrv_query($conn, $sql, $params);
    return ['monto' => ($stmt && sqlsrv_fetch($stmt)) ? sqlsrv_get_field($stmt, 0) : 0];
}

/* TASAS (DOLAR) */
function getTasas($conn, $fecha1)
{
    if (!$conn) return ['tasa_v' => 1];
    
    // Busca la tasa de ese día o la más reciente anterior
    $sql = "SELECT TOP 1 tasa_v FROM tasas WHERE CONVERT(VARCHAR(8), fecha, 112) <= ? ORDER BY fecha DESC";
    $stmt = sqlsrv_query($conn, $sql, [$fecha1]);
    
    return ['tasa_v' => ($stmt && sqlsrv_fetch($stmt)) ? sqlsrv_get_field($stmt, 0) : 1];
}

/* ORDENES DE PAGO DETALLE (INF) */
function getOrd_pago_inf($conn, $fecha1, $fecha2)
{
    if (!$conn) return 0;
    
    $sql = "SELECT fecha, ord_num, descrip, monto FROM ord_pago 
            WHERE anulada = 0 AND ord_num < 6000000 AND cta_egre ='878'
            AND CONVERT(VARCHAR(8), fecha, 112) BETWEEN ? AND ?";
            
    $stmt = sqlsrv_query($conn, $sql, [$fecha1, $fecha2]);
    $res = [];
    
    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $res[] = $row;
        }
    }
    return $res;
}

/* FACTURA DETALLES (COBROS) */
function getFacturaDetalles($conn, $fecha1, $fecha2)
{
    if (!$conn) return 0;

    $sql = "SELECT rc.tp_doc_cob, rc.doc_num as FACTURA, rc.neto, c.cob_num as COBROS, c.fec_cob, 
            rt.tip_cob, rt.mont_doc, rt.cod_caja, rt.des_caja 
            FROM cobros c
            JOIN reng_tip rt ON c.cob_num = rt.cob_num
            JOIN reng_cob rc ON c.cob_num = rc.cob_num
            WHERE c.anulado=0 AND CONVERT(VARCHAR(8), c.fec_cob, 112) BETWEEN ? AND ?";

    $stmt = sqlsrv_query($conn, $sql, [$fecha1, $fecha2]);
    $res = [];

    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $res[] = $row;
        }
    }
    return $res;
}

/* MOVIMIENTOS BANCO DETALLE */
function getMov_banco($conn, $fecha)
{
    if (!$conn) return 0;

    $sql = "SELECT mov_num, codigo, tipo_op, doc_num, descrip, monto_h, monto_d, idb 
            FROM mov_ban WHERE CONVERT(VARCHAR(8), fecha, 112) = ? ORDER BY codigo";
            
    $stmt = sqlsrv_query($conn, $sql, [$fecha]);
    $res = [];

    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $res[] = $row;
        }
    }
    return $res;
}

/* LINEAS DE ARTICULOS */
function getLin_art($conn, $fecha1, $fecha2)
{
    if (!$conn) return ['co_lin'=>0, 'lin_des'=>0];

    $sql = "SELECT a.co_lin, la.lin_des FROM factura f
            JOIN reng_fac rf ON f.fact_num = rf.fact_num
            JOIN art a ON rf.co_art = a.co_art
            JOIN lin_art la ON la.co_lin = a.co_lin
            WHERE f.anulada=0 AND CONVERT(VARCHAR(8), f.fec_emis, 112) BETWEEN ? AND ?
            GROUP BY a.co_lin, la.lin_des";
            
    $stmt = sqlsrv_query($conn, $sql, [$fecha1, $fecha2]);
    
    if ($stmt && sqlsrv_fetch($stmt)) {
        return ['co_lin' => sqlsrv_get_field($stmt, 0), 'lin_des' => sqlsrv_get_field($stmt, 1)];
    }
    return ['co_lin'=>0, 'lin_des'=>0];
}

/* SUBLINEAS DE ARTICULOS */
function getSub_lin($conn, $fecha1, $fecha2)
{
    if (!$conn) return ['co_subl'=>0, 'subl_des'=>0];

    $sql = "SELECT a.co_subl, sl.subl_des, a.co_lin, la.lin_des FROM factura f
            JOIN reng_fac rf ON f.fact_num = rf.fact_num
            JOIN art a ON rf.co_art = a.co_art
            JOIN lin_art la ON la.co_lin = a.co_lin
            JOIN sub_lin sl ON sl.co_subl = a.co_subl
            WHERE f.anulada=0 AND CONVERT(VARCHAR(8), f.fec_emis, 112) BETWEEN ? AND ?
            GROUP BY a.co_subl, sl.subl_des, a.co_lin, la.lin_des
            ORDER BY la.lin_des";

    $stmt = sqlsrv_query($conn, $sql, [$fecha1, $fecha2]);
    
    if ($stmt && sqlsrv_fetch($stmt)) {
        return [
            'co_subl' => sqlsrv_get_field($stmt, 0), 
            'subl_des' => sqlsrv_get_field($stmt, 1),
            'co_lin' => sqlsrv_get_field($stmt, 2),
            'lin_des' => sqlsrv_get_field($stmt, 3)
        ];
    }
    return ['co_subl'=>0, 'subl_des'=>0, 'co_lin'=>0, 'lin_des'=>0];
}
?>