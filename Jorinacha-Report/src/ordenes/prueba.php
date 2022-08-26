<?php
require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';


echo $ordenes = $_POST['ordenes'];
echo $fecha1 = $_POST['fecha1'];
echo $fecha2 = $_POST['fecha2'];

$Day = date("d", strtotime($fecha2));
$Month = date("m", strtotime($fecha2));
$Year = date("Y", strtotime($fecha2));

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
                    $f = 1;
                    for ($i = 0; $i < count($sedes); $i++) {

                        if ($sedes[$f] != null) {
                            $g = 1;
                            for ($i = 0; $i <= $Day; $i++) {

                                $fecha = $g + $Month + $Year;
                                $res0 = getOrdenes_Pag($sedes[$f], $fecha);
                                $monto = $res0['monto'];
                                # SUMANDO TIENDA
                                $total_monto[$sedes[$f]] +=$monto;


                    ?>
                                <td scope='col'><?= $monto ?></td>

                    <?php

                                $g++;
                            }
                        }
                        $f++;
                    }
                    ?>
                </tr>
        <?php }
            $e++;
        } ?>


    </tbody>

</table>