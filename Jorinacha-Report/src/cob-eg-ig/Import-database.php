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
  <h1>Backups para IntegraciÓn</h1>
</center>

<div id="body">

  <form action="Import.php" method="POST">

    <div class="fieldset">


      <div class="form-group">
        <label for="scripts" class="form-label">Script</label>
        <select name="scripts" id="">

          <option value="backups">Realizar Backups de Profit Administrativo</option>
          <option value="restore">Importar Backups a Profit de Integración</option>

        </select>
      </div>

      
      <br>
      <center><button type="submit" class="btn btn-success">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>



<?php include '../../includes/footer.php'; 

?>