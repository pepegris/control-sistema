<?php
// ../../services/adm/ventas/diarias.php

require_once "../../services/empresas.php";

/* 1. FACTURAS / VENTAS */
function getFactura($sede, $fecha1, $fecha2, $data, $linea)
{
    $database = Database($sede);
    if ($database != null) {
        try {
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);
            
            if (!$conn) return ['total_art' => 0, 'tot_neto' => 0];

            // Definir qué sumamos
            $select = ($data == 'ven' || $data == 'ven2') ? "SUM(rf.total_art) as total" : "SUM(f.tot_neto) as total";
            
            $sql = "SELECT $select FROM factura f ";
            
            // JOINS necesarios
            if ($linea != 'todos' || $data == 'ven' || $data == 'ven2') {
                $sql .= " JOIN reng_fac rf ON f.fact_num = rf.fact_num ";
                if ($linea != 'todos') $sql .= " JOIN art a ON a.co_art = rf.co_art ";
            }

            $sql .= " WHERE f.anulada = 0 ";

            // --- MEJORA: FIX DE FECHAS (CONVERT) ---
            if ($data == 'ven' || $data == 'sin') {
                $sql .= " AND CONVERT(VARCHAR(8), f.fec_emis, 112) = '$fecha1' ";
            } else {
                $sql .= " AND CONVERT(VARCHAR(8), f.fec_emis, 112) BETWEEN '$fecha1' AND '$fecha2' ";
            }

            if ($linea != 'todos') {
                $sql .= " AND a.co_lin = '$linea' ";
            }

            $consulta = sqlsrv_query($conn, $sql);
            $res = ['total_art' => 0, 'tot_neto' => 0];

            if ($consulta && sqlsrv_has_rows($consulta)) {
                $row = sqlsrv_fetch_array($consulta);
                $valor = $row['total'];
                
                $res['total_art'] = ($data == 'ven' || $data == 'ven2') ? $valor : 0;
                $res['tot_neto']  = ($data == 'sin' || $data == 'sin2') ? $valor : 0;
            }
            
            sqlsrv_close($conn);
            return $res;

        } catch (\Throwable $th) { return ['total_art' => 0, 'tot_neto' => 0]; }
    } 
    return 0;
}

/* 2. DEVOLUCIONES */
function getDev_cli($sede, $fecha1, $fecha2, $data, $linea)
{
    $database = Database($sede);
    if ($database != null) {
        try {
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);
            if (!$conn) return ['total_art' => 0, 'tot_neto' => 0];

            $select = ($data == 'ven' || $data == 'ven2') ? "SUM(rd.total_art) as total" : "SUM(d.tot_neto) as total";
            $sql = "SELECT $select FROM dev_cli d ";
            
            if ($linea != 'todos' || $data == 'ven' || $data == 'ven2') {
                $sql .= " JOIN reng_dvc rd ON d.fact_num = rd.fact_num ";
                if ($linea != 'todos') $sql .= " JOIN art a ON a.co_art = rd.co_art ";
            }

            $sql .= " WHERE d.anulada = 0 ";

            // --- MEJORA: FIX DE FECHAS ---
            if ($data == 'ven' || $data == 'sin') {
                $sql .= " AND CONVERT(VARCHAR(8), d.fec_emis, 112) = '$fecha1' ";
            } else {
                $sql .= " AND CONVERT(VARCHAR(8), d.fec_emis, 112) BETWEEN '$fecha1' AND '$fecha2' ";
            }

            if ($linea != 'todos') $sql .= " AND a.co_lin = '$linea' ";

            $consulta = sqlsrv_query($conn, $sql);
            $res = ['total_art' => 0, 'tot_neto' => 0];

            if ($consulta && sqlsrv_has_rows($consulta)) {
                $row = sqlsrv_fetch_array($consulta);
                $valor = $row['total'];
                $res['total_art'] = ($data == 'ven' || $data == 'ven2') ? $valor : 0;
                $res['tot_neto']  = ($data == 'sin' || $data == 'sin2') ? $valor : 0;
            }
            sqlsrv_close($conn);
            return $res;
        } catch (\Throwable $th) { return ['total_art' => 0, 'tot_neto' => 0]; }
    }
    return 0;
}

/* 3. DEPOSITOS CAJA (Z) */
function getDep_caj($sede, $fecha1, $fecha2, $data)
{
    $database = Database($sede);
    if ($database != null) {
        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if (!$conn) return ['total_efec' => 0, 'total_tarj' => 0];

        $sql = "SELECT SUM(total_efec) as total_efec, SUM(total_tarj) as total_tarj FROM dep_caj WHERE ";
        
        // --- MEJORA: FIX DE FECHAS ---
        if ($data == 'sin') {
            $sql .= " CONVERT(VARCHAR(8), fecha, 112) = '$fecha1' ";
        } else {
            $sql .= " CONVERT(VARCHAR(8), fecha, 112) BETWEEN '$fecha1' AND '$fecha2' ";
        }

        $consulta = sqlsrv_query($conn, $sql);
        $res = ['total_efec' => 0, 'total_tarj' => 0];
        
        if ($consulta && $row = sqlsrv_fetch_array($consulta)) {
            $res['total_efec'] = $row['total_efec'];
            $res['total_tarj'] = $row['total_tarj'];
        }
        sqlsrv_close($conn);
        return $res;
    }
    return 0;
}

