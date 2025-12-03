<?php
// report-lysto.php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/lysto.php'; // <--- Cargamos el archivo de Lysto

if (!isset($_GET['fecha1'])) {
    header("location: form.php");
    exit;
}

$fecha_titulo1 = date("d/m/Y", strtotime($_GET['fecha1']));
$fecha_titulo2 = date("d/m/Y", strtotime($_GET['fecha2']));
$fecha1 = date("Ymd", strtotime($_GET['fecha1']));
$fecha2 = date("Ymd", strtotime($_GET['fecha2']));
?>

<style>
    html { scroll-behavior: smooth; }
    .text-end { text-align: right !important; }
    
    /* ESTILOS LYSTO (NARANJA) */
    .text-lysto { color: #fd7e14 !important; } /* Naranja */
    .bg-lysto { background-color: #fd7e14 !important; color: white; }
    .border-lysto { border: 2px solid #fd7e14 !important; }
    
    .btn-index { margin: 3px; font-size: 0.85rem; background-color: #34495e; border: 1px solid #555; color: white; }
    .btn-index:hover { background-color: #fd7e14; color: white; border-color: #fd7e14; }
    
    .btn-top { float: right; font-size: 0.8rem; color: #fd7e14; text-decoration: none; margin-top: 5px; }

    /* Encabezado de Tienda */
    .store-header {
        background-color: #444;
        color: #fff;
        padding: 10px;
        margin-top: 40px;
        margin-bottom: 5px;
        border-radius: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        scroll-margin-top: 60px;
    }
    .store-title { font-size: 1.3rem; margin: 0; }
    
    /* Índice Box */
    .index-box { background: #2c3e50; padding: 15px; border-radius: 8px; margin-bottom: 30px; }
</style>

<center>
    <h1>Reporte Facturación <span class="text-lysto">Lysto</span></h1>
    <h4>Desde <?= $fecha_titulo1 ?> Hasta <?= $fecha_titulo2 ?></h4>
</center>

<div class="container-fluid">

    <?php
    $indice_links = [];
    $contenido_tablas = "";
    $gran_total_lysto = 0;

    // Bucle empieza en 1 para saltar Previa Shop
    for ($e = 1; $e < count($sedes_ar); $e++) {

        $sede = $sedes_ar[$e];
        $anchor_id = "sede_" . $e;

        // 1. Obtener Datos (Usando funcion de Lysto)
        $res = getCobros_Lysto($sede, $fecha1, $fecha2);

        // 2. Si hay datos, procesar
        if (!empty($res) && is_array($res)) {
            
            $count = count($res);
            // Agregar al índice
            $indice_links[] = "<a href='#$anchor_id' class='btn btn-sm btn-index'>$sede <span class='badge bg-light text-dark'>$count</span></a>";
            
            $subtotal_sede = 0;
            
            // Iniciar Buffer
            ob_start();
    ?>
            <div id="<?= $anchor_id ?>" class="store-header">
                <h3 class="store-title"><?= $sede ?></h3>
                <span class="badge bg-lysto">Lysto</span>
            </div>

            <div class="table-responsive">
                <table class='table table-dark table-striped table-hover table-sm'>
                    <thead>
                        <tr>
                            <th scope='col'>N°</th>
                            <th scope='col'>Fecha</th>
                            <th scope='col'>Factura</th>
                            <th scope='col'>Cobro</th>
                            <th scope='col'>CI / RIF</th>
                            <th scope='col'>Cliente</th>
                            <th scope='col' class="text-end">Monto Factura</th>
                            <th scope='col' class="text-end">Monto Lysto</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 1;
                    for ($i = 0; $i < count($res); $i++) {

                        $cob_num  = $res[$i]['cob_num'];
                        $fecha    = "";
                        if(isset($res[$i]['fec_cob'])) {
                             $fecha = ($res[$i]['fec_cob'] instanceof DateTime) ? $res[$i]['fec_cob']->format('d-m-Y') : $res[$i]['fec_cob'];
                        }
                        
                        $doc_num  = $res[$i]['doc_num'];
                        $mont_doc = $res[$i]['mont_doc'];
                        $tot_neto = $res[$i]['tot_neto'];
                        $co_cli   = $res[$i]['co_cli'];
                        $cli_des  = $res[$i]['cli_des'];

                        $subtotal_sede += $mont_doc;
                    ?>
                        <tr>
                            <th scope='row'><?= $n ?></th>
                            <td><?= $fecha ?></td>
                            <td><?= $doc_num ?></td>
                            <td><?= $cob_num ?></td>
                            <td><?= $co_cli ?></td>
                            <td><?= $cli_des ?></td>
                            <td class="text-end text-muted"><?= number_format($tot_neto, 2, ',', '.') ?></td>
                            <td class="text-end fw-bold text-lysto"><?= number_format($mont_doc, 2, ',', '.') ?></td>
                        </tr>
                    <?php
                        $n++;
                    } // Fin for filas
                    
                    $gran_total_lysto += $subtotal_sede;
                    ?>
                    
                    <tr class="table-secondary fw-bold">
                        <td colspan="7" class="text-end">Total Lysto <?= $sede ?>:</td>
                        <td class="text-end"><?= number_format($subtotal_sede, 2, ',', '.') ?></td>
                    </tr>
                    </tbody>
                </table>
                <a href="#top-index" class="btn-top">⬆ Volver al Inicio</a>
                <div style="clear:both; margin-bottom: 20px;"></div>
            </div>

    <?php
            $contenido_tablas .= ob_get_clean();
        } // Fin if empty
    } // Fin for sedes
    ?>

    <?php if (!empty($indice_links)): ?>
        <div id="top-index" class="index-box">
            <h5 class="text-white border-bottom pb-2">Tiendas con Movimientos Lysto (Clic para ir):</h5>
            <div class="d-flex flex-wrap">
                <?php foreach ($indice_links as $link) echo $link; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center mt-4">No se encontraron movimientos de Lysto en este rango de fechas.</div>
    <?php endif; ?>

    <?= $contenido_tablas ?>

    <?php if ($gran_total_lysto > 0): ?>
        <div class="card bg-dark text-white text-center mt-4 mb-5 border-lysto">
            <div class="card-body">
                <h2>Total Global Lysto</h2>
                <h1 class="text-lysto"><?= number_format($gran_total_lysto, 2, ',', '.') ?></h1>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php include '../../includes/footer.php'; ?>