<?php
require '../../includes/log.php';
include '../../includes/header.php';
?>

<div id="body">
  <form action="tasa_post.php" method="POST">

    <div class="fieldset">
      <br>
      <center>
        <legend>Reporte</legend>
      </center>

      <div class="form-group">
        <label for="tasa" class="form-label ">Tienda</label>
        <select name="sedes_nom" id="">
          <option value="todos">Todas</option>

          <?php
          require '../../services/mysql.php';

          $service = new Tiendas();
          var_dump($service);
          $res=$service->getTiendas();
          var_dump($service->getTiendas());


          ?>

        </select>
      </div>

      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>