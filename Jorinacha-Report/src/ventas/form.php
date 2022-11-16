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
  <h1>Reporte de Ventas</h1>
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


      <!-- FORMULAIO DE FECHAS -->

      <div class="form-group">
        <label for="fecha1" class="form-label " require>Desde</label>
        <input type="date" name="fecha1" id="" required>
      </div>

      <div class="form-group">
        <label for="fecha2" class="form-label " require>Hasta</label>
        <input type="date" name="fecha2" id="" >
      </div>


      <label for="reporte" class="form-label ">Reporte</label>
      <select name="reporte" id="">

        
        <option value="diario">Ventas Diarias</option>
        <option value="acumulado">Ventas Acumuladas</option>
        <!-- <option value="ventas">Ventas Tiendas</option> -->

      </select> 

      <label for="divisa" class="form-label ">Divisa</label>
      <select name="divisa" id="">

        
        <option value="bs">Bolivares</option>
        <option value="dl">Dolares</option>

      </select> 

      <div class="form-group">
        <label for="pass" class="form-label ">Pass</label>
          <input type="password" name="pass" id="">
      </div>

      


      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>