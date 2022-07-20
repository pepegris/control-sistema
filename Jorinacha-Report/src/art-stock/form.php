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
          $res=$service->getTiendas();
          var_dump($res);
          while ($row = mysqli_fetch_array($res)) {

            $sede = $row['sedes_nom'];

          ?>
            <option value="<?= $sede ?>"><?= $sede ?></option>

          <?php } ?>
        </select>
      </div>

      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>