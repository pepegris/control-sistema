<?php
// --- ACTIVAR VISUALIZACIÓN DE ERRORES ---
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ----------------------------------------

ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/replica/replica.php';

?>
<link rel="stylesheet" href="../../assets/css/animations.css">
<style>
    img {
        width: 23px;
    }

    ul {
        margin-top: 10px;
    }

    @media (max-width: 900px) {
        ul li {
            font-size: 10px;
        }

        img {
            width: 19px;
        }

        ul {
            margin-top: 10px;
        }
    }
</style>

<?php

if (isset($_GET['sede'])) {
    $sede = $_GET['sede'];

    // --- FACTURA ---
    $res1 = Replica_detal($sede, 'factura');
    if ($res1 && isset($res1['fec_emis']) && is_object($res1['fec_emis'])) {
        $factura = $res1['fec_emis']->format('d-m-Y');
    } else {
        $factura = "Error/Null"; // Para ver si falla aquí
    }

    // --- COBROS ---
    $res2 = Replica_detal($sede, 'cobros');
    if ($res2 && isset($res2['fec_emis']) && is_object($res2['fec_emis'])) {
        $cobros = $res2['fec_emis']->format('d-m-Y');
    } else {
        $cobros = "Error/Null";
    }

    // --- ORD PAGO ---
    $res3 = Replica_detal($sede, 'ord_pago');
    if ($res3 && isset($res3['fec_emis']) && is_object($res3['fec_emis'])) {
        $ord_pago = $res3['fec_emis']->format('d-m-Y');
    } else {
        $ord_pago = "Error/Null";
    }

    // --- MOV BAN ---
    $res4 = Replica_detal($sede, 'mov_ban');
    if ($res4 && isset($res4['fec_emis']) && is_object($res4['fec_emis'])) {
        $mov_ban = $res4['fec_emis']->format('d-m-Y');
    } else {
        $mov_ban = "Error/Null";
    }

} else {
    header("location: form.php");
    exit();
}
?>


<div id="body">

    <div class="slideExpandUp">
        <ul class="list-group">
            <li class="list-group-item disabled" style="background-color:black" aria-disabled="true">
                <center><b>Detalles - <?= $sede ?> </b></center>

            </li>
            <?php

            echo "<li class='list-group-item'><span><b style='color:black'> FACTURA </b> /  $factura</span> <img src='./img/cloud-check.svg' alt=''> </li>";

            echo "<li class='list-group-item'><span><b style='color:black'> COBROS </b> /  $cobros</span> <img src='./img/cloud-check.svg' alt=''> </li>";

            echo "<li class='list-group-item'><span><b style='color:black'> ORDENES DE PAGOS </b> /  $ord_pago</span> <img src='./img/cloud-check.svg' alt=''> </li>";

            echo "<li class='list-group-item'><span><b style='color:black'> MOV DE BANCO </b> /  $mov_ban</span> <img src='./img/cloud-check.svg' alt=''> </li>";

            ?>
        </ul>

    </div>

</div>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<br><br><br>

<?php include '../../includes/footer.php'; ?>