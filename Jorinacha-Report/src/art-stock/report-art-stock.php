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
        <th scope='col'>Stock Actual</th>
        <th scope='col'>Precio Bs</th>
        <th scope='col'>Ref</th>
        <?php

        for ($i = 0; $i < count($sedes); $i++) {

          $sede = $sedes[$i];

        ?>
          <th scope='col'><?=$sede?></th>
        <?php } ?>

      </tr>
    </thead>
    <tbody>
      <?php

      $res1 = getArt($sedes[0], $linea);
      echo $res1[0];
      $n=1;
/*       for ($i = 0; $i < count($sedes); $i++) {

        $res1 = getArt($sedes[$i], $linea);

        for ($e = 0; $e < count($res1); $e++) {

          $co_art=$res1['co_art'];
          $co_lin=$res1['co_lin'];
          $art_des=$res1['art_des'];
          $co_cat=$res1['co_cat'];
          $co_color=$res1['co_color'];
          $stock_act=$res1['stock_act'];
          $prec_vta1=$res1['prec_vta1'];
          $prec_vta5=$res1['prec_vta5']; */

      

      ?>
      <tr>
<!--         <th scope="row"><?=$n?></th>
        <td><?=$co_art?></td>
        <td><?=$co_lin?></td>
        <td><?=$art_des?></td>
        <td><?=$co_cat?></td>
        <td><?=$co_color?></td>
        <td>vendido</td>
        <td><?=$stock_act?></td>
        <td><?=$prec_vta1?></td>
        <td><?=$prec_vta5?></td> -->
      </tr>
      <?php /* $n++; } }  */ ?>
    </tbody>
  </table>




<?php
} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>