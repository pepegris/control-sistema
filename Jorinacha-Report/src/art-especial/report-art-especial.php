<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
require '../../services/adm/art-especial/especial.php';

if (isset($_GET)) {


  $linea = $_GET['linea'];
  $tienda = $_GET['tienda'];


  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));





?>


  <center>
    <h1>Reporte </h1>
  </center>
  <table class="table table-dark table-striped" id="tblData">
    <thead>
      <tr>
        <th scope="col">N°</th>
        <th scope='col'>Codigo</th>
        <th scope='col'>Marca</th>
        <th scope='col'>Modelo</th>
        <th scope='col'>Desc</th>
        <th scope='col'>Escala</th>
        <th scope='col'>Color</th>


        <th scope='col'>Stock</th>

        <th scope='col'>Fecha Ult Venta</th>

        <th scope='col'>N° Factura Compra</th>
        <th scope='col'>Fecha Ult Compra</th>

        <th scope='col'>N° Ajuste</th>
        <th scope='col'>Fecha Ult Ajuste</th>

      </tr>
    </thead>

    <tbody>

      <?php

      $getArt1 = getArt($tienda, $linea, 0, null);

      $n = 1;
      for ($e = 0; $e < count($getArt1); $e++) {

        $co_art = $getArt1[$e]['co_art'];
        $co_lin = $getArt1[$e]['co_lin'];
        $co_subl = $getArt1[$e]['co_subl'];
        $co_cat = $getArt1[$e]['co_cat'];
        $co_color = $getArt1[$e]['co_color'];
        $desc = $getArt1[$e]['ubicacion'];


        $stock_act = round($getArt1[$e]['stock_act']);
        
        $ventas =getReng_fac($tienda,  $co_art , $fecha1 , $fecha2);
        var_dump( $n );
        echo "<br>";
        var_dump( $ventas );
        echo "<br>";
        $total_art_ventas = $ventas[0]['total_art'];
        $fec_emis_ventas = $ventas[0]['fec_emis'];


        $compras =getReng_com($tienda,  $co_art , $fecha1 , $fecha2);
        var_dump( $compras );
        echo "<br>";
        $fact_num_compras = $compras[0]['fact_num'];
        $total_art_compras = $compras[0]['total_art'];
        $fec_emis_compras = $compras[0]['fec_emis'];


        $ajuste =getReng_ajue($tienda,  $co_art , $fecha1 , $fecha2);
        var_dump( $ajuste );
        echo "<br>";
        $ajue_num_ajuste = $ajuste[0]['ajue_num'];
        $total_art_ajuste = $ajuste[0]['total_art'];
        $fecha_ajuste = $ajuste[0]['fecha'];


      ?>

        <tr>
          <th scope='row'><?= $n ?></th>
          <td><?= $co_art ?></td>
          <td><?= $co_lin ?></td>
          <td><?= $co_subl ?></td>
          <td><?= $desc ?></td>
          <td><?= $co_cat ?></td>
          <td><?= $co_color ?></td>

          <td ><?= $stock_act ?></td>
        <?php  

        if ($ventas != null) {

          echo "<td>".$fec_emis_ventas->format('d-m-Y') ."</td>";
        } else {
          echo "<td>No se a Vendido </td>";
        }


        if ($compras != null) {

          echo "<td>$fact_num_compras </td><td>".$fec_emis_compras->format('d-m-Y') ."</td>";
        } else {
          echo "<td>No se a</td><td>Registrado Compra</td>";
        }

        if ($ajuste != null) {

          echo "<td>$ajue_num_ajuste </td><td>".$fec_emifecha_ajuste_compras->format('d-m-Y') ."</td>";
        } else {
          echo "<td>No se a</td><td>Registrado Ajuste</td>";
        }
        
        
        $n++ ?>
        </tr>




      <?php  } ?>

    </tbody>
  </table>




<?php

} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>