/* 4. MOVIMIENTOS BANCO */
function getMov_ban($sede, $fecha1, $fecha2, $data)
{
    $database = Database($sede);
    if ($database != null) {
        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if (!$conn) return ['monto_h' => 0];

        $sql = "SELECT SUM(monto_h) as monto_h FROM mov_ban WHERE anulado = 0 AND origen = 'DEP' AND cta_egre='045' AND ";
        
        // --- MEJORA: FIX DE FECHAS ---
        if ($data == 'sin') {
            $sql .= " CONVERT(VARCHAR(8), fecha, 112) = '$fecha1' ";
        } else {
            $sql .= " CONVERT(VARCHAR(8), fecha, 112) BETWEEN '$fecha1' AND '$fecha2' ";
        }

        $consulta = sqlsrv_query($conn, $sql);
        $res = ['monto_h' => 0];
        if ($consulta && $row = sqlsrv_fetch_array($consulta)) {
            $res['monto_h'] = $row['monto_h'];
        }
        sqlsrv_close($conn);
        return $res;
    }
    return 0;
}

/* 5. ORDENES DE PAGO (GASTOS) */
function getOrd_pago($sede, $fecha1, $fecha2, $data)
{
    $database = Database($sede);
    if ($database != null) {
        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if (!$conn) return ['monto' => 0];

        $sql = "SELECT SUM(o.monto) as monto FROM ord_pago o JOIN benefici b ON b.cod_ben = o.cod_ben WHERE o.anulada = 0 AND o.ord_num < 6000000 ";
        
        // --- MEJORA: FIX DE FECHAS ---
        $cond_fecha = "";
        if ($data == 'sin' || $data == 'ven') {
            $cond_fecha = " AND CONVERT(VARCHAR(8), o.fecha, 112) = '$fecha1' ";
        } else {
            $cond_fecha = " AND CONVERT(VARCHAR(8), o.fecha, 112) BETWEEN '$fecha1' AND '$fecha2' ";
        }

        if ($data == 'sin') {
            $sql .= " AND o.cta_egre = '878' $cond_fecha";
        } elseif ($data == 'ven') {
            $sql .= " AND b.ben_des <> 'PREVIA SHOP' AND o.cta_egre <> '878' $cond_fecha";
        } elseif ($data == 'ven2') {
             $sql .= " AND b.ben_des <> 'PREVIA SHOP' AND o.cta_egre <> '878' $cond_fecha";
        } else {
             $sql .= " AND o.cta_egre = '878' $cond_fecha";
        }

        $consulta = sqlsrv_query($conn, $sql);
        $res = ['monto' => 0];
        if ($consulta && $row = sqlsrv_fetch_array($consulta)) {
            $res['monto'] = $row['monto'];
        }
        sqlsrv_close($conn);
        return $res;
    }
    return 0;
}

/* 6. TASAS */
function getTasas($sede, $fecha1)
{
    $database = Database($sede);
    if ($database != null) {
        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if (!$conn) return ['tasa_v' => 1];
        
        // --- MEJORA: FIX DE FECHAS ---
        $sql = "SELECT TOP 1 tasa_v FROM tasas WHERE CONVERT(VARCHAR(8), fecha, 112) <= '$fecha1' ORDER BY fecha DESC";
        
        $consulta = sqlsrv_query($conn, $sql);
        $res = ['tasa_v' => 1];
        if ($consulta && $row = sqlsrv_fetch_array($consulta)) {
            $res['tasa_v'] = $row['tasa_v'];
        }
        sqlsrv_close($conn);
        return $res;
    }
    return 0;
}

/* 7. DETALLE DE FACTURAS Y COBROS (Para el reporte de facturas) */
function getFacturaDetalles($sede, $fecha1, $fecha2)
{
    $database = Database($sede);
    
    if ($database != null) {
        try {
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);
            
            if (!$conn) return [];

            // SQL MEJORADO CON FIX DE FECHA
            $sql = "SELECT 
                        reng_cob.tp_doc_cob, 
                        reng_cob.doc_num as FACTURA, 
                        reng_cob.neto, 
                        cobros.cob_num as COBROS, 
                        cobros.fec_cob, 
                        reng_tip.tip_cob, 
                        reng_tip.mont_doc, 
                        reng_tip.cod_caja, 
                        reng_tip.des_caja 
                    FROM cobros 
                    JOIN reng_tip ON cobros.cob_num = reng_tip.cob_num 
                    JOIN reng_cob ON cobros.cob_num = reng_cob.cob_num 
                    WHERE cobros.anulado=0 
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

        } catch (\Throwable $th) { return []; }
    }
    return [];
}