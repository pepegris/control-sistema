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
          
          $res =getTiendas();
          $i=5;
          while ($res=mysqli_fetch_array($res)) {
              
              $sede=$res['sedes_nom'];
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

          
            $res =getCo_lin();
          while ($row = mysqli_fetch_array($res)) {

            $co_lin= $row['co_lin'];

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