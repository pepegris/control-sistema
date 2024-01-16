<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/inv/inv.php';

if ($_GET) {

  $database = $_GET['tienda'];
  $almac=$_GET['almac'];
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));



?>



  <link rel='stylesheet' href='responm.css'>




  <center>
    <h1>Valor del Inventario Global <?=$database?></h1>
  </center>



  <table class="table table-dark table-striped" id="tblData">
    <thead>

      <tr>
        <th scope="col">Marca</th>
        <th scope='col'>Stock Teorico</th>
        <th scope='col'>Stock Real</th>

        <th scope='col'>Total Costo Teorico</th>
        <th scope='col'>Total Costo Real</th>

        <th scope='col'>Total Precio Teorico</th>
        <th scope='col'>Total Precio Real</th>
      </tr>

    </thead>
    <tbody>

      <?php

      $marca=getLin_art("$fecha1",$database,$almac);

      var_dump(count($marca));



      for ($i = 0; $i < count($marca); $i++) {

       $marcas=$marca[$i]['co_lin'];

       $teorico=getInv_fis_teorico("$marcas","$database","$fecha1",$almac);
       $stock_teor=$teorico['stock_teor'];
       $costo_teor=$teorico['costo'];
       $precio_teor=$teorico['precio'];

       $real=getInv_fis("$marcas","$database","$fecha1");
       $stock_real=$real['STOCK_ACTUAL'];
       $costo_real=$real['COSTO'];
       $precio_real=$real['PRECIO'];
       


      ?>
        <tr>

          <td><?= $marca[$i]['lin_des']  ?> - <?= $marcas ?></td>

          <td><?= $stock_teor  ?></td>
          <td><?= $stock_real   ?></td>

          <td><?= number_format($costo_teor, 2, ',', '.')     ?></td>
          <td><?= number_format($costo_real, 2, ',', '.')   ?></td>

          <td><?= number_format($precio_teor, 2, ',', '.')   ?></td>
          <td><?= number_format($precio_real, 2, ',', '.')  ?></td>


        </tr>

        <?php

  
        $total_stock_teor+=$stock_teor;
        $total_costo_teor+=$costo_teor;
        $total_precio_teor+=$precio_teor;


        $total_stock_real+=$stock_real;
        $total_costo_real+=$costo_real;
        $total_precio_real+=$precio_real;

        }
        ?>




        <tr>
          <td colspan="1">
            <h3>Totales</h3>
          </td>



          <td><b><?= $total_stock_teor ?></b></td>
          <td><b><?= $total_stock_real ?></b></td>

          <td><b><?= number_format($total_costo_teor, 2, ',', '.') ?></b></td>
          <td><b><?= number_format($total_costo_real, 2, ',', '.') ?></b></td>

          <td><b><?= number_format($total_precio_teor, 2, ',', '.') ?></b></td>
          <td><b><?= number_format($total_precio_real, 2, ',', '.') ?></b></td>




        </tr>

    </tbody>


  </table>


<?php
} else {
  header("location: form.php");
}



include '../../includes/footer.php'; ?>