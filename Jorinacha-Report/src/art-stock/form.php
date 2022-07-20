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
  <form action="report-art-stock.php" method="POST">

    <div class="fieldset">
      <br>
      <center>
        <legend>Reporte</legend>
      </center>
      <label for="sedes" class="form-label ">Sedes</label>
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
          <label class="form-check-label" for="sedes">
            <?= $sede ?>
          </label>

        <?php } ?>

      </div>



      <div class="form-group">
        <label for="linea" class="form-label ">Linea</label>
        <select name="linea" id="">
          <option value="todos">Todas</option>

          <?php


         $res2=getLin_art(); 
           
         for ($i=0; $i < count($res2); $i++) { 
 
          $lin_des=utf8_encode("$res2[$i]['lin_des']")
          ?>
             <option value="<?= $res2[$i]["co_lin"] ?>"><?= $res2[$i]['lin_des'] ?></option>  

          <?php   }  ?> 

        </select>
      </div>

      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>