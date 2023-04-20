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

    $res1 = Replica_detal($sede, 'factura');
    $factura1=$res1['fec_emis'];
    $factura = $factura1->format('d-m-Y');
    var_dump($res1['fec_emis']);
    echo $res1['fec_emis'];

    $res2 = Replica_detal($sede, 'cobros');
    $cobros2=$res2['fec_emis'];
    $cobros = $res1->format('d-m-Y');

    $res3 = Replica_detal($sede, 'ord_pago');
    $ord_pago3=$res3['fec_emis'];
    $ord_pago = $ord_pago3->format('d-m-Y');

    $res4 = Replica_detal($sede, 'mov_ban');
    $mov_ban4=$res4['fec_emis'];
    $mov_ban = $mov_ban4->format('d-m-Y');

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