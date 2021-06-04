


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../css/formulario/formulario.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>REPORTES</title>
</head>
<style>

</style>
<body>

<?php 
  include '../includes/menu.php'  ;
  require '../includes/conexion_previa.php';

         
?>

<div id="body">

  <form action="stock_resultado_total.php" method="post" id="formulario" enctype="multipart/form-data">
  <center><h2>Stock Consolidado</h2></center>
  <p>Tiendas</p>
  <div class="form-check">
  <input class="form-check-input" type="checkbox" name='acari_a' value="15" id="acari_a">
  <label class="form-check-label" for="acari_a">
    Acari_a
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="25" name='higue_a' id="higue_a" checked>
  <label class="form-check-label" for="higue_a">
    Higue_a
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="35" name='valena_a' id="valena_a" checked>
  <label class="form-check-label" for="valena_a">
    valena_a
  </label>

</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" name='apura_a' value="45" id="apura_a">
  <label class="form-check-label" for="apura_a">
    Apura_A
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="55" name='catica2a' id="catica2a" checked>
  <label class="form-check-label" for="catica2a">
    Catica2a
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="65" name='corina2a' id="corina2a" checked>
  <label class="form-check-label" for="corina2a">
    Corina2a
  </label>
</div>
   

<div class="form-check">
  <input class="form-check-input" type="checkbox" name='kagu_a' value="75" id="kagu_a">
  <label class="form-check-label" for="kagu_a">
    Kagu_a
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="85" name='matur_a' id="matur_a" checked>
  <label class="form-check-label" for="matur_a">
    Matur_a
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="95" name='merina_a' id="merina_a" checked>
  <label class="form-check-label" for="merina_a">
    Merina_a
  </label>
</div>

<div class="form-check">
  <input class="form-check-input" type="checkbox" name='merina3a' value="105" id="merina3a">
  <label class="form-check-label" for="merina3a">
    Merina3a
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="115" name='nacharia' id="nacharia" checked>
  <label class="form-check-label" for="nacharia">
    Nacharia
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="125" name='ojena_a' id="ojena_a" checked>
  <label class="form-check-label" for="ojena_a">
    Ojena_a
  </label>
</div>

<div class="form-check">
  <input class="form-check-input" type="checkbox" name='puecruza' value="135" id="puecruza">
  <label class="form-check-label" for="puecruza">
    Puecruza
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="145" name='pufijo_a' id="pufijo_a" checked>
  <label class="form-check-label" for="pufijo_a">
    Pufijo_a
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="155" name='trina_a' id="trina_a" checked>
  <label class="form-check-label" for="trina_a">
    Trina_a
  </label>
</div>

<div class="form-check">
  <input class="form-check-input" type="checkbox" name='turme_a' value="165" id="turme_a">
  <label class="form-check-label" for="turme_a">
    Turme_a
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="175" name='vallepaa' id="vallepaa" checked>
  <label class="form-check-label" for="vallepaa">
    vallepaa
  </label>
</div>

   




      <label for="linea">Linea</label>
    <select class="linea" name='linea' id="linea">
    <?php

  $sql = "SELECT co_lin from art  group by co_lin ";
  $consulta= sqlsrv_query($conn,$sql);


  while ($row=sqlsrv_fetch_array($consulta)) {
    $co_lin=$row['co_lin'];

?>
    <option><?=$co_lin ?></option>

		
<?php }?>
</select>
      <input type="submit" value="Enviar">
    
  
  
  </form>



</div>















    
</body>
</html>


