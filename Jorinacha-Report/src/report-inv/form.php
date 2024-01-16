<?php
require '../../includes/log.php';
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/inv/inv.php';
?>

<style>
  .form-check {
    display: none;
    display: flexbox;

  }
</style>

<center>
  <h1>Valor del Inventario</h1>
</center>

<div id="body">

  <form action="routes.php" method="POST">

    <div class="fieldset">
      <br>
      <center>
        <legend>Reporte</legend>
      </center>




      <!-- FORMULAIO DE FECHAS -->

      <label for="tienda" class="form-label ">Tienda</label>
      <select name="tienda" id="">

      <?php
      
      for ($i=0; $i < count($sedes_tiendas) ; $i++) { 

        $tienda=$sedes_tiendas[$i];

        ?>

        <option value="<?= $tienda ?>"><?= $tienda ?></option>


        <?php 
      }

      
      ?>
      </select> 


      <div class="form-group">
        <label for="fecha1" class="form-label " require>Fecha</label>
        <input type="date" name="fecha1" id="" required>
      </div>

      <label for="almac" class="form-label ">Almacen</label>
      <select name="almac" id="">

        <option value="1">1</option>
        <option value="BOLE">BOLE</option>
        <option value="DEVO">DEVO</option>


      </select> 
      

      <label for="reporte" class="form-label ">Reporte</label>
      <select name="reporte" id="">

        <option value="global">Global</option>
        <option value="detallado">Detallado</option>



      </select> 
      

      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>

      <center><a href="../../../../../inventariocyp/index.php"class="btn btn-warning">Ir a Reporte de Inventario</a></center>
      

      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>