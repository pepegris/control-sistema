<?php
// report-ventas-facturas.php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

// Validación básica
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
</style>

<center>
    <h1>Facturas <?= $fecha_titulo ?> - <?= $fecha_titulo2 ?></h1>
</center>

<div class="table-responsive">
    <table class="table table-dark table-striped table-sm" id="tblData">
        <thead>
            <tr>
                <th scope='col'>Fecha</th>
                <th scope="col">Cod</th>
                <th scope='col'>Empresa</th>
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
        // Inicializar Acumuladores Globales
        $total_pagos_bs = 0;
        $total_pagos_usd = 0;

        // Bucle de Tiendas (Empieza en 1 para saltar Previa Shop)
        for ($i = 1; $i < count($sedes_ar); $i++) {
            
            $sede = $sedes_ar[$i];
            $cod = Cliente($sede); // Asumo que esta función está en mysql.php

            // Llamada a la función corregida en diarias.php
            $res_factura = getFacturaDetalles($sede, $fecha1, $fecha2);

            if (!empty($res_factura) && is_array($res_factura)) {
                
                for ($x = 0; $x < count($res_factura); $x++) {
                    
                    // Extracción de datos
                    $tp_doc_cob = $res_factura[$x]['tp_doc_cob'];
                    $doc_num    = $res_factura[$x]['FACTURA'];
                    $neto       = $res_factura[$x]['neto'];
                    $cob_num    = $res_factura[$x]['COBROS'];
                    
                    // Manejo seguro de Fecha (DateTime object)
                    $fecha = "";
                    if (isset($res_factura[$x]['fec_cob']) && $res_factura[$x]['fec_cob'] instanceof DateTime) {
                        $fecha = $res_factura[$x]['fec_cob']->format("d-m-Y");
                    }

                    $tip_cob  = $res_factura[$x]['tip_cob'];
                    $mont_doc = $res_factura[$x]['mont_doc'];
                    $cod_caja = $res_factura[$x]['cod_caja'];
                    $des_caja = $res_factura[$x]['des_caja'];

                    // --- LÓGICA DE SUMA DE TOTALES ---
                    // AQUÍ DEBES AJUSTAR SEGÚN TU CRITERIO DE CAJAS
                    // Ejemplo: Si la caja es '01' o 'EF' es Bolívares, si es '02' o 'US' es Dólares.
                    // Como no sé tus códigos de caja exactos, sumo todo en BS por defecto.
                    // MODIFICA ESTA LÍNEA:
                    
                    if (strpos(strtolower($des_caja), 'dolar') !== false || strpos(strtolower($des_caja), 'usd') !== false) {
                        $total_pagos_usd += $mont_doc;
                    } else {
                        $total_pagos_bs += $mont_doc;
                    }
                    // ---------------------------------
                ?>
                    <tr>
                        <td><?= $fecha ?></td>
                        <td><?= $cod ?></td>
                        <td><?= $sede ?></td>
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
                } // Fin bucle facturas
            } // Fin if array
        } // Fin bucle sedes
        ?>

        <tr class="table-secondary fw-bold" style="border-top: 2px solid white;">
            <td colspan="8" class="text-end">TOTALES GENERALES:</td>
            <td class="text-end">Bs <?= number_format($total_pagos_bs, 2, ',', '.') ?></td>
            <td colspan="2">USD <?= number_format($total_pagos_usd, 2, ',', '.') ?> (Aprox)</td>
        </tr>

        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>