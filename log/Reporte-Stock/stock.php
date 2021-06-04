


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>REPORTES</title>
</head>
<style>
.buscador{
  margin-left: 100px;
}
</style>
<body>

<?php 
  include '../includes/menu.php'  ;
  require_once '../includes/conexion_previa.php';
 
?>

<div class="buscador">

  <form action="stock_resultado.php" method="post" enctype="multipart/form-data">


    <label for="bd">Tienda</label>
    <select class="bd" name='bd' id="bd">
        <option>ACARI_A</option>
        <option>CATICA_A</option>
        <option>CORINA_A</option>
        <option>KAGU_A</option>
        <option>MATUR_A</option>
      </select>


      <!-- linea de articulos -->
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
      <!-- linea de articulos -->
      <input type="submit" value="Enviar">
    
  
  
  </form>



</div>















    
</body>
</html>


