<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

if ($_POST) {
$ordenes = $_POST['ordenes'];
$fecha1 = $_POST['fecha1'];
$fecha2 = $_POST['fecha2'];

$Day = date("d", strtotime($fecha2));
$Month = date("m", strtotime($fecha2));
$Year = date("Y", strtotime($fecha2));

for ($i = 0; $i < 20; $i += 1) {
    $sedes[] = $_POST[$i];
}

?>
  <style>
    form,
    td {
      font-size: 12px;
    }
  </style>
<center>
    <h1>Ordenes de Pago</h1>
</center>
<table class="table table-dark table-striped" id="tblData">
    <thead>
        <tr>
            <th>Fecha</th>
            <?php
            $e = 1;
            for ($i = 0; $i <= $Day; $i++) {

                if ($i >= $Day) {

                    echo "<th>Total</th>";
                } else {
                    echo "<th>$e/$Month/$Year</th>";

                    $e++;
                }
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
                    <td><?= $sede ?></td>

                    <?php

                    $y = 1;
                    for ($r = 0; $r <= $Day; $r++) {

                        if ($y < 10) {

                            $d = 0 . $y;
                        } else {
                            $d = $y;
                        }
                        $fecha =  $Year . $Month . $d;
                        $res0 = getOrdenes_Pag($sedes[$e], $fecha);
                        $monto = $res0['monto'];
                        $total_dia_monto [$fecha] += $monto;
                        # SUMANDO TIENDA
                        $total_monto [$sedes[$e]] +=  number_format($monto, 2, ',', '.') ;

                        if ($r >= $Day) {

                            #$total = $total_monto;
                            echo "<td>$total_monto [$sedes[$e]]</td>";
                        } else {
                            if ($monto == null) {
                                echo "<td>0</td>";
                            } else {
                                echo "<td>$monto</td>";
                            }
                        }
                        $y++;
                    }

                    ?>
                </tr>
        <?php
            }
            $e++;
        }

        ?>

        <tr>
            <td><b>TOTAL</b></td>
            <?php
            $y = 1;
            for ($r = 0; $r <= $Day; $r++) {
                if ($y < 10) {

                    $d = 0 . $y;
                } else {
                    $d = $y;
                }
                $fecha =  $Year . $Month . $d;

                if ($r >= $Day) {
                    $e=1;
                     for ($i=0; $i < count($total_dia_monto[$fecha]) ; $i++) { 
                        $total+=$total_monto[$i][$sedes[$e]];
                        $e++;
                    }
     
                    echo "<td>$total</td>";
                } else {
                    echo"<td>$total_dia_monto[$fecha]</td>";
                }


                $y++;
            }

            ?>
        </tr>

    </tbody>

    <?php

 
    ?>

</table>


<?php
} else {
  header("location: form.php");
}



include '../../includes/footer.php'; ?>