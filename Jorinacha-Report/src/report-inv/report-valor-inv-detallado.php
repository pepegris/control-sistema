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
    <h1>Valor del Inventario Detallado <?=$database?></h1>
  </center>



  <table class="table table-dark table-striped" id="tblData">
    <thead>

      <tr>

        <th scope="col">Marca</th>
        <th scope='col'>Codigo</th>
        <th scope='col'>Descripcion</th>

        <th scope='col'>Stock Teorico</th>
        <th scope='col'>Stock Real</th>
        <th scope='col'>DIF</th>


        <th scope='col'>Costo</th>
        <th scope='col'>Costo Teorico</th>
        <th scope='col'>Costo Real</th>
        <th scope='col'>DIF</th>

        <th scope='col'>Precio</th>
        <th scope='col'>Precio Teorico</th>
        <th scope='col'>Precio Real</th>
        <th scope='col'>DIF</th>


      </tr>

    </thead>
    <tbody>

      <?php

      $marca=getLin_art("$fecha1",$database,$almac);

      var_dump(count($marca));
      echo "<br>";

  


      for ($i = 0; $i < count($marca); $i++) {

       $co_lin=$marca[$i]['co_lin'];
       $lin_des=$marca[$i]['lin_des'];

       ?>

<!--        <tr>
       <th ><h4><?= $lin_des  ?> - <?= $co_lin ?></h4></th>
       </tr> -->
 
       <?php

       $teorico=getreng_stock_teorico("$co_lin","$database","$fecha1",$almac);


       for ($e = 0; $e < count($teorico); $e++) {


        $co_art=$teorico[$e]['co_art'];
        $art_des=$teorico[$e]['art_des'];

        $real=getreng_stock_real("$co_lin","$database","$fecha1","$co_art");


        $stock_teorico=$teorico[$e]['stock_teor'];
        $stock_real=$real['stock_real'];

        
        $costo=number_format($teorico[$e]['costo'], 1, ',', '.') ;
        $teorico_total_costo=$teorico[$e]['total_costo_teorico'] ;
        $real_total_costo=$real['total_costo_real'] ;

        $precio=$teorico[$e]['precio'];
        $teorico_total_precio=$teorico[$e]['total_precio_teorico'];
        $real_total_precio=$real['total_precio_real'];

        if ($stock_real==null) {
          
          $stock_real=0;

        }

        if ($real_total_costo==null) {
          
          $real_total_costo=0;

        }


        if ($real_total_precio==null) {
          
          $real_total_precio=0;

        }


        $dif_stock= $stock_real -$stock_teorico  ;
        $dif_costo= $real_total_costo-$teorico_total_costo  ;
        $dif_precio = $real_total_precio-$teorico_total_precio   ;


 
 
      ?>
        <tr>

          <td><?= $lin_des  ?> - <?= $co_lin ?></td>
          <td><?= $co_art  ?></td>
          <td><?= $art_des  ?></td>

          <td><?= $stock_teorico  ?></td>
          <td><?= $stock_real  ?></td>
          <td style="color:red"><?=  $dif_stock ?></td>

          <td><?= $costo  ?></td>
          <td><?= $teorico_total_costo  ?></td>
          <td><?= $real_total_costo  ?></td>
          <td style="color:red"><?=  number_format($dif_costo, 1, ',', '.') ?></td>

          <td><?= $precio  ?></td>
          <td><?= number_format($teorico_total_precio, 0, ',', '.')  ?></td>
          <td><?= number_format($real_total_precio, 0, ',', '.')  ?></td>
          <td style="color:red"><?= number_format($dif_precio, 0, ',', '.') ?></td>


        </tr>

        <?php

        $sub_total_stock_teorico+=$stock_teorico;
        $sub_total_stock_real+=$stock_real;
        $sub_total_stock_dif+=$dif_stock;

        $sub_total_costo_teorico+=$teorico[$e]['total_costo_teorico'];
        $sub_total_costo_real+=$real['total_costo_real'];
        $sub_total_costo_dif=$sub_total_costo_real-$sub_total_costo_teorico;

        $sub_total_precio_teorico+=$teorico_total_precio;
        $sub_total_precio_real+=$real_total_precio;
        $sub_total_precio_dif+=$dif_precio;
        
        $t_total_costo_teorico+=$teorico[$e]['total_costo_teorico'];
        $t_total_costo_real+=$real['total_costo_real'];
        

          }
          ?>


          <tr>
          <th colspan='2' ><?= $lin_des  ?> - <?= $co_lin ?> </th>
          <td ><h4>Sub Total:</h4></td>
          

          <td><b><?= $sub_total_stock_teorico  ?></b></td>
          <td><b><?= $sub_total_stock_real  ?></b></td>
          <td style="color:red"><?=  $sub_total_stock_dif ?></td>

          <td ><h4>Costo:</h4></td>
          <td><b><?= number_format($sub_total_costo_teorico, 1, ',', '.')   ?></b></td>
          <td><b><?= number_format($sub_total_costo_real, 1, ',', '.')   ?></b></td>
          <td style="color:red"><?=  number_format($sub_total_costo_dif, 1, ',', '.')  ?></td>

          <td ><h4>Precio:</h4></td>
          <td><b><?= number_format($sub_total_precio_teorico, 0, ',', '.')   ?></b></td>
          <td><b><?= number_format($sub_total_precio_real, 0, ',', '.')  ?></b></td>
          <td style="color:red"><?= number_format($sub_total_precio_dif, 0, ',', '.') ?></td>


          </tr>


        <?php
          /**********************************************/
          $t_total_stock_teorico+=$sub_total_stock_teorico;
          $t_total_stock_real+=$sub_total_stock_real;
          $t_total_stock_dif+=$sub_total_stock_dif;

          $t_total_precio_teorico+=$sub_total_precio_teorico;
          $t_total_precio_real+=$sub_total_precio_real;
          $t_total_precio_dif+=$sub_total_precio_dif;
          /**********************************************/
          $sub_total_stock_teorico=0;
          $sub_total_stock_real=0;
          $sub_total_stock_dif=0;

          $sub_total_costo_teorico=0;
          $sub_total_costo_real=0;
          $sub_total_costo_dif=0;

          $sub_total_precio_teorico=0;
          $sub_total_precio_real=0;
          $sub_total_precio_dif=0;
 
 
        }

        $t_total_costo_dif=$t_total_costo_real-$t_total_costo_teorico;

        if ($t_total_stock_dif >= 0) {
          $color='green';
        }else {
          $color='red';
        }

        if ($t_total_costo_dif >= 0) {
          $color='green';
        }else {
          $color='red';
        }

        if ($t_total_precio_dif >= 0) {
          $color='green';
        }else {
          $color='red';
        }

        ?>




<tr>

          <th colspan='2' ></th>
          <th  >Totales:</th>
          

          <td><b><?= $t_total_stock_teorico  ?></b></td>
          <td><b><?= $t_total_stock_real  ?></b></td>
          <td style="color:<?= $color ?>"><?=  $t_total_stock_dif ?></td>

          <th >Costo:</th>
          <td><b><?= number_format( $t_total_costo_teorico, 1, ',', '.')   ?></b></td>
          <td><b><?= number_format($t_total_costo_real, 1, ',', '.')   ?></b></td>
          <td style="color:<?= $color ?>"><?=  number_format($t_total_costo_dif, 1, ',', '.')  ?></td>

          <th >Precio:</th>
          <td><b><?= number_format($t_total_precio_teorico, 0, ',', '.')  ?></b></td>
          <td><b><?= number_format($t_total_precio_real, 0, ',', '.')  ?></b></td>
          <td style="color:<?= $color ?>"><?= number_format($t_total_precio_dif, 0, ',', '.') ?></td>


          </tr>
          
    </tbody>


  </table>


<?php
} else {
  header("location: form.php");
}



include '../../includes/footer.php'; ?>