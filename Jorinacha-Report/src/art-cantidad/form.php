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
  <form action="prueba.php" method="POST">

    <div class="fieldset">
      <br>
      <center>
        <legend>Reporte</legend>
      </center>


      <div class="form-check">
        <?php
        $res1 = getTiendas();
        $i = 0;
        while ($row1 = mysqli_fetch_array($res1)) {

          $sede = $row1['sedes_nom'];
          if ($sede == 'Sede Boleita') {
            $sede = 'Previa Shop';
          }

        ?>

          <input class="form-check-input" type="checkbox" value="<?= $sede ?>" name="<?= $i ?>" checked>
          <label class="form-check-label" for="<?= $sede ?>">
            <?= $sede ?>
          </label>

        <?php $i++;
        } ?>

      </div>



      <div class="form-group">
        <label for="linea" class="form-label ">Linea</label>
        <select name="linea" id="">
          <!--           <option value="todos">Todas</option> -->

          <?php


          $res2 = getLin_art_all();

          for ($i = 0; $i < count($res2); $i++) {

            $lin_des = utf8_encode("$res2[$i]['lin_des']")
          ?>
            <option value="<?= $res2[$i]["co_lin"] ?>"><?= $res2[$i]['lin_des'] ?></option>

          <?php   }  ?>

        </select>
      </div>
      <!-- FORMULAIO DE cantidades -->
      <div class="form-group">
        <label for="art" class="form-label ">Articulo</label>
        <input type="text" name="art" id="">
      </div>

      <div class="form-group">
        <label for="number" class="form-label ">Cantidad</label>
        <input type="number" name="number" id="" require>
      </div>

      <div class="form-group">
        <label for="condicional" class="form-label ">Condicional</label>
        <select name="linea" id="">

        <option value="menor">Menor</option>
        <option value="mayor">Mayor</option>

        </select>
      </div>
      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>