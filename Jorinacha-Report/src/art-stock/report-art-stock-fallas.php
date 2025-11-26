<?php
// Aumentamos memoria y tiempo por la cantidad de datos
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/fallas/fallas-report.php';

// Validar que vengan parametros
if (!isset($_GET['linea'])) {
    header("location: form.php");
    exit;
}

$linea = $_GET['linea'];
$almacen = $_GET['almacen'];
// Formato seguro para SQL Server (YYYYMMDD)
$fecha1 = date("Ymd", strtotime($_GET['fecha1']));
$fecha2 = date("Ymd", strtotime($_GET['fecha2']));
?>

<style>
    form, td, th { font-size: 11px; } /* Ajuste leve para que quepa más info */
    .table-responsive { overflow-x: auto; }
</style>

<div class="container-fluid">
    <center><h3>Reporte de Fallas y Stock (Multi-Sede)</h3></center>
    
    <div class="table-responsive">
        <table class="table table-dark table-striped table-bordered table-hover" id="tblData">
            <thead>
                <tr>
                    <th scope="col" class="bg-primary">#</th>
                    <th scope='col' class="bg-primary">Código</th>
                    <th scope='col' class="bg-primary">Marca</th>
                    <th scope='col' class="bg-primary">Modelo</th>
                    <th scope='col' class="bg-primary">Ubicación</th>
                    <th scope='col' class="bg-primary">Cat</th>
                    <th scope='col' class="bg-primary">Color</th>
                    <th scope='col' class="bg-primary">Precio</th>
                    <th scope='col' class="bg-primary">Stock Real (Almacén)</th>

                    <?php
                    // Cabeceras Dinámicas de las Sedes
                    foreach ($sedes_ar as $sede) {
                        if ($sede != null && $sede != 'Previa Shop') {
                            echo "<th scope='col' style='color:yellow'>$sede (Stock)</th>";
                            echo "<th scope='col' style='color:#00ff00'>$sede (Ventas)</th>";
                        }
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // 1. Obtener lista Maestra de Artículos (Previa Shop)
                $articulosMaster = getArt('Previa Shop', $linea, 0, 0);

                if (empty($articulosMaster)) {
                    echo "<tr><td colspan='20'>No se encontraron artículos para esta línea o criterios de búsqueda.</td></tr>";
                }

                $n = 1;
                $total_stock_act_previa = 0;
                
                // Inicializar arrays de totales por tienda
                $total_stock_tienda = [];
                $total_venta_tienda = [];

                foreach ($articulosMaster as $articulo) {
                    $co_art   = $articulo['co_art'];
                    $stock_db = round($articulo['stock_act']);
                    $precio   = number_format($articulo['prec_vta5'], 2);
                    
                    // 2. Calcular Stock Real en Previa Shop (Menos pedidos)
                    $pedidosData = getPedidos(null, $co_art);
                    $cant_comprometida = $pedidosData['total_art'];
                    
                    $stock_real_previa = $stock_db - $cant_comprometida;
                    $total_stock_act_previa += $stock_real_previa;

                    // Estilo para el stock principal
                    $styleStockMain = ($stock_real_previa > 0) ? "font-weight:bold; color:#fff;" : "color:#999;";
                ?>
                    <tr>
                        <td><?= $n ?></td>
                        <td><?= $co_art ?></td>
                        <td><?= $articulo['co_lin'] ?></td>
                        <td><?= $articulo['co_subl'] ?></td>
                        <td><?= $articulo['ubicacion'] ?></td>
                        <td><?= $articulo['co_cat'] ?></td>
                        <td><?= $articulo['co_color'] ?></td>
                        <td>$<?= $precio ?></td>
                        
                        <!-- Columna Stock Central -->
                        <td style="<?= $styleStockMain ?>"><?= $stock_real_previa ?></td>

                        <?php
                        // 3. Iterar por las tiendas para este artículo
                        foreach ($sedes_ar as $sede) {
                            if ($sede == null || $sede == 'Previa Shop') continue;

                            // Inicializar contadores globales para esta sede si no existen
                            if (!isset($total_stock_tienda[$sede])) $total_stock_tienda[$sede] = 0;
                            if (!isset($total_venta_tienda[$sede])) $total_venta_tienda[$sede] = 0;

                            // A. Obtener Stock Tienda
                            $resStock = getArt_stock_tiendas($sede, $co_art);
                            $stock_tienda = 0;
                            if (!empty($resStock)) {
                                $stock_tienda = round($resStock[0]['stock_act']);
                            }

                            // B. Obtener Ventas Tienda
                            $ventas_tienda = getReng_fac($sede, $co_art, $fecha1, $fecha2);
                            $ventas_tienda = round($ventas_tienda);

                            // Acumular Totales
                            $total_stock_tienda[$sede] += $stock_tienda;
                            $total_venta_tienda[$sede] += $ventas_tienda;

                            // Estilos Condicionales
                            $styleStock = ($stock_tienda > 0) ? "font-weight:bold; color:yellow;" : "color:#555;";
                            $styleVenta = ($ventas_tienda > 0) ? "font-weight:bold; color:#00ff00;" : "color:#555;";
                        ?>
                            <!-- Celdas de Datos por Tienda -->
                            <td style="<?= $styleStock ?>"><?= $stock_tienda ?></td>
                            <td style="<?= $styleVenta ?>"><?= $ventas_tienda ?></td>

                        <?php 
                        } // Fin foreach sedes 
                        ?>
                    </tr>
                <?php
                    $n++;
                } // Fin foreach articulos
                ?>

                <!-- FILA DE TOTALES -->
                <tr style="background-color: #333; font-weight: bold;">
                    <td colspan="8" style="text-align: right;">TOTALES GENERALES:</td>
                    <td><?= $total_stock_act_previa ?></td>
                    <?php
                    foreach ($sedes_ar as $sede) {
                        if ($sede != null && $sede != 'Previa Shop') {
                            echo "<td style='color:yellow'>" . ($total_stock_tienda[$sede] ?? 0) . "</td>";
                            echo "<td style='color:#00ff00'>" . ($total_venta_tienda[$sede] ?? 0) . "</td>";
                        }
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>