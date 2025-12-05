<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/replica/replica.php';

// Validación de entrada
if (!isset($_GET['sede'])) {
    header("location: form.php");
    exit;
}
$sede = $_GET['sede'];

// 1. OBTENER TODOS LOS DATOS EN UNA SOLA CONEXIÓN
$resultados = Replica_Full_Details($sede);

// 2. PREPARAR FECHAS DE COMPARACIÓN (SEMAFORO)
$now_warning = new DateTime(date("d-m-Y", strtotime("-2 day"))); // Alerta Amarilla
$now_danger  = new DateTime(date("d-m-Y", strtotime("-5 day"))); // Alerta Roja

// Función auxiliar para generar la línea HTML (Para no repetir código 4 veces)
function renderItem($titulo, $fechaObj, $warn, $dang) {
    
    if ($fechaObj == null) {
        $fechaStr = "Sin Datos / Error";
        $icon = "./img/cloud-off.svg"; // Icono gris o error
    } else {
        $fechaStr = $fechaObj->format('d-m-Y');
        
        // Lógica del Semáforo
        if ($fechaObj >= $warn) {
            $icon = "./img/cloud-check.svg"; // Verde (Al día)
        } elseif ($fechaObj >= $dang) {
            $icon = "./img/cloud-sync.svg";  // Amarillo (Retraso leve)
        } else {
            $icon = "./img/cloud-upload.svg"; // Rojo (Desactualizado)
        }
    }

    echo "
    <li class='list-group-item d-flex justify-content-between align-items-center'>
        <span>
            <b style='color:black'> $titulo </b> 
            <span style='color:#555; margin-left:10px;'>/ $fechaStr</span>
        </span> 
        <img src='$icon' alt='status'> 
    </li>";
}
?>

<link rel="stylesheet" href="../../assets/css/animations.css">
<style>
    img { width: 23px; }
    ul { margin-top: 10px; }
    @media (max-width: 900px) {
        ul li { font-size: 10px; }
        img { width: 19px; }
        ul { margin-top: 10px; }
    }
</style>

<div id="body">
    <div class="slideExpandUp">
        <ul class="list-group">
            <li class="list-group-item disabled" style="background-color:black; color:white" aria-disabled="true">
                <center><b>Detalles - <?= $sede ?> </b></center>
            </li>
            
            <?php
            // Generamos las 4 líneas usando la función auxiliar
            renderItem("FACTURA",          $resultados['factura'],  $now_warning, $now_danger);
            renderItem("COBROS",           $resultados['cobros'],   $now_warning, $now_danger);
            renderItem("ORDENES DE PAGO",  $resultados['ord_pago'], $now_warning, $now_danger);
            renderItem("MOV DE BANCO",     $resultados['mov_ban'],  $now_warning, $now_danger);
            ?>
            
        </ul>
    </div>
</div>

<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<br><br><br>

<center>
    <a href="form.php" class="btn btn-secondary">Volver al Listado</a>
</center>

<?php include '../../includes/footer.php'; ?>