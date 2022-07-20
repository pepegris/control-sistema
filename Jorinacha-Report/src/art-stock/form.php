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

      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault">
          Default checkbox
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
        <label class="form-check-label" for="flexCheckChecked">
          Checked checkbox
        </label>
      </div>



      <div class="form-group">
        <label for="linea" class="form-label ">Linea</label>
        <select name="sedes_nom" id="">
          <option value="todos">Todas</option>

          <?php


          $res2 = getCo_lin();
          var_dump($res2);
          while ($row2 = sqlsrv_fetch_array($res2)) {

            $co_lin = $row2 ^ [co_lin];

          ?>
            <option value="<?= $co_lin ?>"><?= $co_lin ?></option>

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