<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../css/formulario/formulario.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/bootstrap-4.5.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/animations.css">
    
    <title>REPORTES</title>
</head>
<style>

</style>
<body>

<?php 

include '../includes/cabecera.php';
  include '../includes/icono.php';

  $todos='Todos';
         
?>

<div id="body" class="stretchLeft">

  <form action="reporte.php" method="post" id="formulario" >
  <center><h2>Stock de Articulos con su Precio Tiendas</h2></center>
  <p>Tiendas</p>

          <?php 
          require '../includes/conexion_control.php';

          $sql = "SELECT sedes_nom FROM sedes WHERE    estado_sede <> 'inactivo'  AND sedes_nom <> 'Sede Sabana Grande' ";
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

        <div class='form-group'>
                        <label for='fecha' class='form-label mt-2'>Fecha</label> 
                        <input type='date' name='fecha' id='' class='form-control' >
        </div>

        <div class="form-group">
        <label for="linea">Origen de la Data</label>
      <select class="linea" name='linea' id="linea">

      <option>Boleita</option>
      <option>Tiendas</option>
     
      </select>

        </div>

<!-- 
    <label for="linea">Linea del Articulo:</label>
      <select class="linea" name='linea' id="linea">

      <option><?php/* $todos; */ ?></option>
      <?php
     /*  require '../includes/conexion_previa.php';

    $sql = "SELECT co_lin from art  group by co_lin ";
    $consulta= sqlsrv_query($conn,$sql);


    while ($row=sqlsrv_fetch_array($consulta)) {
      $co_lin=$row['co_lin'];
 */
      ?>
          <option><?php/* $co_lin; */ ?></option>

          
      <?php/*  } */?>
      </select>
   -->
 
 
   




     
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


