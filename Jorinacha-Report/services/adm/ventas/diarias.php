<?php
// ../../services/adm/ventas/diarias.php

require_once "../../services/empresas.php"; // Aseguramos cargar esto

/* CONSULTAR ARTICULOS VENDIDOS / FACTURAS */
function getFactura($sede, $fecha1, $fecha2, $data, $linea)
{
    // 1. OBTENEMOS EL NOMBRE DE LA BD
    $database = Database($sede);
    
    if ($database != null) {
        try {
            // 2. CONECTAMOS (Estructura Vieja Segura)
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);
            
            if (!$conn) return ['total_art' => 0, 'tot_neto' => 0];

            // 3. ARMAMOS EL SQL CON EL ARREGLO DE FECHA (CONVERT)
            $select = ($data == 'ven' || $data == 'ven2') ? "SUM(rf.total_art) as total" : "SUM(f.tot_neto) as total";
            $sql = "SELECT $select FROM factura f ";
            
            if ($linea != 'todos' || $data == 'ven' || $data == 'ven2') {
                $sql .= " JOIN reng_fac rf ON f.fact_num = rf.fact_num ";
                if ($linea != 'todos') $sql .= " JOIN art a ON a.co_art = rf.co_art ";
            }

            $sql .= " WHERE f.anulada = 0 ";
            
            // --- AQUI ESTA EL FIX PARA QUE SALGAN DATOS ---
            if ($data == 'ven' || $data == 'sin') {
                $sql .= " AND CONVERT(VARCHAR(8), f.fec_emis, 112) = '$fecha1' ";
            } else {
                $sql .= " AND CONVERT(VARCHAR(8), f.fec_emis, 112) BETWEEN '$fecha1' AND '$fecha2' ";
            }
            // ----------------------------------------------

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
            
            sqlsrv_close($conn); // Cerramos conexión
            return $res;

        } catch (\Throwable $th) {
            return ['total_art' => 0, 'tot_neto' => 0];
        }
    } else {
        return 0;
    }
}

/* DEVOLUCIONES */
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

            // FIX DE FECHA
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
        } catch (\Throwable $th) { throw $th; }
    } else { return 0; }
}

/* DEPOSITOS CAJA */
function getDep_caj($sede, $fecha1, $fecha2, $data)
{
    $database = Database($sede);
    if ($database != null) {
        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if (!$conn) return ['total_efec' => 0, 'total_tarj' => 0];

        $sql = "SELECT SUM(total_efec) as total_efec, SUM(total_tarj) as total_tarj FROM dep_caj WHERE ";
        
        // FIX DE FECHA
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

/* MOVIMIENTOS BANCO */
function getMov_ban($sede, $fecha1, $fecha2, $data)
{
    $database = Database($sede);
    if ($database != null) {
        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if (!$conn) return ['monto_h' => 0];

        $sql = "SELECT SUM(monto_h) as monto_h FROM mov_ban WHERE anulado = 0 AND origen = 'DEP' AND cta_egre='045' AND ";
        
        // FIX DE FECHA
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

/* ORDENES DE PAGO */
function getOrd_pago($sede, $fecha1, $fecha2, $data)
{
    $database = Database($sede);
    if ($database != null) {
        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if (!$conn) return ['monto' => 0];

        $sql = "SELECT SUM(o.monto) as monto FROM ord_pago o JOIN benefici b ON b.cod_ben = o.cod_ben WHERE o.anulada = 0 AND o.ord_num < 6000000 ";
        
        // FIX DE FECHA Y FILTROS
        if ($data == 'sin') {
            $sql .= " AND o.cta_egre = '878' AND CONVERT(VARCHAR(8), o.fecha, 112) = '$fecha1' ";
        } elseif ($data == 'ven') {
            $sql .= " AND b.ben_des <> 'PREVIA SHOP' AND o.cta_egre <> '878' AND CONVERT(VARCHAR(8), o.fecha, 112) = '$fecha1' ";
        } elseif ($data == 'ven2') {
             $sql .= " AND b.ben_des <> 'PREVIA SHOP' AND o.cta_egre <> '878' AND CONVERT(VARCHAR(8), o.fecha, 112) BETWEEN '$fecha1' AND '$fecha2' ";
        } else {
             $sql .= " AND o.cta_egre = '878' AND CONVERT(VARCHAR(8), o.fecha, 112) BETWEEN '$fecha1' AND '$fecha2' ";
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

/* TASAS */
function getTasas($sede, $fecha1)
{
    $database = Database($sede);
    if ($database != null) {
        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        
        // FIX DE FECHA
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
?>