<?php
require "../../includes/log.php";
include '../../includes/header.php';


$ordenes = $_POST['ordenes'];
$fecha1 = $_POST['fecha1'];
$fecha2 = $_POST['fecha2'];

echo $fecha2->format('Y-m-d');
?>
<table class="table table-dark table-striped" id="tblData">
<thead>
    <tr>
        <th>Fecha</th>

    </tr>
    <tr>
        <th>Tasa</th>
    </tr>
    <tr>
        <th>Tienda</th>
    </tr>
</thead>

</table>