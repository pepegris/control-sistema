<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/inv/inv.php';

if ($_GET) {

  $database = $_GET['tienda'];
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));



?>



  <link rel='stylesheet' href='responm.css'>




  <center>
    <h1>Valor del Inventario <?=$database?></h1>
  </center>



  <table class="table table-dark table-striped" id="tblData">
    <thead>

      <tr>
        <th scope="col">Marca</th>
        <th scope='col'>Stock Teorico</th>

        <th scope='col'>Total Costo Teorico</th>
        <th scope='col'>Total Precio Teorico</th>

        <th scope='col'>Stock Real</th>

        <th scope='col'>Total Costo Real</th>
        <th scope='col'>Total Precio Real</th>
      </tr>

    </thead>
    <tbody>

      <?php

      for ($i = 0; $i < count($marcas); $i++) {

       $marca=getLin_art("$marcas[$i]");

       $teorico=getInv_fis_teorico("$marcas[$i]","$database");
       $stock_teor=$teorico['stock_teor'];
       $costo_teor=$teorico['costo'];
       $precio_teor=$teorico['precio'];

       $real=getInv_fis("$marcas[$i]","$database");
       $stock_real=$real['STOCK_ACTUAL'];
       $costo_real=$real['COSTO'];
       $precio_real=$real['PRECIO'];
       


      ?>
        <tr>

          <td><?= $marca['lin_des'] ?>-<?= $marcas[$i]?></td>
          <td><?= $stock_teor  ?></td>


          <td><?= $costo_teor    ?></td>
          <td><?= $precio_teor ?></td>

          <td><?= $stock_real   ?></td>
          <td><?= $costo_real  ?></td>

          <td><?= $precio_real ?></td>


        </tr>

        <?php


        }
        ?>




        <tr>
          <td colspan="1">
            <h3>Totales</h3>
          </td>



          <td><b>0</b></td>


          <td></td>

        </tr>

    </tbody>


  </table>


<?php
} else {
  header("location: form.php");
}



include '../../includes/footer.php'; ?>