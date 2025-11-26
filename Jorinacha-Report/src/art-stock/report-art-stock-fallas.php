<?php
// =============================================================================
// REPORTE OPTIMIZADO: STOCK Y FALLAS (MULTI-SEDE)
// =============================================================================
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

// Detectar si solicitan Excel
$is_export = isset($_GET['export']) && $_GET['export'] == 1;

if ($is_export) {
    // Cabeceras para forzar descarga como Excel (.xls)
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header("Content-Disposition: attachment; filename=Reporte_Fallas_" . date('Ymd_His') . ".xls");
    header("Content-Transfer-Encoding: binary");
    echo "\xEF\xBB\xBF"; // BOM para caracteres UTF-8 (ñ, tildes) en Excel
} else {
    // Solo incluir log y headers visuales si NO es exportación
    require "../../includes/log.php";
    include '../../includes/header.php';
}

include '../../services/mysql.php';
// IMPORTANTE: Incluimos el archivo de lógica que contiene las nuevas funciones Batch
include '../../services/adm/fallas/fallas-report.php'; 

// --- Validación de Parámetros ---
if (!isset($_GET['linea'])) {
    if (!$is_export) echo "<script>window.location='form.php';</script>";
    exit;
}

$linea = $_GET['linea'];
$almacen = isset($_GET['almacen']) ? $_GET['almacen'] : '0';

// Formato de fechas para SQL Server (YYYYMMDD)
$fecha1 = date("Ymd", strtotime($_GET['fecha1']));
$fecha2 = date("Ymd", strtotime($_GET['fecha2']));
?>

