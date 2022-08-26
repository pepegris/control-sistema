<?php
require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';


echo $ordenes = $_POST['ordenes'];
echo $fecha1 = $_POST['fecha1'];
echo $fecha2 = $_POST['fecha2'];

$newDate = date("d", strtotime($fecha2));
echo $newDate;
for ($i = 0; $i < 20; $i += 1) {
    $sedes[] = $_POST[$i];
  }
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
            <?php

            for ($i = 0; $i < count($sedes); $i++) {

                if ($sedes[$i] != null) {

                    $sede = $sedes[$i];

            ?>
                    <th scope='col'><?= $sede ?></th>
            <?php }
            } ?>
        </tr>
    </thead>

</table>