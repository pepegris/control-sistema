<?php
// services/adm/ordenes-compra/ordenes-compra.php

require_once "../../services/empresas.php"; 

// Variable global para almacenar el estado de la conexión
$tipo_conexion_actual = "Desconocido";

// =============================================================================
// HELPER: GESTIÓN INTELIGENTE DE CONEXIÓN DESTINO (VPN -> LOCAL)
// =============================================================================
function ConectarDestino($sede) {
    global $lista_replicas, $tipo_conexion_actual;

    // 1. Validar que la sede exista en el array
    if (!isset($lista_replicas[$sede])) {
        $tipo_conexion_actual = "Error: Sede no existe";
        return false;
    }

    $config = $lista_replicas[$sede];
    
    // --- INTENTO 1: CONEXIÓN REMOTA (VPN) ---
    $serverRemoto = $config['ip'];
    $dbRemota = $config['db'];
    
    $connectionInfoRemoto = array(
        "Database" => $dbRemota, 
        "UID" => "mezcla", 
        "PWD" => "Zeus33$", 
        "CharacterSet" => "UTF-8",
        "LoginTimeout" => 3 // 3 segundos de espera máximo para VPN
    );

    // Usamos @ para que no muestre error visual si falla
    $conn = @sqlsrv_connect($serverRemoto, $connectionInfoRemoto);

    if ($conn) {
        $tipo_conexion_actual = "🌍 VPN (Remoto)";
        return $conn; // Éxito Remoto
    }

    // --- INTENTO 2: CONEXIÓN LOCAL (FALLBACK 172.16.1.39) ---
    // Si falló la VPN, usamos el servidor central pero apuntando a la DB de la tienda
    $serverLocal = "172.16.1.39";
    $dbLocal = $config['db_local'];

    $connectionInfoLocal = array(
        "Database" => $dbLocal, 
        "UID" => "mezcla", 
        "PWD" => "Zeus33$", 
        "CharacterSet" => "UTF-8",
        "LoginTimeout" => 10
    );

    $connLocal = sqlsrv_connect($serverLocal, $connectionInfoLocal);
    
    if ($connLocal) {
        $tipo_conexion_actual = "🏢 LOCAL (Réplica)";
        return $connLocal; // Éxito Local
    }

    $tipo_conexion_actual = "❌ FALLÓ CONEXIÓN";
    return false; // Fallaron ambos
}

// =============================================================================
// 1. LECTURA DE FACTURAS (FUENTE: CENTRAL PREVIA_A)
// =============================================================================
function Factura_Ordenes($sede, $fecha, $campo7) {
    // Siempre lee de la central
    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    
    // Usamos la función Cliente() de empresas.php
    $cliente = Cliente($sede); 

    if ($conn) {
        try {
            $clientes_internos = ['S14','S13','S12','S11','S10','S09','S08','S07','S06','S05','S04','S03','S02','S01'];
            
            if (in_array($cliente, $clientes_internos)) {
                $sql = "SELECT fact_num, contrib,
                        CONVERT(numeric(10,2), saldo) AS saldo,
                        CONVERT(numeric(10,2), tot_bruto) AS tot_bruto,
                        CONVERT(numeric(10,2), tot_neto) AS tot_neto,
                        CONVERT(numeric(10,2), iva) AS iva
                        FROM not_ent 
                        WHERE co_cli='$cliente' AND FEC_EMIS='$fecha' AND campo7 <> '$campo7' AND anulada=0";
            } else {
                $sql = "SELECT fact_num, contrib,
                        CONVERT(numeric(10,2), saldo) AS saldo,
                        CONVERT(numeric(10,2), tot_bruto) AS tot_bruto,
                        CONVERT(numeric(10,2), tot_neto) AS tot_neto,
                        CONVERT(numeric(10,2), iva) AS iva
                        FROM factura 
                        WHERE co_cli='$cliente' AND FEC_EMIS='$fecha' AND campo7 <> '$campo7' AND anulada=0";
            }

            $consulta = sqlsrv_query($conn, $sql);
            $ordenes_facturas = [];

            if ($consulta) {
                while ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
                    $ordenes_facturas[] = $row;
                }
            }
            return $ordenes_facturas;

        } catch (Exception $e) {
            return [];
        }
    }
    return [];
}

