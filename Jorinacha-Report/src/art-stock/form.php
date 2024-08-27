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
  <h1>Reporte de Fallas</h1>
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
          <!--           <option value="todos">Todas las marcas</option> -->

          <?php


          $res2 = getLin_art_all();

          for ($i = 0; $i < count($res2); $i++) {

            $lin_des = utf8_encode("$res2[$i]['lin_des']")
          ?>
            
            <option value="<?= $res2[$i]["co_lin"] ?>"><?= $res2[$i]['lin_des'] ?> - <?= $res2[$i]["co_lin"] ?></option>

          <?php   }  ?>

        </select>
      </div>

      <div class="form-group">
        <label for="fecha1" class="form-label " require>Desde</label>
        <input type="date" name="fecha1" id="">
      </div>

      <div class="form-group">
        <label for="fecha2" class="form-label " require>Hasta</label>
        <input type="date" name="fecha2" id="">
      </div>

      <label for="pedidos" class="form-label ">Pedidos</label>
      <select name="pedidos" id="">

        <option value="sin">Sin Pedidos</option>
        <option value="con">Con Pedidos</option>
        <option value="fallas">Fallas</option>
        <option value="des">Articulos Despachados</option>


      </select>

<!--       <label for="almacen" class="form-label ">Almacen</label>
      <select name="almacen" id="">

        <option value="solo">Solo con Almacen</option>
        <option value="todos">Todos</option>

      </select>
 -->



      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>