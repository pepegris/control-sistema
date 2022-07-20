<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

?>

<div id="body">
  <form action="tasa_post.php" method="POST">

    <div class="fieldset">
      <br>
      <center>
        <legend>Reporte</legend>
      </center>

      <div class="form-group">
      <label for="sede" class="form-label ">Sedes</label>
      <?php 
          
          $res1 =getTiendas();
          $i=5;
          while ($row1=mysqli_fetch_array($res1)) {
              
              $sede=$row1['sedes_nom'];
              $i+=10;
              
          ?>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name='<?=$sede?>' value="<?=$i?>"  >
            <label class="form-check-label" for="<?=$sede?>">
            <?=$sede?>
            </label>
            </div>

        <?php } ?>
      </div>
      
      <div class="form-group">
        <label for="linea" class="form-label ">Linea</label>
        <select name="sedes_nom" id="">
          <option value="todos">Todas</option>

          <?php

          
            $res2 =getCo_lin();
          while ($row2 = mysqli_fetch_array($res2)) {

            $co_lin= $row2['co_lin'];

          ?>
            <option value="<?= $co_lin?>"><?= $co_lin?></option>

          <?php } ?>

        </select>
      </div>

      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>