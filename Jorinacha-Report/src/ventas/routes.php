<?php
require '../../includes/log.php';
include '../../includes/loading.php';
include '../../services/sqlserver.php';
include '../../services/mysql.php';

// Verificamos si se envió el formulario
if (isset($_POST['clave'])) {

    $clave = $_POST['clave'];

    if ($clave === 'N3td0s') {
        
        // Recoger variables
        $reporte = $_POST['reporte'];
        $divisa  = $_POST['divisa'];
        $linea   = $_POST['linea'];

        $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
        
        // Lógica de fecha: Si no ponen fecha2, asume que es el mismo día que fecha1
        $fecha2 = (!empty($_POST['fecha2'])) ? date("Ymd", strtotime($_POST['fecha2'])) : $fecha1;

        // --- CORRECCIÓN CRÍTICA DE SEDES ---
        // Capturamos el array directamente. Si no seleccionaron nada, enviamos array vacío.
        $array_sedes = isset($_POST['sedes']) ? $_POST['sedes'] : [];
        
        // Serializamos para pasar por URL (Formato seguro para PHP)
        $sedes = urlencode(serialize($array_sedes));

        // Inicializamos URL
        $url = "";

        // --- SISTEMA DE RUTEO (SWITCH) ---
        switch ($reporte) {
            case 'diario':
                $url = "report-ventas-diaria.php?fecha1=$fecha1&divisa=$divisa&sedes=$sedes&linea=$linea";
                break;

            case 'por':
                $url = "report-grafica.php?fecha1=$fecha1&fecha2=$fecha2&linea=$linea&divisa=$divisa&sedes=$sedes";
                break;

            case 'des':
                $url = "report-despacho-gf.php?fecha1=$fecha1&fecha2=$fecha2&linea=$linea&divisa=$divisa&sedes=$sedes";
                break;

            case 'cashea':
                $url = "report-cashea.php?fecha1=$fecha1&fecha2=$fecha2&divisa=$divisa&sedes=$sedes";
                break;

            case 'lysto': // Nuevo reporte Lysto
                $url = "report-lysto.php?fecha1=$fecha1&fecha2=$fecha2&divisa=$divisa&sedes=$sedes";
                break;

            case 'dias':
                $url = "report-ventas-diaria-todo.php?fecha1=$fecha1&fecha2=$fecha2&divisa=$divisa&sedes=$sedes&linea=$linea";
                break;

            case 'mes':
                // Lógica especial para el reporte mensual dependiendo si es marcas o todo
                if ($linea == 'todos') {
                    $base = ($divisa == 'dl') ? "report-ventas-mes-todo-dolares.php" : "report-ventas-mes-todo-bolivares.php";
                } else {
                    $base = ($divisa == 'dl') ? "report-ventas-mes-marcas-dolares.php" : "report-ventas-mes-marcas-bolivares.php";
                }
                $url = "$base?fecha1=$fecha1&fecha2=$fecha2&linea=$linea&divisa=$divisa&sedes=$sedes";
                break;

            case 'acumulado':
                if (!empty($_POST['fecha2'])) { // Validación específica para acumulado
                    $base = ($divisa == 'dl') ? "report-ventas-acumulado-dolares.php" : "report-ventas-acumulado.php";
                    $url = "$base?fecha1=$fecha1&fecha2=$fecha2&linea=$linea&divisa=$divisa&sedes=$sedes";
                } else {
                    // Si falta fecha2 en acumulado, error
                    echo "<script>alert('El reporte acumulado requiere Fecha Hasta');</script>";
                    header('refresh:0;url= form.php');
                    exit;
                }
                break;

            case 'ventas':
                $url = "report-ventas-detalle.php?fecha1=$fecha1&linea=$linea&divisa=$divisa&sedes=$sedes";
                break;

            case 'ord':
                $url = "report-ventas-ordenes.php?fecha1=$fecha1&fecha2=$fecha2&linea=$linea&divisa=$divisa&sedes=$sedes";
                break;

            case 'ordenes':
                $url = "report-ordenes.php?fecha1=$fecha1&fecha2=$fecha2&linea=$linea&divisa=$divisa&sedes=$sedes";
                break;

            case 'factura':
                $url = "report-ventas-facturas.php?fecha1=$fecha1&fecha2=$fecha2&linea=$linea&divisa=$divisa&sedes=$sedes";
                break;

            default:
                header('refresh:1;url= form.php');
                exit;
        }

        // REDIRECCIÓN FINAL
        if (!empty($url)) {
            header("refresh:1;url=$url");
        } else {
            header('refresh:1;url= form.php');
        }

    } else {
        // Clave incorrecta
        echo "<script>alert('Clave Incorrecta');</script>";
        header('refresh:0;url= form.php');
    }

} else {
    // Acceso directo sin POST
    header('refresh:1;url= form.php');
}
?>