<?php
// report-ventas-diaria.php
// Quita los ini_set de memoria, ya no deberían ser necesarios con este código optimizado
require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php'; // Solo para traer las empresas ($sedes_ar)
include '../../services/db_connection.php'; // NUEVA CONEXION
include '../../services/adm/ventas/diarias.php'; // FUNCIONES OPTIMIZADAS


// TEMPORAL: PON ESTO ANTES DE if (!$_GET)
$test_conn = ConectarSQLServer("NombreDeUnaBDQueSabesQueExiste"); 
if (!$test_conn) {
    echo "¡ERROR DE CONEXIÓN GLOBAL! Revisa db_connection.php y las credenciales.";
    die( print_r( sqlsrv_errors(), true)); 
}
sqlsrv_close($test_conn);
// FIN TEMPORAL
if (!$_GET) { header("location: form.php"); exit; }

$divisa = $_GET['divisa'];
$fecha_input = $_GET['fecha1'];
$fecha_titulo = date("d/m/Y", strtotime($fecha_input));
$fecha_sql = date("Ymd", strtotime($fecha_input)); // Formato para SQL Server
$linea = 'todos';

?>
<style>
    .ok-icon { width: 20px; filter: invert(42%) sepia(93%) saturate(1352%) hue-rotate(87deg) brightness(119%) contrast(119%); } /* Verde */
    .err-icon { width: 20px; filter: invert(16%) sepia(88%) saturate(6054%) hue-rotate(356deg) brightness(97%) contrast(113%); } /* Rojo */
    .warn-text { color: #ffc107; font-weight: bold; }
    .err-text { color: #dc3545; font-weight: bold; }
    .ok-text { color: #28a745; font-weight: bold; }
</style>

<center><h1>Ventas Diarias <?= $fecha_titulo ?></h1></center>
<h4>En <?= ($divisa == 'dl') ? "Dolares" : "Bolivares" ?></h4>

<table class="table table-dark table-striped table-sm" id="tblData"> <thead>
        <tr>
            <th>Empresa</th>
            <?= ($divisa == 'dl') ? "<th>Tasa</th>" : "" ?>
            <th>Ventas Neta</th>
            <th>Pares</th>
            <th>Devol ($)</th>
            <th>Depósitos</th>
            <th>Efectivo</th>
            <th>Tarjeta</th>
            <th>Divisas (Gastos)</th>
            <th>Prov/Varios</th>
            <th>Cuadre (Diferencia)</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // Inicializar totales globales
    $tot_ventas = $tot_pares = $tot_devol = $tot_depositos = $tot_efectivo = $tot_tarjeta = $tot_gastos = $tot_otros = $tot_dif = 0;

    for ($i = 1; $i < count($sedes_ar); $i++) {
        $nombre_sede = $sedes_ar[$i];
        
        // 1. OBTENER BASE DE DATOS Y CONECTAR
        $db_name = Database($nombre_sede);
        $conn = ConectarSQLServer($db_name);

        // Variables por defecto si falla conexión
        $tasa = 1; 
        $venta_neta = $pares_netos = $monto_devol = 0;
        $depositos = $efectivo = $tarjeta = $gastos_divisas = $pagos_otros = 0;
        $conectado = false;

        if ($conn) {
            $conectado = true;
            // 2. OBTENER TASA
            if ($divisa == 'dl') {
                $arr_tasa = getTasas($conn, $fecha_sql);
                $tasa = ($arr_tasa['tasa_v'] > 0) ? $arr_tasa['tasa_v'] : 1;
            }

            // 3. OBTENER DATOS (Pasamos $conn)
            $fac = getFactura($conn, $fecha_sql, null, 'sin', $linea);
            $fac_art = getFactura($conn, $fecha_sql, null, 'ven', $linea);
            
            $dev = getDev_cli($conn, $fecha_sql, null, 'sin', $linea);
            $dev_art = getDev_cli($conn, $fecha_sql, null, 'ven', $linea);

            $caja = getDep_caj($conn, $fecha_sql);
            $banco = getMov_ban($conn, $fecha_sql);
            
            $ordenes_div = getOrd_pago($conn, $fecha_sql, 'sin'); // Divisas/Gastos
            $ordenes_prov = getOrd_pago($conn, $fecha_sql, 'ven'); // Proveedores

            // 4. CALCULOS MATEMATICOS
            // Ventas
            $venta_neta_local = $fac['tot_neto'] - $dev['tot_neto'];
            $venta_neta = $venta_neta_local / $tasa;
            $pares_netos = $fac_art['total_art'] - $dev_art['total_art'];
            $monto_devol = $dev['tot_neto'] / $tasa;

            // Dinero
            $depositos = $banco['monto_h'] / $tasa;
            $efectivo = $caja['total_efec'] / $tasa;
            $tarjeta = $caja['total_tarj'] / $tasa;
            $gastos_divisas = $ordenes_div['monto'] / $tasa;
            $pagos_otros = $ordenes_prov['monto'] / $tasa;

            // CERRAR CONEXION INMEDIATAMENTE
            sqlsrv_close($conn);
        }

        // 5. LOGICA DEL CUADRE
        // El dinero que tenemos vs Lo que se vendió
        // Nota: A veces los depósitos bancarios YA incluyen el efectivo/tarjeta. 
        // Si tu sistema duplica, comenta $efectivo o $depositos según tu flujo.
        // Aquí asumo: DepositoBanco + EfectivoCaja + TarjetaCaja + Vales
        
        $dinero_reportado = $depositos + $efectivo + $tarjeta + $gastos_divisas + $pagos_otros;
        $diferencia = $dinero_reportado - $venta_neta;

        // Acumular Totales
        $tot_ventas += $venta_neta;
        $tot_pares += $pares_netos;
        $tot_devol += $monto_devol;
        $tot_depositos += $depositos;
        $tot_efectivo += $efectivo;
        $tot_tarjeta += $tarjeta;
        $tot_gastos += $gastos_divisas;
        $tot_otros += $pagos_otros;
        $tot_dif += $diferencia;

        // Renderizar Fila
        ?>
        <tr>
            <td><?= $nombre_sede ?> <?php if(!$conectado) echo "<span class='badge bg-danger'>OFF</span>"; ?></td>
            
            <?php if ($divisa == 'dl'): ?>
                <td><?= number_format($tasa, 2, ',', '.') ?></td>
            <?php endif; ?>

            <td><?= number_format($venta_neta, 2, ',', '.') ?></td>
            <td><?= number_format($pares_netos, 0, ',', '.') ?></td>
            <td><?= number_format($monto_devol, 2, ',', '.') ?></td>
            
            <td><?= number_format($depositos, 2, ',', '.') ?></td>
            <td><?= number_format($efectivo, 2, ',', '.') ?></td>
            <td><?= number_format($tarjeta, 2, ',', '.') ?></td>
            <td><?= number_format($gastos_divisas, 2, ',', '.') ?></td>
            <td><?= number_format($pagos_otros, 2, ',', '.') ?></td>

            <td>
                <?php 
                if (!$conectado || $venta_neta == 0) {
                    echo "-";
                } elseif (abs($diferencia) < 1) { 
                    // Si la diferencia es menor a 1 (por redondeo), es perfecto
                    echo "<img src='./img/checkmark-circle.svg' class='ok-icon'>"; 
                } else {
                    // Hay diferencia real
                    $clase = ($diferencia < 0) ? 'err-text' : 'warn-text'; // Rojo si falta, Amarillo si sobra
                    echo "<span class='$clase'>" . number_format($diferencia, 2, ',', '.') . "</span>";
                    if ($diferencia < -5) echo " <img src='./img/help.svg' width='16'>";
                }
                ?>
            </td>
        </tr>
    <?php } // Fin del ciclo for ?>

    <tr class="table-secondary">
        <td colspan="<?= ($divisa=='dl') ? 2 : 1 ?>"><b>TOTALES</b></td>
        <td><b><?= number_format($tot_ventas, 2, ',', '.') ?></b></td>
        <td><b><?= number_format($tot_pares, 0, ',', '.') ?></b></td>
        <td><b><?= number_format($tot_devol, 2, ',', '.') ?></b></td>
        <td><b><?= number_format($tot_depositos, 2, ',', '.') ?></b></td>
        <td><b><?= number_format($tot_efectivo, 2, ',', '.') ?></b></td>
        <td><b><?= number_format($tot_tarjeta, 2, ',', '.') ?></b></td>
        <td><b><?= number_format($tot_gastos, 2, ',', '.') ?></b></td>
        <td><b><?= number_format($tot_otros, 2, ',', '.') ?></b></td>
        <td><b><?= number_format($tot_dif, 2, ',', '.') ?></b></td>
    </tr>
    </tbody>
</table>

<?php include '../../includes/footer.php'; ?>