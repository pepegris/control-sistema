<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../css/formulario/formulario.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/bootstrap-4.5.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/animations.css">
    
    <title>Reportes Articulos</title>
</head>
<style>

</style>
<body>

<?php 

include '../includes/cabecera.php';
  include '../includes/icono.php';

  $todos='Todos';

  require '../includes/conexion_previa.php';

    $sql_co_lin = "SELECT co_lin from art  group by co_lin ";
    $consulta_co_lin= sqlsrv_query($conn,$sql_co_lin);
         
?>

<div id="body" class="stretchLeft">

  <form action="reporte_articulos_buscar.php" method="post" id="formulario" >
  <center><h2>Stock de Articulos con su Precio Tiendas</h2></center>
  <p>Tiendas</p>

          <?php 
          require '../includes/conexion_control.php';

          $sql = "SELECT sedes_nom FROM sedes  ";
          $consulta = mysqli_query($conn,$sql);

          $i=5;
          while ($res=mysqli_fetch_array($consulta)) {
              
              $sede=$res['sedes_nom'];
              $i+=10;
              
          ?>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name='<?=$sede?>' value="<?=$i?>"  >
            <label class="form-check-label" for="<?=$sede?>">
            <?=$sede?>
            </label>
            </div>

        <?php } ?>

            <label for="almacen">Almacen</label>
            <select name="almacen" id="">
    
              <option value="BOLE">Boleita</option>
              <option value="DEVO">Devolucion</option>
              <option value="1">Tienda</option>
            </select>

 
    <label for="linea">Linea del Articulo:</label>
      <select class="linea" name='linea' id="linea">

      <option><?= $todos  ?></option>
      <?php
      


    while ($row=sqlsrv_fetch_array($consulta_co_lin)) {
      $co_lin=$row['co_lin'];
 
      ?>
          <option><?= $co_lin  ?></option>

          
      <?php  } ?>
      </select>
   
 
 
   




     
 <center>     <input type="submit" value="Enviar" class="btn btn-info" >
    </center>
  
  
  </form>



</div>






<script>
	$('#animatedElement').click(function() {
		$(this).addClass("stretchLeft");
	});
</script>



    
<script src="../js/jquery-1.10.1.min.js"></script>











    
</body>
</html>


