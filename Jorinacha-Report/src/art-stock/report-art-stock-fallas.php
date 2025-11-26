<?php
// =============================================================================
// REPORTE OPTIMIZADO: STOCK Y FALLAS (MULTI-SEDE)
// =============================================================================
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';

// IMPORTANTE: Incluimos el archivo de lógica que contiene las nuevas funciones Batch
include '../../services/adm/fallas/fallas-report.php'; 

// --- Validación de Parámetros ---
if (!isset($_GET['linea'])) {
    // Si no hay parámetros, redirigir o mostrar error
    echo "<script>window.location='form.php';</script>";
    exit;
}

$linea = $_GET['linea'];
$almacen = isset($_GET['almacen']) ? $_GET['almacen'] : '0';

// Formato de fechas para SQL Server (YYYYMMDD)
$fecha1 = date("Ymd", strtotime($_GET['fecha1']));
$fecha2 = date("Ymd", strtotime($_GET['fecha2']));
?>

<style>
    /* Estilos para compactar la tabla y manejar el scroll */
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #2c2c2c; color: white; }
    h2 { text-transform: uppercase; letter-spacing: 2px; margin-top: 20px; }
    
    .table-responsive {
        max-height: 80vh; /* Altura máxima de la tabla */
        overflow: auto;
        box-shadow: 0 0 15px rgba(0,0,0,0.5);
    }

    #tblData { 
        font-size: 11px; 
        white-space: nowrap; 
        border-collapse: separate; /* Necesario para sticky headers */
        border-spacing: 0;
    }

    #tblData th, #tblData td { 
        padding: 5px 8px; 
        vertical-align: middle; 
        border: 1px solid #444;
    }

    /* Columnas Fijas (Sticky) a la izquierda */
    .sticky-col-1 { position: sticky; left: 0; background-color: #212529; z-index: 10; border-right: 2px solid #555; }
    .sticky-col-2 { position: sticky; left: 30px; background-color: #212529; z-index: 10; border-right: 2px solid #555; }
    
    /* Encabezados Fijos (Sticky) arriba */
    thead th { position: sticky; top: 0; background-color: #1a1d20; z-index: 20; color: white; }
    
    /* Colores Semánticos */
    .text-stock { color: #ffd700; font-weight: bold; } /* Amarillo */
    .text-venta { color: #00ff99; font-weight: bold; } /* Verde Neon */
    .text-muted-custom { color: #555; }
    
    .total-row { background-color: #0d6efd !important; color: white; font-weight: bold; }
    .total-row td { border-top: 2px solid white; }
</style>

<div class="container-fluid">
    <center>
        <h2>Reporte de Fallas y Stock</h2>
        <p class="text-muted">Rango: <?= date("d/m/Y", strtotime($fecha1)) ?> - <?= date("d/m/Y", strtotime($fecha2)) ?></p>
    </center>
    
    <?php
    // -------------------------------------------------------------------------
    // PASO 1: OBTENER LISTA MAESTRA (Artículos de la línea seleccionada)
    // -------------------------------------------------------------------------
    $articulosMaster = getArt('Previa Shop', $linea, 0, 0);

    if (empty($articulosMaster)) {
        echo "<div class='alert alert-warning text-center'>No se encontraron artículos para la línea seleccionada.</div>";
        include '../../includes/footer.php';
        exit;
    }

    // -------------------------------------------------------------------------
    // PASO 2: PREPARAR DATOS MASIVOS (BATCH PROCESSING)
    // Extraemos todos los códigos en un array simple
    // -------------------------------------------------------------------------
    $listaCodigos = array_column($articulosMaster, 'co_art');
    
    // Inicializamos Arrays de Caché (Memoria)
    $CACHE_PEDIDOS = [];
    $CACHE_STOCK_TIENDAS = [];
    $CACHE_VENTAS_TIENDAS = [];

    // -------------------------------------------------------------------------
    // PASO 3: PRE-CARGA (CONSULTAS INTELIGENTES)
    // Aquí ocurre la optimización: 1 Consulta por Tienda en vez de 1 consulta por artículo
    // -------------------------------------------------------------------------
    
    // A. Cargar todos los pedidos pendientes de una vez
    $CACHE_PEDIDOS = getBatchPedidos($listaCodigos);

    // B. Cargar Stock y Ventas de cada tienda
    foreach ($sedes_ar as $sede) {
        if ($sede != null && $sede != 'Previa Shop') {
            // Trae el stock de TODOS los artículos en la lista para esta sede
            $CACHE_STOCK_TIENDAS[$sede] = getBatchStock($sede, $listaCodigos);
            
            // Trae las ventas de TODOS los artículos en la lista para esta sede
            $CACHE_VENTAS_TIENDAS[$sede] = getBatchVentas($sede, $listaCodigos, $fecha1, $fecha2);
        }
    }
    
    // Variables para totales de columna (verticales)
    $totales_stock_tienda = [];
    $totales_venta_tienda = [];
    $gran_total_previa = 0;
    ?>

    <!-- ----------------------------------------------------------------------- -->
    <!-- PASO 4: RENDERIZADO (Puro acceso a memoria, muy rápido) -->
    <!-- ----------------------------------------------------------------------- -->
    <div class="table-responsive">
        <table class="table table-dark table-hover" id="tblData">
            <thead>
                <tr>
                    <th scope="col" class="sticky-col-1">#</th>
                    <th scope='col' class="sticky-col-2">Código</th>
                    <th scope='col'>Descripción</th>
                    <th scope='col'>Modelo</th>
                    <th scope='col'>Ref</th>
                    <th scope='col'>Color</th>
                    <th scope='col'>Precio</th>
                    <th scope='col' style="background-color:#333; border-right: 3px solid #666;">STOCK<br>CENTRAL</th>

                    <?php
                    // Generar cabeceras dinámicas de tiendas
                    foreach ($sedes_ar as $sede) {
                        if ($sede != null && $sede != 'Previa Shop') {
                            echo "<th scope='col' class='text-center' style='color:#ffd700; border-left:1px solid #444;'>Stock<br><small>$sede</small></th>";
                            echo "<th scope='col' class='text-center' style='color:#00ff99;'>Vta<br><small>$sede</small></th>";
                        }
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $n = 1;
                foreach ($articulosMaster as $art) {
                    $codigo = trim($art['co_art']);
                    
                    // --- Datos Previa Shop (Central) ---
                    $stock_fisico = round($art['stock_act']);
                    
                    // Leemos los pedidos desde la memoria (Array Cache)
                    $pedidos_pend = isset($CACHE_PEDIDOS[$codigo]) ? round($CACHE_PEDIDOS[$codigo]) : 0;
                    
                    // Stock Real = Físico - Comprometido
                    $stock_real_previa = $stock_fisico - $pedidos_pend;
                    
                    // Acumulamos total general
                    $gran_total_previa += $stock_real_previa;

                    // Estilo condicional para Central
                    $stylePrevia = ($stock_real_previa > 0) ? "font-size:1.1em; font-weight:bold; color: white;" : "color: #666;";
                    $precio = number_format($art['prec_vta5'], 2);
                ?>
                    <tr>
                        <td class="sticky-col-1"><?= $n ?></td>
                        <td class="sticky-col-2"><strong><?= $codigo ?></strong></td>
                        <td title="<?= $art['ubicacion'] ?>"><?= substr($art['ubicacion'], 0, 20) ?>...</td>
                        <td><?= $art['co_subl'] ?></td>
                        <td><?= $art['co_lin'] ?></td>
                        <td><?= $art['co_color'] ?></td>
                        <td>$<?= $precio ?></td>
                        <td style="<?= $stylePrevia ?> background-color:#333; border-right: 3px solid #666; text-align:center;">
                            <?= $stock_real_previa ?>
                        </td>

                        <?php
                        // --- Datos Tiendas (Leídos desde Cache) ---
                        foreach ($sedes_ar as $sede) {
                            if ($sede != null && $sede != 'Previa Shop') {
                                
                                // 1. STOCK TIENDA
                                $stock_tienda = 0;
                                // Verificamos si existe el dato en el array masivo
                                if (isset($CACHE_STOCK_TIENDAS[$sede][$codigo])) {
                                    $stock_tienda = round($CACHE_STOCK_TIENDAS[$sede][$codigo]['stock']);
                                }

                                // 2. VENTA TIENDA
                                $venta_tienda = 0;
                                if (isset($CACHE_VENTAS_TIENDAS[$sede][$codigo])) {
                                    $venta_tienda = round($CACHE_VENTAS_TIENDAS[$sede][$codigo]);
                                }

                                // 3. ACUMULADORES VERTICALES
                                if (!isset($totales_stock_tienda[$sede])) $totales_stock_tienda[$sede] = 0;
                                if (!isset($totales_venta_tienda[$sede])) $totales_venta_tienda[$sede] = 0;
                                
                                $totales_stock_tienda[$sede] += $stock_tienda;
                                $totales_venta_tienda[$sede] += $venta_tienda;

                                // 4. ESTILOS
                                $classStock = ($stock_tienda > 0) ? "text-stock" : "text-muted-custom";
                                $classVenta = ($venta_tienda > 0) ? "text-venta" : "text-muted-custom";
                        ?>
                                <td class="<?= $classStock ?> text-center" style="border-left:1px solid #444;"><?= $stock_tienda ?></td>
                                <td class="<?= $classVenta ?> text-center"><?= $venta_tienda ?></td>
                        <?php 
                            }
                        } 
                        ?>
                    </tr>
                <?php
                    $n++;
                } // Fin foreach articulos
                ?>

                <!-- FILA DE TOTALES -->
                <tr class="total-row">
                    <td colspan="7" class="text-right" style="text-align: right; padding-right: 15px;">TOTALES GENERALES:</td>
                    <td class="text-center" style="border-right: 3px solid #ddd; font-size: 1.2em;"><?= $gran_total_previa ?></td>
                    <?php
                    foreach ($sedes_ar as $sede) {
                        if ($sede != null && $sede != 'Previa Shop') {
                            $ts = isset($totales_stock_tienda[$sede]) ? $totales_stock_tienda[$sede] : 0;
                            $tv = isset($totales_venta_tienda[$sede]) ? $totales_venta_tienda[$sede] : 0;
                            
                            echo "<td class='text-center' style='color:#fff000; border-left:1px solid #ddd;'>$ts</td>";
                            echo "<td class='text-center' style='color:#fff;'>$tv</td>";
                        }
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>