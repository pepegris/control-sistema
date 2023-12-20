<?php
require '../../includes/log.php';
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/ordenes-compra/ordenes-compra.php';
?>

<style>
  .form-check {
    display: none;
    display: flexbox;

  }
</style>

<center>
  <h1>Crear Ordenes de Compras</h1>
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
      
      for ($i=1; $i < count($sedes_ar) ; $i++) { 

        $tienda=$sedes_ar[$i];

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
      
      <label for="corregir" class="form-label ">Corregir</label>
      <select name="corregir" id="">

        <option value="IMPORTADO">No</option>
        <option value="error">Si</option>



      </select> 
      

      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>

     
      

      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>