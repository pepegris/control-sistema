<?php
require "../../includes/log.php";
include '../../includes/header.php';


echo $ordenes = $_POST['ordenes'];
echo $fecha1 = $_POST['fecha1'];
echo $fecha2 = $_POST['fecha2'];


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