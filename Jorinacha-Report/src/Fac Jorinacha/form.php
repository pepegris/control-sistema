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
  <h1>Facturar Jorinacha</h1>
</center>

<div id="body">


  <form action="factura.php" method="POST">

    <div class="fieldset">
      <br>
      <center>
        <legend>Reporte</legend>
      </center>

      <div class="form-group">
        <label for="empresa" class="form-label ">Empresa</label>
        <select name="empresa" id="">
          <option value="Previa Shop C.A">Previa Shop C.A</option>
          <option value="Inv Jorinacha C.A">Inv Jorinacha C.A</option>
        </select>
      </div>

      <div class="form-group">
        <label for="factura" class="form-label ">Num Factura:</label>
        <input type="number" name="factura" id="">
      
      </div>

      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>