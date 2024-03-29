<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

if ($_GET) {

  $divisa = $_GET['divisa'];
  $fecha_titulo1 = date("d/m/Y", strtotime($_GET['fecha1']));
  $fecha_titulo2 = date("d/m/Y", strtotime($_GET['fecha2']));

  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));

  $Month = date("m", strtotime($fecha1));
  $Year = date("Y", strtotime($fecha1));


?>

  <style>
    img {


      width: 28px;
    }
  </style>


  <link rel='stylesheet' href='responm.css'>




  <center>
    <h1>Ordenes de Pago <?= $fecha_titulo1 ?> - <?= $fecha_titulo2 ?> </h1>
  </center>


  <table class="table table-dark table-striped" id="tblData">
    <thead>



      <tr>
        <th scope='col'>Fecha</th>

        <th scope="col">Cod</th>
        <th scope='col'>Empresa</th>

        <th scope='col'>Tasa</th>

        <th scope='col'>Número de Orden</th>

        <th scope='col'>Descripción</th>

        <th scope='col'>Monto Bs</th>
        <th scope='col'>Monto USD</th>




      </tr>

    </thead>
    <tbody>

      <?php



      for ($i = 1; $i < count($sedes_ar); $i++) {



        $tasa_v_bs  = 1;

        $cod = Cliente($sedes_ar[$i]);

        $sede = $sedes_ar[$i];

        /* CONSULTAS */

        $ord_pago = getOrd_pago_inf($sedes_ar[$i], $fecha1, $fecha2);



        for ($x = 0; $x < count($ord_pago); $x++) {


          $fecha_ord_pago = $ord_pago[$x]['fecha'];
          $fecha = $fecha_ord_pago->format('d-m-Y');

          $Day = date("d", strtotime($fecha));



          $fecha_2 = $Year . '/' . $Month . '/' . $Day;

          $tasas = getTasas($sede,  $fecha_2);

          

          if ($tasas != null) {
            $tasa_v_tasas = $tasas['tasa_v'];
          } else {
            $tasa_v_tasas;
          }

          $tasa_dia = number_format($tasa_v_tasas, 2, ',', '.');

          





          $num_ord_pago = $ord_pago[$x]['ord_num'];
          $descrip_ord_pago = $ord_pago[$x]['descrip'];



          /* DOLARES *//* DOLARES *//* DOLARES */


          $tasa_monto_ord_pago_usd = $ord_pago[$x]['monto'] / $tasa_v_tasas;
          $monto_ord_pago_usd = number_format($tasa_monto_ord_pago_usd, 2, ',', '.');



          /* BOLIVARES *//* BOLIVARES *//* BOLIVARES */


          $tasa_monto_ord_pago_bs = $ord_pago[$x]['monto'] / $tasa_v_bs;
          $monto_ord_pago_bs = number_format($tasa_monto_ord_pago_bs, 2, ',', '.');


          /* totales *//* BOLIVARES *//* totales */

          $total_pagos_usd += $tasa_monto_ord_pago_usd;


          /* totales *//* DOLARES *//* totales */


          $total_pagos_bs += $tasa_monto_ord_pago_bs;



      ?>
          <tr>

            <td><?= $fecha   ?></td>
            <td><?= $cod   ?></td>
            <td><?= $sede ?></td>

            <td><?= $tasa_dia ?></td>

            <td><?= $num_ord_pago    ?></td>
            <td><?= $descrip_ord_pago  ?></td>

            <td><?= $monto_ord_pago_bs  ?></td>
            <td><?= $monto_ord_pago_usd  ?></td>



        <?php
        }
      }



        ?>



          <tr>
            <td colspan="6">
              <h3>Totales</h3>
            </td>





            <td><b>Bs<?= number_format($total_pagos_bs, 2, ',', '.')  ?></b></td>
            <td><b>$<?= number_format($total_pagos_usd, 2, ',', '.')  ?></b></td>





          </tr>

    </tbody>


  </table>


<?php
} else {
  header("location: form.php");
}



include '../../includes/footer.php'; ?>