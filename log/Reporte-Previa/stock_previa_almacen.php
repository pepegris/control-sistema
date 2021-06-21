


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
  include '../includes/menu.php'  ;
  require '../includes/conexion_previa.php';
  include 'includes/icono.php';

         
?>

<div id="body" class="stretchLeft">

  <form action="reporte_stock_almacenp.php" method="post" id="formulario" enctype="multipart/form-data">
  <center><h2>Stock Consolidado Tiendas</h2></center>
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


