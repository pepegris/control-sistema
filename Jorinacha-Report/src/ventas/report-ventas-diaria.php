<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

if ($_GET) {

  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha_titulo = date("d/m/Y", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));




?>
  <style>
    form,
    td {
      font-size: 12px;
    }
  </style>

  
  

  <center>
    <h1>Ventas Diarias <?= $fecha_titulo ?></h1>
  </center>
  <table class="table table-dark table-striped" id="tblData">
    <thead>



      <tr>
        <th scope="col">Cod</th>
        <th scope='col'>Empresa</th>
        
        <th scope='col'>Ventas</th>
        <th scope='col'>Pares</th>

        <th scope='col'>Devol</th>
        <th scope='col'>Pares Dev</th>

        <th scope='col'>Depositos</th>
        <th scope='col'>Pagos</th>

        <th scope='col'>Efectivo</th>
        <th scope='col'>Tarjeta</th>

        <th scope='col'>Estatus Caja</th>
      </tr>

    </thead>
    <tbody>

      <?php

      for ($i = 1; $i < count($sedes_ar); $i++) {

        $cod = Cliente($sedes_ar[$i]);

        $factura = getFactura($sedes_ar[$i], $fecha1, null);
        var_dump($factura);
        $total_art_factura =  number_format($factura['total_art'], 0, ',', '.');
        $tot_neto_factura = number_format($factura['tot_neto'], 2, ',', '.');

        $dev_cli = getDev_cli($sedes_ar[$i], $fecha1, null);
        $total_art_dev_cli = number_format( $dev_cli['total_art'], 0, ',', '.');
        $tot_neto_dev_cli = number_format($dev_cli['tot_neto'], 2, ',', '.');

        $dep_caj = getDep_caj($sedes_ar[$i], $fecha1, null);
        $total_efec_dep_caj = number_format($dep_caj['total_efec'], 2, ',', '.');
        $total_tarj_dep_caj = number_format($dep_caj['total_tarj'], 2, ',', '.');

        $mov_ban = getMov_ban($sedes_ar[$i], $fecha1, null);
        $monto_h_mov_ban = number_format($mov_ban['monto_h'], 2, ',', '.');

        $ord_pago = getOrd_pago($sedes_ar[$i], $fecha1, null);
        $monto_ord_pago = number_format($ord_pago['monto'], 2, ',', '.');

        $tasas = getTasas($sedes_ar[$i], $fecha1, null);
        $tasa_v_tasas = number_format($tasas['tasa_v'], 2, ',', '.') 
      ?>
      <tr>

        <td><?= $cod   ?></td>
        <td><?= $sedes_ar[$i]  ?></td>

        <td><?= $tot_neto_factura  ?></td>
        <td><?= $total_art_factura - $total_art_dev_cli   ?></td>

        <td><?= $tot_neto_dev_cli  ?></td>
        <td><?= $total_art_dev_cli  ?></td>

        <td><?= $total_efec_dep_caj  ?></td>
        <td><?= $total_tarj_dep_caj  ?></td>

      <?php

      if ($monto_h_mov_ban == $tot_neto_factura ) {
        echo "<i class='lni lni-checkmark-circle'></i>";
      }else {
        echo "<i class='lni lni-cross-circle'></i>";
      }

      echo "</tr>";


      }

      ?>



      <tr>
        <td><b>TOTAL</b></td>

      </tr>

    </tbody>


  </table>


<?php
} else {
  header("location: form.php");
}



include '../../includes/footer.php'; ?>