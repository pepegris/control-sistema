
<?php

session_start();

$cuenta_on=$_SESSION['username'];

if (!isset($cuenta_on)) {
    header("location:../salir.php");
}

$cuenta_on = ucwords($cuenta_on); 

 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulario/formulario.css">
    <link rel="stylesheet" href="../css/formulario/bootstrap.min.css">

    <link rel="stylesheet" href="../css/bootstrap-4.5.2-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../js/animations.css">
    <title>Contadores</title>
</head>
<body>
<?php include '../includes/menu.php';

    include 'includes/icono.php';
    
    /* $prueba=$_SERVER['DOCUMENT_ROOT'];
    var_dump($prueba);
    $carpeta = $_SERVER['DOCUMENT_ROOT'] . '\php\control\log\uploads\documents';
    var_dump($carpeta); */
 ?>

<div id="body">

<!-- uploads_contadores.php -->

<form action="formulario_contadores.php" method="POST"    >
  
    <div class="fieldset">
    <br>
    <center><legend>Registrar Contador</legend></center>

    
    <div class="form-group">
      <label for="sede" class="form-label mt-2" >Seleccionar Sede</label>
      <select name="sede" id="" required  class="form-control" style="height: 50px;">

      <?php 
        require '../includes/conexion_control.php';

        $sql = "SELECT sedes_nom FROM sedes  ";
        $consulta = mysqli_query($conn,$sql);

        while ($res=mysqli_fetch_array($consulta)) {
            
            $sede=$res['sedes_nom'];
            
        ?>
            <option value="<?=$sede?>" ><?=$sede?></option>

        <?php } ?>
      
      </select>
      
    </div>
     
    
   
 
    <br>
   
    
    
    <center><button type="submit" class="btn btn-primary">Registrar</button></center>
    <br>
    
    
</form>

</div>







    
</body>
</html>