// =============================================================================
// 2. LECTURA DE RENGLONES (FUENTE: CENTRAL PREVIA_A)
// =============================================================================
function Reng_Factura($sede, $fecha, $fact_num) {
    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    $cliente = Cliente($sede);

    if ($conn) {
        try {
            $clientes_internos = ['S14','S13','S12','S11','S10','S09','S08','S07','S06','S05','S04','S03','S02','S01'];

            if (in_array($cliente, $clientes_internos)) {
                $sql = "SELECT not_ent.fact_num, reng_num, reng_nde.co_art, 
                        CONVERT(numeric(10,0), reng_nde.total_art) AS total_art, 
                        CONVERT(numeric(10,2), reng_nde.prec_vta) AS prec_vta,
                        CONVERT(numeric(10,2), reng_nde.reng_neto) AS reng_neto,
                        CONVERT(numeric(10,2), art.cos_pro_un) AS cos_pro_un, 
                        CONVERT(numeric(10,2), art.ult_cos_un) AS ult_cos_un, 
                        CONVERT(numeric(10,2), art.ult_cos_om) AS ult_cos_om,
                        CONVERT(numeric(10,2), art.cos_pro_om) AS cos_pro_om
                        FROM reng_nde
                        INNER JOIN not_ent ON reng_nde.fact_num = not_ent.fact_num
                        INNER JOIN art ON art.co_art = reng_nde.co_art
                        WHERE co_cli='$cliente' AND FEC_EMIS='$fecha' AND not_ent.fact_num='$fact_num' AND anulada=0";
            } else {
                $sql = "SELECT factura.fact_num, reng_num, reng_fac.co_art, 
                        CONVERT(numeric(10,0), reng_fac.total_art) AS total_art, 
                        CONVERT(numeric(10,2), reng_fac.prec_vta) AS prec_vta,
                        CONVERT(numeric(10,2), reng_fac.reng_neto) AS reng_neto,
                        CONVERT(numeric(10,2), art.cos_pro_un) AS cos_pro_un, 
                        CONVERT(numeric(10,2), art.ult_cos_un) AS ult_cos_un, 
                        CONVERT(numeric(10,2), art.ult_cos_om) AS ult_cos_om,
                        CONVERT(numeric(10,2), art.cos_pro_om) AS cos_pro_om
                        FROM reng_fac
                        INNER JOIN factura ON reng_fac.fact_num = factura.fact_num
                        INNER JOIN art ON art.co_art = reng_fac.co_art
                        WHERE co_cli='$cliente' AND FEC_EMIS='$fecha' AND factura.fact_num='$fact_num' AND anulada=0";
            }

            $consulta = sqlsrv_query($conn, $sql);
            $Reng_Factura = [];

            if ($consulta) {
                while ($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
                    $Reng_Factura[] = $row;
                }
            }
            return $Reng_Factura;

        } catch (Exception $e) { return []; }
    }
    return [];
}

// =============================================================================
// 3. INSERTAR CABECERA (DESTINO: VPN O LOCAL)
// =============================================================================
function Ordenes_Compra($sede, $fact_num, $contrib, $saldo, $tot_bruto, $tot_neto, $iva) {
    // Usamos la nueva función inteligente
    $conn = ConectarDestino($sede);

    if ($conn) {
        try {
            $dif = ($tot_bruto > 0) ? $tot_bruto / 16 : 0;
            $moneda = ($sede == 'CAGUA') ? 'BOD' : 'BSD'; 

            $sql = "INSERT INTO ordenes (fact_num, contrib, status, comentario, descrip,
                    co_sucu, forma_pag, moneda, co_cli, co_ven, co_tran,
                    saldo, tot_bruto, tot_neto, iva,
                    tasag, tasag10, co_us_in, co_us_mo, dis_cen)
                    VALUES ('10$fact_num', $contrib, 0, '<Orden de Compra Importada>', 'Factura $fact_num',
                    1, 'CRED', '$moneda', '002', 127, 1,
                    $saldo, $tot_bruto, $tot_neto, $iva,
                    16, 12, '001', '001', '<IVA> <1>16/$tot_bruto/$dif</1> </IVA>')";

            $consulta = sqlsrv_query($conn, $sql);
            return ($consulta) ? true : false;

        } catch (Exception $e) { return false; }
    }
    return false;
}

