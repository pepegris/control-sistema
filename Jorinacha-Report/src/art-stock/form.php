<?php
require '../../includes/log.php';
include '../../includes/header.php';
$servername = "localhost";
$database = "control_sistema";
$username = "root";
$password = "";


$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password );
$res = $conn->query("SELECT sedes_nom FROM sedes WHERE    estado_sede <> 'inactivo'");
foreach ($res as $key ) {
  var_dump($key);
}
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