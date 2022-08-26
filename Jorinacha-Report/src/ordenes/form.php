<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

var_dump($_POST);
$ordenes = $_POST['ordenes'];
$fecha1 = $_POST['fecha1'];
$fecha2 = $_POST['fecha2'];

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
        while ($row1= mysqli_fetch_array($res1)) {

          $sede = $row1['sedes_nom'];
          if ($sede=='Sede Boleita') {
            $sede = 'Previa Shop';
          }
          
        ?>

          <input class="form-check-input" type="checkbox" value="<?= $sede ?>" name="<?= $i ?>"  checked>
          <label class="form-check-label" for="<?= $sede ?>">
            <?= $sede ?>
          </label>

        <?php  $i ++; } ?>

      </div>


      <div class="form-group">
        <label for="ordenes" class="form-label ">Ordenes de:</label>
        <select name="ordenes" id="">

            <option value="tiendas">Tiendas</option> 
            <option value="bole">Bole</option> 

        </select>
      </div>

      <div class="form-group">
        <label for="fecha1" class="form-label ">Desde</label>
          <input type="date" name="fecha1" id="">
      </div>

      <div class="form-group">
        <label for="fecha2" class="form-label ">Hasta</label>
          <input type="date" name="fecha2" id="">
      </div>
      <br>
      <center><button type="submit"  class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>