<?php if (!$is_export): ?>
<!-- ESTILOS VISUALES (SOLO PARA PANTALLA) -->
<style>
    /* 1. CAMBIO DE FONDO SOLICITADO */
    body { 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        background-color: #242943; /* Color solicitado */
        color: white; 
    }
    
    h2 { text-transform: uppercase; letter-spacing: 2px; margin-top: 20px; color: #fff; }
    
    .table-responsive {
        max-height: 80vh; 
        overflow: auto;
        box-shadow: 0 0 15px rgba(0,0,0,0.5);
        border: 1px solid #444;
        margin-top: 15px;
    }

    #tblData { 
        font-size: 11px; 
        white-space: nowrap; 
        border-collapse: separate; 
        border-spacing: 0;
        background-color: #242943; /* Fondo tabla igual al body */
        width: 100%;
    }

    #tblData th, #tblData td { 
        padding: 5px 8px; 
        vertical-align: middle; 
        border: 1px solid #444;
    }

    /* 3. COLUMNAS FIJAS (STICKY) */
    /* Columna #: Fija a la izquierda del todo */
    .sticky-col-1 { 
        position: sticky; 
        left: 0; 
        background-color: #1f2235; 
        z-index: 10; 
        border-right: 1px solid #555; 
        width: 30px;
        text-align: center;
    }
    
    /* Columna Código: Fija después del # */
    .sticky-col-2 { 
        position: sticky; 
        left: 30px; /* Ancho aprox de col 1 */
        background-color: #1f2235; 
        z-index: 10; 
        border-right: 1px solid #555; 
        min-width: 90px;
    }

    /* Columna Modelo: Fija después del Código (NUEVO) */
    .sticky-col-3 { 
        position: sticky; 
        left: 120px; /* 30px (#) + 90px (Cod) aprox */
        background-color: #1f2235; 
        z-index: 10; 
        border-right: 2px solid #999; /* Borde más grueso para separar de la data */
        min-width: 100px;
        font-weight: bold;
        color: #ddd;
    }
    
    /* Encabezados Fijos Arriba */
    thead th { 
        position: sticky; 
        top: 0; 
        background-color: #1a1d20; 
        z-index: 20; 
        color: white; 
        border-bottom: 2px solid #00ff99;
        height: 40px;
    }
    
    /* Asegurar que las intersecciones de sticky (esquina superior izq) tengan z-index mayor */
    thead th.sticky-col-1, thead th.sticky-col-2, thead th.sticky-col-3 {
        z-index: 30;
        background-color: #1a1d20;
    }

    /* Colores Semánticos */
    .text-stock { color: #ffd700; font-weight: bold; } /* Amarillo */
    .text-venta { color: #00ff99; font-weight: bold; } /* Verde Neon */
    .text-muted-custom { color: #666; }
    
    .total-row { background-color: #0d6efd !important; color: white; font-weight: bold; }
    .total-row td { border-top: 2px solid white; }

    /* Botonera */
    .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    .btn-excel { 
        background-color: #198754; 
        color: white; 
        padding: 8px 15px; 
        text-decoration: none; 
        border-radius: 4px; 
        font-weight: bold;
        border: 1px solid #146c43;
    }
    .btn-excel:hover { background-color: #157347; color: white; }
</style>
<?php else: ?>
    <!-- ESTILOS BASICOS PARA EXCEL (Fondo blanco, bordes negros) -->
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #ddd; font-weight: bold; text-align: center; }
        .text-stock { background-color: #fff2cc; color: #000; font-weight: bold; }
        .text-venta { background-color: #d9ead3; color: #000; font-weight: bold; }
        .total-row { background-color: #cfe2f3; font-weight: bold; }
    </style>
<?php endif; ?>

<div class="container-fluid">
    <center>
        <h2>Reporte de Fallas y Stock</h2>
        <p class="text-muted" style="<?= $is_export ? 'color:#000;' : 'color:#aaa !important;' ?>">
            Rango: <?= date("d/m/Y", strtotime($fecha1)) ?> - <?= date("d/m/Y", strtotime($fecha2)) ?>
        </p>
    </center>
    
    <?php if (!$is_export): ?>
    <div class="toolbar">
        <div></div> <!-- Espaciador -->
        <!-- BOTÓN DE EXPORTACIÓN -->
        <a href="?linea=<?=urlencode($_GET['linea'])?>&almacen=<?=urlencode($almacen)?>&fecha1=<?=urlencode($_GET['fecha1'])?>&fecha2=<?=urlencode($_GET['fecha2'])?>&export=1" class="btn-excel" target="_blank">
            &#x1F4E5; Descargar Excel
        </a>
    </div>
    <?php endif; ?>
    
    <?php
    // -------------------------------------------------------------------------
    // PASO 1: OBTENER LISTA MAESTRA
    // -------------------------------------------------------------------------
    $articulosMaster = getArt('Previa Shop', $linea, 0, 0);

    if (empty($articulosMaster)) {
        echo "<div class='alert alert-warning text-center'>No se encontraron artículos.</div>";
        if (!$is_export) include '../../includes/footer.php';
        exit;
    }

    // -------------------------------------------------------------------------
    // PASO 2: PREPARAR DATOS MASIVOS (BATCH PROCESSING)
    // -------------------------------------------------------------------------
    $listaCodigos = array_column($articulosMaster, 'co_art');
    
    $CACHE_PEDIDOS = [];
    $CACHE_STOCK_TIENDAS = [];
    $CACHE_VENTAS_TIENDAS = [];

    // -------------------------------------------------------------------------
    // PASO 3: PRE-CARGA DE DATOS
    // -------------------------------------------------------------------------
    
    // A. Cargar pedidos
    $CACHE_PEDIDOS = getBatchPedidos($listaCodigos);

    // B. Cargar Stock y Ventas Tiendas
    foreach ($sedes_ar as $sede) {
        if ($sede != null && $sede != 'Previa Shop') {
            $CACHE_STOCK_TIENDAS[$sede] = getBatchStock($sede, $listaCodigos);
            $CACHE_VENTAS_TIENDAS[$sede] = getBatchVentas($sede, $listaCodigos, $fecha1, $fecha2);
        }
    }
    
    // Variables para totales de columna
    $totales_stock_tienda = [];
    $totales_venta_tienda = [];
    $gran_total_previa = 0;
    ?>

    <!-- ----------------------------------------------------------------------- -->
    <!-- PASO 4: RENDERIZADO -->
    <!-- ----------------------------------------------------------------------- -->
    <div class="<?= $is_export ? '' : 'table-responsive' ?>">
        <table class="table table-dark table-hover" id="tblData" border="1">
            <thead>
                <tr>
                    <th scope="col" class="<?= $is_export ? '' : 'sticky-col-1' ?>">#</th>
                    <th scope='col' class="<?= $is_export ? '' : 'sticky-col-2' ?>">Código</th>
                    <th scope='col' class="<?= $is_export ? '' : 'sticky-col-3' ?>">Modelo</th>
                    <th scope='col'>Descripción</th>
                    <th scope='col'>Ref</th>
                    <th scope='col'>Color</th>
                    <th scope='col'>Precio</th>
                    <th scope='col' style="<?= $is_export ? 'background-color:#ccc;' : 'background-color:#333; border-right: 3px solid #666;' ?>">STOCK<br>CENTRAL</th>

                    <?php
                    foreach ($sedes_ar as $sede) {
                        if ($sede != null && $sede != 'Previa Shop') {
                            $styleTh = $is_export ? "background-color:#eee;" : "color:#ffd700; border-left:1px solid #444;";
                            echo "<th scope='col' class='text-center' style='$styleTh'>Stock<br><small>$sede</small></th>";
                            
                            $styleThV = $is_export ? "background-color:#eee;" : "color:#00ff99;";
                            echo "<th scope='col' class='text-center' style='$styleThV'>Vta<br><small>$sede</small></th>";
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
                    $pedidos_pend = isset($CACHE_PEDIDOS[$codigo]) ? round($CACHE_PEDIDOS[$codigo]) : 0;
                    $stock_real_previa = $stock_fisico - $pedidos_pend;
                    
                    // 2. FILTRO DE "CERO ACTIVIDAD"
                    $es_activo = false;

                    // A. Chequear si tiene stock en central
                    if ($stock_real_previa > 0) {
                        $es_activo = true;
                    }

                    // B. Si no tiene en central, chequear todas las tiendas
                    if (!$es_activo) {
                        foreach ($sedes_ar as $sede) {
                            if ($sede != null && $sede != 'Previa Shop') {
                                // Consultar cache
                                $s_tmp = isset($CACHE_STOCK_TIENDAS[$sede][$codigo]) ? round($CACHE_STOCK_TIENDAS[$sede][$codigo]['stock']) : 0;
                                $v_tmp = isset($CACHE_VENTAS_TIENDAS[$sede][$codigo]) ? round($CACHE_VENTAS_TIENDAS[$sede][$codigo]) : 0;
                                
                                if ($s_tmp > 0 || $v_tmp > 0) {
                                    $es_activo = true;
                                    break;
                                }
                            }
                        }
                    }

                    // C. Saltar fila si inactivo
                    if (!$es_activo) {
                        continue;
                    }
                    
                    // --- RENDER ---
                    
                    // Acumulamos total general
                    $gran_total_previa += $stock_real_previa;

                    // Estilo Central
                    if ($is_export) {
                         $stylePrevia = "text-align:center; font-weight:bold;";
                    } else {
                         $stylePrevia = ($stock_real_previa > 0) ? "font-size:1.1em; font-weight:bold; color: white;" : "color: #666;";
                         $stylePrevia .= " background-color:#333; border-right: 3px solid #666; text-align:center;";
                    }
                    
                    $precio = number_format($art['prec_vta5'], 2);
                ?>
                    <tr>
                        <td class="<?= $is_export ? '' : 'sticky-col-1' ?>"><?= $n ?></td>
                        <td class="<?= $is_export ? '' : 'sticky-col-2' ?>"><strong><?= $codigo ?></strong></td>
                        <td class="<?= $is_export ? '' : 'sticky-col-3' ?>"><?= $art['co_subl'] ?></td>
                        <td title="<?= $art['ubicacion'] ?>"><?= substr($art['ubicacion'], 0, 25) ?>...</td>
                        <td><?= $art['co_lin'] ?></td>
                        <td><?= $art['co_color'] ?></td>
                        <td>$<?= $precio ?></td>
                        <td style="<?= $stylePrevia ?>">
                            <?= $stock_real_previa ?>
                        </td>

                        <?php
                        // --- Datos Tiendas ---
                        foreach ($sedes_ar as $sede) {
                            if ($sede != null && $sede != 'Previa Shop') {
                                
                                $stock_tienda = 0;
                                if (isset($CACHE_STOCK_TIENDAS[$sede][$codigo])) {
                                    $stock_tienda = round($CACHE_STOCK_TIENDAS[$sede][$codigo]['stock']);
                                }

                                $venta_tienda = 0;
                                if (isset($CACHE_VENTAS_TIENDAS[$sede][$codigo])) {
                                    $venta_tienda = round($CACHE_VENTAS_TIENDAS[$sede][$codigo]);
                                }

                                // Acumuladores
                                if (!isset($totales_stock_tienda[$sede])) $totales_stock_tienda[$sede] = 0;
                                if (!isset($totales_venta_tienda[$sede])) $totales_venta_tienda[$sede] = 0;
                                
                                $totales_stock_tienda[$sede] += $stock_tienda;
                                $totales_venta_tienda[$sede] += $venta_tienda;

                                // Estilos
                                if ($is_export) {
                                    // Para Excel: Estilos simples o clases definidas arriba
                                    $classStock = ($stock_tienda > 0) ? "text-stock" : "";
                                    $classVenta = ($venta_tienda > 0) ? "text-venta" : "";
                                    $styleCellS = "text-align:center;";
                                    $styleCellV = "text-align:center;";
                                } else {
                                    // Para Web: Estilos oscuros
                                    $classStock = ($stock_tienda > 0) ? "text-stock" : "text-muted-custom";
                                    $classVenta = ($venta_tienda > 0) ? "text-venta" : "text-muted-custom";
                                    $styleCellS = "border-left:1px solid #444;";
                                    $styleCellV = "";
                                }
                        ?>
                                <td class="<?= $classStock ?> text-center" style="<?= $styleCellS ?>"><?= $stock_tienda ?></td>
                                <td class="<?= $classVenta ?> text-center" style="<?= $styleCellV ?>"><?= $venta_tienda ?></td>
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
                    <td class="text-center" style="<?= $is_export ? '' : 'border-right: 3px solid #ddd;' ?> font-size: 1.2em;"><?= $gran_total_previa ?></td>
                    <?php
                    foreach ($sedes_ar as $sede) {
                        if ($sede != null && $sede != 'Previa Shop') {
                            $ts = isset($totales_stock_tienda[$sede]) ? $totales_stock_tienda[$sede] : 0;
                            $tv = isset($totales_venta_tienda[$sede]) ? $totales_venta_tienda[$sede] : 0;
                            
                            $sTs = $is_export ? "font-weight:bold;" : "color:#fff000; border-left:1px solid #ddd;";
                            $sTv = $is_export ? "font-weight:bold;" : "color:#fff;";
                            
                            echo "<td class='text-center' style='$sTs'>$ts</td>";
                            echo "<td class='text-center' style='$sTv'>$tv</td>";
                        }
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php if (!$is_export) include '../../includes/footer.php'; ?>