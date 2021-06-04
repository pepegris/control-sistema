


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../css/formulario/formulario.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>REPORTES</title>
</head>
<body>

<?php 
 include '../includes/menu.php'  ;
 
?>


 <div id="body">
 <form action="articulos_resultado.php" method="post" enctype="multipart/form-data">


<label for="bd">Tienda</label>
<select class="bd" name='bd' id="bd">
    <option>ACARI_A</option>
    <option>CATICA_A</option>
    <option>CORINA_A</option>
    <option>KAGU_A</option>
    <option>MATUR_A</option>
  </select>
<label for="art">Articulo</label>
<input type="text" name="art" id="art"autofocus="autofocus" required="required"  >
<label for="">Fecha</label>
<label for="date1">Desde</label>
<input type="date" name="date1" id="date1" required="required">
<label for="date2">Hasta</label>
<input type="date" name="date2" id="date2" required="required">
<input type="submit" value="Enviar">


</form>

 </div>

















    
</body>
</html>


