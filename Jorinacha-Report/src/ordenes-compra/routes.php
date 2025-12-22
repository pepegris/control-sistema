<?php
// routes.php
require '../../includes/log.php';
include '../../includes/loading-ordenes-compras.php'; // Incluimos la parte visual
include '../../services/adm/ordenes-compra/ordenes-compra.php'; // Incluimos la lógica de BD
include '../../services/mysql.php';

// INICIAR BUFFER DE SALIDA PARA MOSTRAR PROGRESO EN TIEMPO REAL
if (ob_get_level() == 0) ob_start();

// Aumentamos tiempo de ejecución (5 minutos) por si la VPN es lenta
ini_set('max_execution_time', 300); 

if (isset($_POST['tienda'])) {

    $tienda = $_POST['tienda'];
    $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
    $corregir = isset($_POST['corregir']) ? $_POST['corregir'] : '';

    // Paso 1: Buscar facturas pendientes en la central (PREVIA_A)
    $Factura_Ordenes = Factura_Ordenes($tienda, $fecha1, $corregir);

    // Validación estricta: Si no hay datos, detenemos todo.
    if (!is_array($Factura_Ordenes) || count($Factura_Ordenes) === 0) {
        echo "<script>document.getElementById('spinner').style.display = 'none';</script>";
        echo "<center><h3 style='color:#ff5555;'>No hay Información que Importar</h3></center>";
        echo "<center><a href='form.php' class='btn btn-danger'>Volver</a></center>";
        exit;
    }    

    // Paso 2: Recorrer facturas encontradas
    foreach ($Factura_Ordenes as $factura) {

        $ordenes_fact_num = $factura['fact_num'];
        $ordenes_contrib = $factura['contrib'];
        $ordenes_saldo = $factura['saldo'];
        $ordenes_tot_bruto = $factura['tot_bruto'];
        $ordenes_tot_neto = $factura['tot_neto'];
        $ordenes_iva = $factura['iva'];

        // Crear Cabecera de Orden (La función decide si es Remoto o Local)
        $orden = Ordenes_Compra($tienda, $ordenes_fact_num, $ordenes_contrib, $ordenes_saldo, $ordenes_tot_bruto, $ordenes_tot_neto, $ordenes_iva);
        
        // Obtener Renglones de la central
        $Reng_Factura = Reng_Factura($tienda, $fecha1, $ordenes_fact_num);
        
        $reng_orden = false; // Flag para saber si se insertaron items

        if (is_array($Reng_Factura)) {
            foreach ($Reng_Factura as $renglon) {
                
                $rf_fact_num = $renglon['fact_num'];
                $rf_reng_num = $renglon['reng_num'];
                $rf_co_art = $renglon['co_art'];
                $rf_total_art = $renglon['total_art'];
                $rf_prec_vta = $renglon['prec_vta'];
                $rf_reng_neto = $renglon['reng_neto'];
                $rf_cos_pro_un = $renglon['cos_pro_un'];
                $rf_ult_cos_un = $renglon['ult_cos_un'];
                $rf_ult_cos_om = $renglon['ult_cos_om'];
                $rf_cos_pro_om = $renglon['cos_pro_om'];

                // Verificar si ya existe en destino
                $existe = Con_Reng_Ordenes($tienda, $rf_fact_num, $rf_reng_num);

                // Insertar Renglón (La función decide si es Remoto o Local)
                $insertado = Reng_Ordenes($tienda, $rf_fact_num, $rf_reng_num, $rf_co_art, $rf_total_art, $rf_prec_vta, $rf_reng_neto, $rf_cos_pro_un, $rf_ult_cos_un, $rf_ult_cos_om, $rf_cos_pro_om);
                
                if($insertado) $reng_orden = true;

                // Verificar inserción para mostrar mensaje
                $verificacion = Con_Reng_Ordenes($tienda, $rf_fact_num, $rf_reng_num);

                // --- FEEDBACK VISUAL ---
                if (!$verificacion) {
                    echo "<h3 style='color:#ff5555; font-size:16px;'>Error: Articulo $rf_co_art - Orden 10$rf_fact_num Renglon #$rf_reng_num</h3>";
                } elseif ($corregir == '' && !$existe) {
                    echo "<h3 style='color:#00ff99; font-size:16px;'>Articulo Creado $rf_co_art - Orden 10$rf_fact_num Renglon #$rf_reng_num</h3>";
                }

                // Forzar el scroll hacia abajo y actualizar pantalla
                echo "<script>window.scrollTo(0,document.body.scrollHeight);</script>";
                ob_flush();
                flush();
            }
        }

        // Paso 3: Actualizar status en la central si todo salió bien
        if ($corregir == 'IMPORTADO') {
            $importado = Up_Factura_Ordenes($tienda, $fecha1, $ordenes_fact_num, $orden, $reng_orden);
            echo "<h3 style='color:#eee; border-top:1px solid #555; padding-top:10px; margin-top:10px;'>$importado</h3><br>";
            
            echo "<script>window.scrollTo(0,document.body.scrollHeight);</script>";
            ob_flush();
            flush();
        }
    }

    // FINALIZAR PROCESO VISUALMENTE
    echo "<script>
        document.getElementById('spinner').style.display = 'none'; // Ocultar spinner
        document.querySelector('h1').innerText = 'PROCESO FINALIZADO'; // Cambiar titulo
    </script>";

    echo "<center><br><a href='form.php' class='btn btn-success btn-lg'>Volver al Inicio</a></center>";
    
    // Cerrar divs abiertos en loading.php
    echo "</div></body>";

} else {
    header('Location: form.php');
    exit;
}
?>