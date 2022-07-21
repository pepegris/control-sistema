<?php
require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

if ($_POST) {


  $linea = $_POST['linea'];
  for ($i = 0; $i < 20; $i += 1) {
    $sedes[] = $_POST[$i];
  }
  var_dump($sedes);
  echo $sedes[1];
?>


  <table class="table table-dark table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope='col'>Codigo</th>
        <th scope='col'>Linea</th>
        <th scope='col'>Descripcion</th>
        <th scope='col'>Talla</th>
        <th scope='col'>Color</th>
        <th scope='col'>Total Vendido</th>
        <th scope='col'>Precio Bs</th>
        <th scope='col'>Ref</th>
        <?php

        for ($i = 0; $i < count($sedes); $i++) {

          $sede = $sedes[$i];

        ?>
          <th scope='col'><?= $sede ?></th>
        <?php } ?>

      </tr>
    </thead>
    <tbody>
      <?php

      $res1 = getArt('Previa Shop', $linea);

      for ($e = 0; $e < count($res1); $e++) {

        $co_art = $res1[$e]['co_art'];
        $co_lin = $res1[$e]['co_lin'];
        $art_des = $res1[$e]['art_des'];
        $co_cat = $res1[$e]['co_cat'];
        $co_color = $res1[$e]['co_color'];

        $stock_act = round($res1[$e]['stock_act']);

        $precio = round($res1[$e]['prec_vta1']);
        $prec_vta1 = number_format($precio, 2, ',', '.');

        $prec_vta5 = round($res1[$e]['prec_vta5']);

      ?>

        <tr>
          <th scope='row'><?= $n ?></th>
          <td><?= $co_art ?></td>
          <td><?= $co_lin ?></td>
          <td><?= $art_des ?></td>
          <td><?= $co_cat ?></td>
          <td><?= $co_color ?></td>
          <td>vendido</td>
          <td>Bs <?= $prec_vta1 ?></td>
          <td>$ <?= $prec_vta5 ?></td>
          <td><?= $stock_act ?></td>
          <?php
          for ($i = 0; $i < count($sedes); $i++) {
            $f = 1;

            if ($sedes[$f]==null) {
              $f++;
            }else {

              $res2 = getArt_stock_tiendas($sedes[$f], $linea,$co_art);
              $stock_act_tienda = round($res2[0]['stock_act']);
    
          ?>
            <td><?= $sedes[$f] ?></td>
          <?php $f++;
         } }   ?>
        </tr>




      <?php  }    ?>
    </tbody>
  </table>




<?php
} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>