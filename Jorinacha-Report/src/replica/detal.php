<?php
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

    $factura1 = Replica_detal($sede, 'factura');
    $factura=$factura1['fec_emis'];

    $cobros2 = Replica_detal($sede, 'cobros');
    $cobros=$cobros2['fec_emis'];

    $ord_pago3 = Replica_detal($sede, 'ord_pago');
    $ord_pago=$ord_pago3['fec_emis'];

    $mov_ban4 = Replica_detal($sede, 'mov_ban');
    $mov_ban=$mov_ban4['fec_emis'];

} else {
    header("location: form.php");
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