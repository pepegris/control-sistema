<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

?>

<style>
  .form-check {
    display: flexbox;

  }
</style>

<div id="body">
  <form action="tasa_post.php" method="POST">

    <div class="fieldset">
      <br>
      <center>
        <legend>Reporte</legend>
      </center>
      <label for="linea" class="form-label ">Sedes</label>
      <div class="form-check">
        <?php
        $res1 = getTiendas();
        $i = 5;
        while ($row1= mysqli_fetch_array($res1)) {

          $sede = $row1['sedes_nom'];
          if ($sede=='Sede Boleita') {
            $sede = 'Previa Shop';
          }
          $i += 10;
        ?>

          <input class="form-check-input" type="checkbox" value="<?= $i ?>" id="<?= $sede ?>" checked>
          <label class="form-check-label" for="<?= $sede ?>">
            <?= $sede ?>
          </label>

        <?php } ?>

      </div>


<?php
$res2= getCo_lin();
echo $res2;
var_dump($res2);
?>
      <div class="form-group">
        <label for="linea" class="form-label ">Linea</label>
        <select name="sedes_nom" id="">
          <option value="todos">Todas</option>

          <?php

            $res2= getCo_lin();
            echo $res2;
            var_dump($res2);
            foreach ($res2 as $key => $value) {
              echo $value;
            }
          
           
         /*   while ($row2 = sqlsrv_fetch_array(getCo_lin())) {

            $co_lin = $row2[co_lin]; */

          ?>
            <!-- <option value="<?= $co_lin ?>"><?= $co_lin ?></option> -->

          <?php /* } */ ?> 

        </select>
      </div>

      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>