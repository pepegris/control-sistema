<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

if ($_GET) {

  $fecha_titulo = date("d/m/Y", strtotime($_GET['fecha1']));
  $fecha_titulo2 = date("d/m/Y", strtotime($_GET['fecha2']));
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));




?>

  <style>
    img {


      width: 28px;
    }
  </style>


  <link rel='stylesheet' href='responm.css'>




  <center>
    <h1>Facturas <?= $fecha_titulo ?> - <?= $fecha_titulo2 ?></h1>
  </center>


  <table class="table table-dark table-striped" id="tblData">
    <thead>



      <tr>
        <th scope='col'>Fecha</th>

        <th scope="col">Cod</th>
        <th scope='col'>Empresa</th>

        <th scope='col'>Documen</th>

        <th scope='col'>Num Fact</th>
        <th scope='col'>Monto</th>

        <th scope='col'>Num Cob</th>
        <th scope='col'>Tipo de Cob</th>
        <th scope='col'>Monto</th>

        <th scope='col'>Cod Caja</th>
        <th scope='col'>Caja</th>

        
      </tr>

    </thead>
    <tbody>

      <?php



      for ($i = 1; $i < count($sedes_ar); $i++) {
        
        $cod = Cliente($sedes_ar[$i]);

        $sede = $sedes_ar[$i];

        /* CONSULTAS */

        $res_factura = getFacturaDetalles($sedes_ar[$i], $fecha1, $fecha2);


        for ($x = 0; $x < count($res_factura); $x++) {



          $tp_doc_cob = $res_factura[$x]['tp_doc_cob'];
          $doc_num = $res_factura[$x]['FACTURA'];
          $neto = $res_factura[$x]['neto'];

          $cob_num = $res_factura[$x]['COBROS'];
          $fec_cob = $res_factura[$x]['fec_cob'];
          $fecha = $fec_cob->format("d-m-Y");

          $tip_cob = $res_factura[$x]['tip_cob'];
          $mont_doc = $res_factura[$x]['mont_doc'];
          $cod_caja = $res_factura[$x]['cod_caja'];
          $des_caja = $res_factura[$x]['des_caja'];


      ?>
          <tr>

            <td><?= $fecha   ?></td>

            <td><?= $cod   ?></td>
            <td><?= $sede ?></td>

            <td><?= $tp_doc_cob ?></td>

            <td><?= $doc_num    ?></td>
            <td><?= $neto  ?></td>

            <td><?= $cob_num  ?></td>
            <td><?= $tip_cob  ?></td>
            <td><?= $mont_doc  ?></td>

            <td><?= $cod_caja  ?></td>
            <td><?= $des_caja  ?></td>

            </tr>

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