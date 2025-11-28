<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php'; // Contiene $sedes_ar y require empresas.php
include '../../services/adm/ventas/diarias.php'; // Funciones "Old Way" con fix de fecha

if (!isset($_GET['fecha1'])) { header("location: form.php"); exit; }

$divisa = $_GET['divisa'];
$fecha_input = $_GET['fecha1'];
$fecha_titulo = date("d/m/Y", strtotime($fecha_input));
$fecha_sql = date("Ymd", strtotime($fecha_input));
$linea = 'todos';
?>

<style>
    .text-end { text-align: right; }
    .text-center { text-align: center; }
    .diff-pos { color: #ffc107; font-weight: bold; }
    .diff-neg { color: #dc3545; font-weight: bold; }
    .ok-icon { filter: invert(42%) sepia(93%) saturate(1352%) hue-rotate(87deg) brightness(119%) contrast(119%); width: 20px; }
</style>

<center>
    <h1>Ventas Diarias <?= $fecha_titulo ?></h1>
    <h4>En <?= ($divisa == 'dl') ? "Dolares" : "Bolivares" ?></h4>
</center>

<table class="table table-dark table-striped table-sm" id="tblData">
    <thead>
        <tr class="text-center">
            <th>Empresa</th>
            <?= ($divisa == 'dl') ? "<th>Tasa</th>" : "" ?>
            <th>Ventas Neta</th>
            <th>Pares</th>
            <th>Devol ($)</th>
            <th style="border-left: 1px solid #555;">Depositos</th>
            <th>Efectivo</th>
            <th>Tarjeta</th>
            <th>Divisas</th>
            <th>Prov/Varios</th>
            <th style="border-left: 1px solid #555;">Diferencia</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $tot_ventas = $tot_pares = $tot_devol = 0;
    $tot_depositos = $tot_efectivo = $tot_tarjeta = $tot_gastos = $tot_otros = 0;
    $tot_dif = 0;

    // BUCLE EMPIEZA EN 1 (Salta Previa Shop)
    for ($i = 1; $i < count($sedes_ar); $i++) {
        $sede = $sedes_ar[$i]; // Pasamos el NOMBRE (String), no la conexión

        // 1. OBTENER TASA
        $tasa = 1;
        if ($divisa == 'dl') {
            $arr_tasa = getTasas($sede, $fecha_sql);
            $tasa = ($arr_tasa['tasa_v'] > 0) ? $arr_tasa['tasa_v'] : 1;
        }

        // 2. OBTENER DATOS (Usando funciones originales corregidas)
        $fac     = getFactura($sede, $fecha_sql, null, 'sin', $linea);
        $fac_art = getFactura($sede, $fecha_sql, null, 'ven', $linea);
        $dev     = getDev_cli($sede, $fecha_sql, null, 'sin', $linea);
        $dev_art = getDev_cli($sede, $fecha_sql, null, 'ven', $linea);

        $caja    = getDep_caj($sede, $fecha_sql, null, 'sin');
        $banco   = getMov_ban($sede, $fecha_sql, null, 'sin');
        $o_gasto = getOrd_pago($sede, $fecha_sql, null, 'sin');
        $o_prov  = getOrd_pago($sede, $fecha_sql, null, 'ven');

        // 3. CALCULOS
        $venta_neta  = ($fac['tot_neto'] - $dev['tot_neto']) / $tasa;
        $pares       = $fac_art['total_art'] - $dev_art['total_art'];
        $monto_devol = $dev['tot_neto'] / $tasa;

        $depositos   = $banco['monto_h'] / $tasa;
        $efectivo    = $caja['total_efec'] / $tasa;
        $tarjeta     = $caja['total_tarj'] / $tasa;
        $gastos      = $o_gasto['monto'] / $tasa;
        $otros       = $o_prov['monto'] / $tasa;

        // 4. CUADRE (Matemático)
        $dinero = $depositos + $efectivo + $tarjeta + $gastos + $otros;
        $diferencia = $dinero - $venta_neta;

        // ACUMULADOS
        $tot_ventas += $venta_neta;
        $tot_pares += $pares;
        $tot_devol += $monto_devol;
        $tot_depositos += $depositos;
        $tot_efectivo += $efectivo;
        $tot_tarjeta += $tarjeta;
        $tot_gastos += $gastos;
        $tot_otros += $otros;
        $tot_dif += $diferencia;
        ?>
        
        <tr>
            <td><?= $sede ?></td>
            
            <?php if ($divisa == 'dl'): ?>
                <td class="text-end"><?= number_format($tasa, 2, ',', '.') ?></td>
            <?php endif; ?>

            <td class="text-end"><?= number_format($venta_neta, 2, ',', '.') ?></td>
            <td class="text-end"><?= number_format($pares, 0, ',', '.') ?></td>
            <td class="text-end"><?= number_format($monto_devol, 2, ',', '.') ?></td>

            <td class="text-end" style="border-left: 1px solid #555;"><?= number_format($depositos, 2, ',', '.') ?></td>
            <td class="text-end"><?= number_format($efectivo, 2, ',', '.') ?></td>
            <td class="text-end"><?= number_format($tarjeta, 2, ',', '.') ?></td>
            <td class="text-end"><?= number_format($gastos, 2, ',', '.') ?></td>
            <td class="text-end"><?= number_format($otros, 2, ',', '.') ?></td>

            <td class="text-center" style="border-left: 1px solid #555;">
                <?php 
                if (abs($diferencia) < 1) { 
                     echo "<img src='./img/checkmark-circle.svg' class='ok-icon'>"; 
                } else {
                     $clase = ($diferencia < 0) ? 'diff-neg' : 'diff-pos';
                     echo "<span class='$clase'>" . number_format($diferencia, 2, ',', '.') . "</span>";
                     if ($diferencia < -5) echo " <img src='./img/help.svg' width='16'>";
                }
                ?>
            </td>
        </tr>
    <?php } // Fin for ?>

    <tr class="table-secondary" style="border-top: 2px solid white; font-weight:bold;">
        <td colspan="<?= ($divisa=='dl') ? 2 : 1 ?>" class="text-end">TOTALES:</td>
        <td class="text-end"><?= number_format($tot_ventas, 2, ',', '.') ?></td>
        <td class="text-end"><?= number_format($tot_pares, 0, ',', '.') ?></td>
        <td class="text-end"><?= number_format($tot_devol, 2, ',', '.') ?></td>
        <td class="text-end" style="border-left: 1px solid #555;"><?= number_format($tot_depositos, 2, ',', '.') ?></td>
        <td class="text-end"><?= number_format($tot_efectivo, 2, ',', '.') ?></td>
        <td class="text-end"><?= number_format($tot_tarjeta, 2, ',', '.') ?></td>
        <td class="text-end"><?= number_format($tot_gastos, 2, ',', '.') ?></td>
        <td class="text-end"><?= number_format($tot_otros, 2, ',', '.') ?></td>
        <td class="text-center" style="border-left: 1px solid #555;"><?= number_format($tot_dif, 2, ',', '.') ?></td>
    </tr>
    </tbody>
</table>

<?php include '../../includes/footer.php'; ?>