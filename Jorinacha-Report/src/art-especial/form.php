<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
require '../../services/adm/art-especial/especial.php';

?>

<style>
  .form-check {
    display: none;
    display: flexbox;

  }
</style>

<center>
  <h1>Reporte de Mov Articulos</h1>
</center>

<div id="body">

  <form action="routes.php" method="POST">

    <div class="fieldset">
      <br>
      <center>
        <legend>Reporte</legend>
      </center>





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



      <div class="form-group">
      <label for="tienda" class="form-label ">Tienda</label>
            <select name="tienda" id="">

            <?php
            for ($i=0; $i < count($sedes_ar) ; $i++) { 

              $sede=Database($sedes_ar[$i]);

            
            ?>

              <option value="<?= $sede ?>"><?=  $sedes_ar[$i] ?></option>

              <?php
                    
            }
              ?>

            </select> 
      </div>

      <!-- FORMULAIO DE FECHAS -->

      <div class="form-group">
        <label for="fecha1" class="form-label " require>Desde</label>
        <input type="date" name="fecha1" id="" required>
      </div>

      <div class="form-group">
        <label for="fecha2" class="form-label " require>Hasta</label>
        <input type="date" name="fecha2" id="" required>
      </div>



      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>