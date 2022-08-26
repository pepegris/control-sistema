<?php
require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';


$ordenes = $_POST['ordenes'];
$fecha1 = $_POST['fecha1'];
$fecha2 = $_POST['fecha2'];

$Day = date("d", strtotime($fecha2));
$Month = date("m", strtotime($fecha2));
$Year = date("Y", strtotime($fecha2));
echo $fecha = $Day  + $Month + $Year;
for ($i = 0; $i < 20; $i += 1) {
    $sedes[] = $_POST[$i];
}

?>
<table class="table table-dark table-striped" id="tblData">
    <thead>
        <tr>
            <th>Fecha</th>
            <?php
            $e = 1;
            for ($i = 0; $i <= $Day; $i++) {

                echo "<th>$e/$Month/$Year</th>";

                $e++;
            }

            ?>

        </tr>
        <tr>
            <th>Tasa</th>
            <?php
            $e = 1;
            for ($i = 0; $i <= $Day; $i++) {

                echo "<th></th>";

                $e++;
            }
            ?>
        </tr>
        <tr>
            <th>Tienda</th>
            <?php
            $e = 1;
            for ($i = 0; $i <= $Day; $i++) {

                echo "<th>Bs</th>";

                $e++;
            }

            ?>
        </tr>
    </thead>

    <tbody>

        <?php

        $e = 1;
        for ($i = 0; $i < count($sedes); $i++) {

            if ($sedes[$e] != null) {

                $sede = $sedes[$e];

        ?>
                <tr>
                    <td scope='col'><?= $sede ?></td>

                    <?php

                            for ($i = 0; $i <= $Day; $i++) {

                                $fecha = $e + $Month + $Year;
                                $res0 = getOrdenes_Pag($sedes[$f], $fecha);
                                $monto = $res0['monto'];
                                # SUMANDO TIENDA
                                $total_monto[$sedes[$f]] +=$monto;


                    ?>
                                <td scope='col'><?= $monto ?></td>

                    <?php


                            }
                        }

                        $e++;
                    }
                    ?>
                </tr>
 


    </tbody>

</table>