// =============================================================================
// 4. INSERTAR RENGLÓN (DESTINO: VPN O LOCAL)
// =============================================================================
function Reng_Ordenes($sede, $fact_num, $reng_num, $co_art, $total_art, $prec_vta, $reng_neto, $cos_pro_un, $ult_cos_un, $ult_cos_om, $cos_pro_om) {
    
    $conn = ConectarDestino($sede);

    if ($conn) {
        try {
            $sql = "INSERT INTO reng_ord (fact_num, reng_num, comentario,
                    uni_venta, total_uni, co_alma, tipo_imp,
                    co_art, total_art, pendiente, prec_vta, prec_vta2, reng_neto,
                    cos_pro_un, ult_cos_un, ult_cos_om, cos_pro_om)
                    VALUES ('10$fact_num', $reng_num, '<Orden de Compra Importada>',
                    'PAR', 1, 1, 1,
                    '$co_art', $total_art, $total_art, $prec_vta, $prec_vta, $reng_neto,
                    $cos_pro_un, $ult_cos_un, $ult_cos_om, $cos_pro_om)";

            $consulta = sqlsrv_query($conn, $sql);
            return ($consulta) ? true : false;

        } catch (Exception $e) { return false; }
    }
    return false;
}

// =============================================================================
// 5. VERIFICAR EXISTENCIA (DESTINO: VPN O LOCAL)
// =============================================================================
function Con_Reng_Ordenes($sede, $fact_num, $reng_num) {
    $conn = ConectarDestino($sede);
    
    if ($conn) {
        try {
            $sql = "SELECT fact_num, reng_num FROM reng_ord WHERE fact_num='10$fact_num' AND reng_num='$reng_num'";
            $consulta = sqlsrv_query($conn, $sql);
            
            if ($consulta && sqlsrv_has_rows($consulta)) {
                return true; // Existe
            }
            return null; // No existe
        } catch (Exception $e) { return null; }
    }
    return null;
}

// =============================================================================
// 6. ACTUALIZAR STATUS (FUENTE: CENTRAL PREVIA_A)
// =============================================================================
function Up_Factura_Ordenes($sede, $fecha, $fact_num, $status1, $status2) {
    // Esto se actualiza en la central
    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    
    $cliente = Cliente($sede);

    if ($conn) {
        try {
            $clientes_internos = ['S14','S13','S12','S11','S10','S09','S08','S07','S06','S05','S04','S03','S02','S01'];
            
            if (in_array($cliente, $clientes_internos)) {
                $sql = "UPDATE not_ent SET campo7='IMPORTADO' WHERE co_cli='$cliente' AND FEC_EMIS='$fecha' AND fact_num='$fact_num' AND anulada=0";
                $documento = "Nota de Entrega $fact_num";        
            } else {
                $sql = "UPDATE factura SET campo7='IMPORTADO' WHERE co_cli='$cliente' AND FEC_EMIS='$fecha' AND fact_num='$fact_num' AND anulada=0";
                $documento = "Factura $fact_num"; 
            }

            if ($status1 && $status2) {
                $consulta = sqlsrv_query($conn, $sql);
                if ($consulta) {
                    return "Fue importada con Éxito la $documento a la Tienda $sede";
                }
            }
            return "No se Pudo Importar la $documento";

        } catch (Exception $e) { return "Error al actualizar status"; }
    }
    return "Error de conexión Central";
}
?>