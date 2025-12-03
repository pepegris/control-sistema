<?php
// report-ventas-facturas.php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

if (!isset($_GET['fecha1'])) {
    header("location: form.php");
    exit;
}

$fecha_titulo = date("d/m/Y", strtotime($_GET['fecha1']));
$fecha_titulo2 = date("d/m/Y", strtotime($_GET['fecha2']));
$fecha1 = date("Ymd", strtotime($_GET['fecha1']));
$fecha2 = date("Ymd", strtotime($_GET['fecha2']));
?>

<style>
    /* Estilos generales */
    html { scroll-behavior: smooth; } /* Desplazamiento suave */
    
    .text-end { text-align: right !important; }
    .text-center { text-align: center !important; }
    .fw-bold { font-weight: bold; }
    
    /* Estilos del Índice */
    .index-container {
        background-color: #2c3e50;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 30px;
        border: 1px solid #444;
    }
    .index-title { color: #fff; font-size: 1.1rem; margin-bottom: 10px; border-bottom: 1px solid #555; padding-bottom: 5px; }
    .btn-index {
        margin: 3px;
        font-size: 0.85rem;
        background-color: #34495e;
        border: 1px solid #555;
        color: #ecf0f1;
    }
    .btn-index:hover { background-color: #3498db; color: white; }
    
    /* Encabezados de Tienda */
    .store-header {
        background-color: #444;
        color: #fff;
        padding: 10px;
        margin-top: 40px; /* Espacio para que el ancla no quede tapada */
        margin-bottom: 5px;
        border-radius: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        scroll-margin-top: 80px; /* Ajuste para menú fijo si tienes */
    }
    .store-title { font-size: 1.2rem; margin: 0; }
    
    /* Totales y botón volver */
    .grand-total-box {
        background-color: #222;
        border: 2px solid #555;
        padding: 20px;
        margin-top: 40px;
        border-radius: 10px;
    }
    .btn-top { text-decoration: none; color: #3498db; font-size: 0.8rem; float: right; margin-top: 5px; cursor: pointer; }
</style>

<center>
    <h1>Reporte Detallado de Facturas</h1>
    <h4>Desde <?= $fecha_titulo ?> Hasta <?= $fecha_titulo2 ?></h4>
</center>

<div class="container-fluid">

    <?php
    // Variables para almacenar contenido
    $indice_links = []; // Array para guardar los botones del índice
    $contenido_tablas = ""; // String gigante para guardar las tablas HTML
    
    $gran_total_bs = 0;
    $gran_total_usd = 0;

    // BUCLE DE PROCESAMIENTO
    for ($i = 1; $i < count($sedes_ar); $i++) {
        
        $sede = $sedes_ar[$i];
        $cod_cliente = Cliente($sede);
        
        // Crear un ID único para el ancla (ej: store_1, store_2)
        $anchor_id = "store_" . $i;

        // 1. Consultar datos
        $res_factura = getFacturaDetalles($sede, $fecha1, $fecha2);

        // 2. Si hay datos, procesamos y guardamos en memoria
        if (!empty($res_factura) && is_array($res_factura)) {
            
            // A. Agregar al Índice
            $count_regs = count($res_factura);
            $indice_links[] = "<a href='#$anchor_id' class='btn btn-sm btn-index'>$sede <span class='badge bg-secondary'>$count_regs</span></a>";
            
            // B. Generar HTML de la Tabla (Concatenamos a la variable $contenido_tablas)
            $subtotal_sede_bs = 0;
            $subtotal_sede_usd = 0;
            
            // Iniciamos buffer de salida para capturar HTML limpio
            ob_start(); 
            ?>
            
            <div id="<?= $anchor_id ?>" class="store-header">
                <h3 class="store-title"><?= $sede ?> <small class="text-muted" style="font-size:0.7em">(<?= $cod_cliente ?>)</small></h3>
                <div>
                    <span class="badge bg-primary"><?= $count_regs ?> Registros</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-striped table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Doc</th>
                            <th>Num Fact</th>
                            <th class="text-end">Monto Fact</th>
                            <th>Num Cob</th>
                            <th>Tipo</th>
                            <th class="text-end">Monto Cob</th>
                            <th>Caja</th>
                            <th>Desc Caja</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($x = 0; $x < $count_regs; $x++) {
                        
                        $tp_doc_cob = $res_factura[$x]['tp_doc_cob'];
                        $doc_num    = $res_factura[$x]['FACTURA'];
                        $neto       = $res_factura[$x]['neto'];
                        $cob_num    = $res_factura[$x]['COBROS'];
                        $tip_cob    = $res_factura[$x]['tip_cob'];
                        $mont_doc   = $res_factura[$x]['mont_doc'];
                        $cod_caja   = $res_factura[$x]['cod_caja'];
                        $des_caja   = $res_factura[$x]['des_caja'];
                        
                        $fecha = "";
                        if (isset($res_factura[$x]['fec_cob']) && $res_factura[$x]['fec_cob'] instanceof DateTime) {
                            $fecha = $res_factura[$x]['fec_cob']->format("d/m/Y");
                        }

                        // Acumuladores
                        if (strpos(strtolower($des_caja), 'dolar') !== false || strpos(strtolower($des_caja), 'usd') !== false || strpos(strtolower($des_caja), 'divisa') !== false) {
                            $subtotal_sede_usd += $mont_doc;
                        } else {
                            $subtotal_sede_bs += $mont_doc;
                        }
                    ?>
                        <tr>
                            <td><?= $fecha ?></td>
                            <td><?= $tp_doc_cob ?></td>
                            <td><?= $doc_num ?></td>
                            <td class="text-end"><?= number_format($neto, 2, ',', '.') ?></td>
                            <td><?= $cob_num ?></td>
                            <td><?= $tip_cob ?></td>
                            <td class="text-end"><?= number_format($mont_doc, 2, ',', '.') ?></td>
                            <td><?= $cod_caja ?></td>
                            <td><?= $des_caja ?></td>
                        </tr>
                    <?php 
                    } // Fin for facturas 
                    
                    // Sumar al Global
                    $gran_total_bs += $subtotal_sede_bs;
                    $gran_total_usd += $subtotal_sede_usd;
                    ?>
                    
                    <tr class="table-secondary fw-bold">
                        <td colspan="6" class="text-end">Total <?= $sede ?>:</td>
                        <td class="text-end">Bs <?= number_format($subtotal_sede_bs, 2, ',', '.') ?></td>
                        <td colspan="2">USD <?= number_format($subtotal_sede_usd, 2, ',', '.') ?></td>
                    </tr>
                    </tbody>
                </table>
                <a href="#top-index" class="btn-top">⬆ Volver al Índice</a>
                <div style="clear:both; margin-bottom: 20px;"></div>
            </div>
            
            <?php
            // Capturamos todo el HTML generado arriba y lo agregamos a la variable gigante
            $contenido_tablas .= ob_get_clean();
            
        } // Fin if empty
    } // Fin for sedes
    ?>

    <?php if (!empty($indice_links)): ?>
        <div id="top-index" class="index-container">
            <div class="index-title">Índice de Tiendas (Clic para ir)</div>
            <div class="d-flex flex-wrap">
                <?php 
                foreach ($indice_links as $link) {
                    echo $link;
                }
                ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">No se encontraron facturas en el rango de fechas seleccionado.</div>
    <?php endif; ?>

    <?= $contenido_tablas ?>

    <?php if (!empty($indice_links)): ?>
    <div class="grand-total-box">
        <h2 class="text-center text-white">TOTALES CONSOLIDADOS</h2>
        <div class="row text-center mt-3">
            <div class="col-md-6">
                <h3 class="text-success">Bolívares: Bs <?= number_format($gran_total_bs, 2, ',', '.') ?></h3>
            </div>
            <div class="col-md-6">
                <h3 class="text-info">Divisas: $ <?= number_format($gran_total_usd, 2, ',', '.') ?></h3>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php include '../../includes/footer.php'; ?>