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
      </tr>
    </thead>
    <tbody>
      <?php 
         $res = getArt($sedes[0],$linea);
         var_dump($res);

      ?>
      <tr>
        <th scope="row">1</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
      </tr>
    </tbody>
  </table>




<?php
} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>