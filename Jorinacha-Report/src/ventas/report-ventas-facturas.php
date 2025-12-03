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
    .text-end { text-align: right !important; }
    .text-center { text-align: center !important; }
    .fw-bold { font-weight: bold; }
    
    /* Estilo para el título de cada tienda */
    .store-header {
        background-color: #444;
        color: #fff;
        padding: 10px;
        margin-top: 30px;
        margin-bottom: 5px;
        border-radius: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .store-title { font-size: 1.2rem; margin: 0; }
    
    /* Totales globales flotantes o fijos al final */
    .grand-total-box {
        background-color: #222;
        border: 2px solid #555;
        padding: 20px;
        margin-top: 40px;
        border-radius: 10px;
    }
</style>

<center>
    <h1>Reporte Detallado de Facturas</h1>
    <h4>Desde <?= $fecha_titulo ?> Hasta <?= $fecha_titulo2 ?></h4>
</center>

<div class="container-fluid">

    <?php
    // Totales Generales (De todas las tiendas juntas)
    $gran_total_bs = 0;
    $gran_total_usd = 0;

    // Bucle de Tiendas (Empieza en 1 para saltar Previa Shop)
    for ($i = 1; $i < count($sedes_ar); $i++) {
        
        $sede = $sedes_ar[$i];
        $cod_cliente = Cliente($sede);

        // 1. Consultar datos de esta sede
        $res_factura = getFacturaDetalles($sede, $fecha1, $fecha2);

        // 2. Solo dibujamos tabla SI HAY DATOS
        if (!empty($res_factura) && is_array($res_factura)) {
            
            // Totales por Sede (Se reinician en cada vuelta del bucle)
            $subtotal_sede_bs = 0;
            $subtotal_sede_usd = 0;
    ?>
            
            <div class="store-header">
                <h3 class="store-title"><?= $sede ?> <small>(<?= $cod_cliente ?>)</small></h3>
                <span class="badge bg-primary"><?= count($res_factura) ?> Registros</span>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-striped table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th scope='col'>Fecha</th>
                            <th scope='col'>Doc</th>
                            <th scope='col'>Num Fact</th>
                            <th scope='col' class="text-end">Monto Fact</th>
                            <th scope='col'>Num Cob</th>
                            <th scope='col'>Tipo</th>
                            <th scope='col' class="text-end">Monto Cob</th>
                            <th scope='col'>Caja</th>
                            <th scope='col'>Desc Caja</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($x = 0; $x < count($res_factura); $x++) {
                        
                        $tp_doc_cob = $res_factura[$x]['tp_doc_cob'];
                        $doc_num    = $res_factura[$x]['FACTURA'];
                        $neto       = $res_factura[$x]['neto'];
                        $cob_num    = $res_factura[$x]['COBROS'];
                        $tip_cob    = $res_factura[$x]['tip_cob'];
                        $mont_doc   = $res_factura[$x]['mont_doc'];
                        $cod_caja   = $res_factura[$x]['cod_caja'];
                        $des_caja   = $res_factura[$x]['des_caja'];
                        
                        // Fecha
                        $fecha = "";
                        if (isset($res_factura[$x]['fec_cob']) && $res_factura[$x]['fec_cob'] instanceof DateTime) {
                            $fecha = $res_factura[$x]['fec_cob']->format("d/m/Y");
                        }

                        // Lógica de Acumulados (Ajusta la palabra clave según tus cajas)
                        // Si la descripción de caja dice "Dolar" o "USD", suma a dolares. Sino a Bs.
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
                    
                    // Sumamos al Gran Total
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
            </div>
            
    <?php
        } // Fin if empty
    } // Fin for sedes
    ?>

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

</div>

<?php include '../../includes/footer.php'; ?>