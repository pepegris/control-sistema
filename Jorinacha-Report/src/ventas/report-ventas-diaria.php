<?php
// Configuración de recursos
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

// INCLUDES NECESARIOS
require "../../includes/log.php";
include '../../includes/header2.php';

// 1. CARGA DE DEPENDENCIAS
include '../../services/mysql.php';           // Contiene $sedes_ar y Database()
include '../../services/db_connection.php';   // Contiene ConectarSQLServer()
include '../../services/adm/ventas/diarias.php'; // Contiene funciones optimizadas (getFactura, etc.)

// VALIDACIÓN
if (!isset($_GET['fecha1'])) {
    header("location: form.php");
    exit;
}

// PARAMETROS
$divisa = $_GET['divisa'];
$fecha_input = $_GET['fecha1'];
$fecha_titulo = date("d/m/Y", strtotime($fecha_input));
$fecha_sql = date("Ymd", strtotime($fecha_input)); // Formato YYYYMMDD para SQL Server
$linea = 'todos';

?>

<style>
    .icon-status { width: 22px; vertical-align: middle; }
    .ok-icon { filter: invert(42%) sepia(93%) saturate(1352%) hue-rotate(87deg) brightness(119%) contrast(119%); } /* Verde */
    
    .text-end { text-align: right !important; }
    .text-center { text-align: center !important; }
    
    .diff-positive { color: #ffc107; font-weight: bold; } /* Amarillo */
    .diff-negative { color: #ff4d4d; font-weight: bold; } /* Rojo */
    .diff-zero { color: #28a745; font-weight: bold; }     /* Verde */
    
    .badge-off { background-color: #dc3545; color: white; padding: 2px 5px; border-radius: 4px; font-size: 0.8em; }
    
    /* Líneas divisorias para facilitar lectura */
    .border-l { border-left: 1px solid #555; }
</style>

<center>
    <h1>Ventas Diarias <?= $fecha_titulo ?></h1>
    <h4>En <?= ($divisa == 'dl') ? "Dolares" : "Bolivares" ?></h4>
</center>

<div class="table-responsive">
    <table class="table table-dark table-striped table-hover table-sm" id="tblData">
        <thead>
            <tr class="text-center">
                <th>Empresa</th>
                <?= ($divisa == 'dl') ? "<th>Tasa</th>" : "" ?>
                <th>Ventas Neta</th>
                <th>Pares</th>
                <th>Devol ($)</th>
                
                <th class="border-l">Depositos</th>
                <th>Efectivo</th>
                <th>Tarjeta</th>
                <th>Divisas/Gastos</th>
                <th>Prov/Varios</th>
                
                <th class="border-l">Diferencia</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // INICIALIZAR ACUMULADORES TOTALES
        $g_ventas = $g_pares = $g_devol = 0;
        $g_banco = $g_efectivo = $g_tarjeta = $g_gastos = $g_otros = 0;
        $g_diferencia = 0;

        // BUCLE: Comienza en 1 para saltar "Previa Shop"
        for ($i = 1; $i < count($sedes_ar); $i++) {
            $nombre_sede = $sedes_ar[$i];
            
            // ---------------------------------------------------------
            // 1. CONEXIÓN OPTIMIZADA
            // ---------------------------------------------------------
            $nombre_bd = Database($nombre_sede); // Obtener nombre BD
            $conn = ConectarSQLServer($nombre_bd); // Abrir conexión

            // Valores por defecto
            $tasa = 1;
            $venta_neta = $pares = $monto_devol = 0;
            $banco = $efectivo = $tarjeta = $gastos = $otros_pagos = 0;
            $conectado = false;

            if ($conn) {
                $conectado = true;

                // 2. OBTENER TASA
                if ($divisa == 'dl') {
                    $arr_tasa = getTasas($conn, $fecha_sql);
                    $tasa = ($arr_tasa['tasa_v'] > 0) ? $arr_tasa['tasa_v'] : 1;
                }

                // 3. CONSULTAS (Pasando $conn a funciones de diarias.php)
                $fac_neto = getFactura($conn, $fecha_sql, null, 'sin', $linea);
                $fac_art  = getFactura($conn, $fecha_sql, null, 'ven', $linea);
                $dev_neto = getDev_cli($conn, $fecha_sql, null, 'sin', $linea);
                $dev_art  = getDev_cli($conn, $fecha_sql, null, 'ven', $linea);

                $dat_caja  = getDep_caj($conn, $fecha_sql, null, 'sin');
                $dat_banco = getMov_ban($conn, $fecha_sql, null, 'sin');
                $ord_gastos = getOrd_pago($conn, $fecha_sql, null, 'sin'); // Gastos 878
                $ord_provee = getOrd_pago($conn, $fecha_sql, null, 'ven'); // Proveedores

                // 4. CERRAR CONEXIÓN (Liberar recursos)
                sqlsrv_close($conn);

                // 5. CÁLCULOS (Aplicando Tasa)
                $venta_neta  = ($fac_neto['tot_neto'] - $dev_neto['tot_neto']) / $tasa;
                $pares       = $fac_art['total_art'] - $dev_art['total_art'];
                $monto_devol = $dev_neto['tot_neto'] / $tasa;

                $banco       = $dat_banco['monto_h'] / $tasa;
                $efectivo    = $dat_caja['total_efec'] / $tasa;
                $tarjeta     = $dat_caja['total_tarj'] / $tasa;
                $gastos      = $ord_gastos['monto'] / $tasa;
                $otros_pagos = $ord_provee['monto'] / $tasa;
            }

            // 6. LÓGICA DEL CUADRE
            // Sumamos lo que entró: Efectivo + Tarjeta + Vales de Gastos + Vales Prov.
            // NOTA: Si tus depósitos SUMAN al cuadre (ej. transferencias directas), descomenta la variable $banco.
            // Si el banco son solo depósitos del efectivo ya contado, NO lo sumes.
            
            $dinero_registrado = $efectivo + $tarjeta + $gastos + $otros_pagos; 
            // Si quieres sumar banco también: $dinero_registrado += $banco;

            $diferencia = $dinero_registrado - $venta_neta;

            // 7. ACUMULAR TOTALES
            $g_ventas += $venta_neta;
            $g_pares += $pares;
            $g_devol += $monto_devol;
            $g_banco += $banco;
            $g_efectivo += $efectivo;
            $g_tarjeta += $tarjeta;
            $g_gastos += $gastos;
            $g_otros += $otros_pagos;
            $g_diferencia += $diferencia;
            ?>

            <tr>
                <td>
                    <?= $nombre_sede ?> 
                    <?php if(!$conectado) echo "<span class='badge-off'>OFF</span>"; ?>
                </td>

                <?php if ($divisa == 'dl'): ?>
                    <td class="text-end"><?= number_format($tasa, 2, ',', '.') ?></td>
                <?php endif; ?>

                <td class="text-end"><?= number_format($venta_neta, 2, ',', '.') ?></td>
                <td class="text-end"><?= number_format($pares, 0, ',', '.') ?></td>
                <td class="text-end text-danger"><?= ($monto_devol > 0) ? number_format($monto_devol, 2, ',', '.') : '-' ?></td>

                <td class="text-end border-l"><?= ($banco > 0) ? number_format($banco, 2, ',', '.') : '-' ?></td>
                <td class="text-end"><?= ($efectivo > 0) ? number_format($efectivo, 2, ',', '.') : '-' ?></td>
                <td class="text-end"><?= ($tarjeta > 0) ? number_format($tarjeta, 2, ',', '.') : '-' ?></td>
                <td class="text-end"><?= ($gastos > 0) ? number_format($gastos, 2, ',', '.') : '-' ?></td>
                <td class="text-end"><?= ($otros_pagos > 0) ? number_format($otros_pagos, 2, ',', '.') : '-' ?></td>

                <td class="text-center border-l">
                    <?php 
                    if (!$conectado || ($venta_neta == 0 && $dinero_registrado == 0)) {
                        echo "-";
                    } elseif (abs($diferencia) < 1) { 
                        // Diferencia menor a 1 se considera cuadre perfecto
                        echo "<img src='./img/checkmark-circle.svg' class='icon-status ok-icon' title='Cuadre Perfecto'>"; 
                    } else {
                        // Mostramos la diferencia
                        $clase = ($diferencia < 0) ? 'diff-negative' : 'diff-positive';
                        echo "<span class='$clase'>" . number_format($diferencia, 2, ',', '.') . "</span>";
                        
                        // Alerta visual si falta más de 5
                        if ($diferencia < -5) echo " <img src='./img/help.svg' class='icon-status'>";
                    }
                    ?>
                </td>
            </tr>
        <?php } // FIN FOR ?>

            <tr class="table-secondary" style="font-weight: bold; border-top: 2px solid white;">
                <td class="text-end" colspan="<?= ($divisa=='dl') ? 2 : 1 ?>">TOTALES:</td>
                
                <td class="text-end"><?= number_format($g_ventas, 2, ',', '.') ?></td>
                <td class="text-end"><?= number_format($g_pares, 0, ',', '.') ?></td>
                <td class="text-end"><?= number_format($g_devol, 2, ',', '.') ?></td>
                
                <td class="text-end border-l"><?= number_format($g_banco, 2, ',', '.') ?></td>
                <td class="text-end"><?= number_format($g_efectivo, 2, ',', '.') ?></td>
                <td class="text-end"><?= number_format($g_tarjeta, 2, ',', '.') ?></td>
                <td class="text-end"><?= number_format($g_gastos, 2, ',', '.') ?></td>
                <td class="text-end"><?= number_format($g_otros, 2, ',', '.') ?></td>
                
                <td class="text-center border-l">
                    <span class="<?= ($g_diferencia < 0) ? 'diff-negative' : 'diff-positive' ?>">
                        <?= number_format($g_diferencia, 2, ',', '.') ?>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>