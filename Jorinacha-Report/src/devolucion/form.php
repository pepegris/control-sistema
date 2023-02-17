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
  <h1>Reporte de Devoluciones</h1>
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
        <label for="tipo_dev" class="form-label">Tipo</label>
        <select name="tipo_dev" id="">

          <option value="cliente">Cliente</option>
          <option value="proveedor">Proveedor</option>

          

        </select>
      </div>
      <div class="form-group">
      <label for="doc1" class="form-label ">Nro Documento</label>
          <input type="number"  name="doc1" id="" >
      </div>
      <div class="form-group">
          <label for="doc1" class="form-label "></label>
          <input type="number"  name="doc1" id="" >
      </div>
      <div class="form-group">
      <label for="art" class="form-label ">Articulo</label>
          <input type="search" name="art" id="">
      </div>



      <!-- FORMULAIO DE FECHAS -->


      <div class="form-group">
        <label for="fecha1" class="form-label " >Desde</label>
        <input type="date" name="fecha1" id="" required>
      </div>

      <div class="form-group">
        <label for="fecha2" class="form-label " >Hasta</label>
        <input type="date" name="fecha2" id="" required>
      </div>



      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>