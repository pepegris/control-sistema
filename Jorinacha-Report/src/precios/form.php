<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

?>

<style>
  .form-check {
    display: none;
    display: flexbox;

  }
</style>

<center>
  <h1>Reporte de Precios de Articulos</h1>
</center>

<div id="body">

  <form action="routes.php" method="POST">

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
      <!-- FORMULAIO DE FECHAS -->
      <div class="form-group">
        <label for="tasa" class="form-label ">Tasa</label>
          <input type="number" step="0.01" name="tasa" id="" required>
      </div>


      <label for="reporte" class="form-label ">Reporte</label>
      <select name="reporte" id="">

        
        <option value="precios">Valor de Stock</option>
        <option value="detal">Detallado</option>
        <option value="global">Global</option>

      </select>

      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>