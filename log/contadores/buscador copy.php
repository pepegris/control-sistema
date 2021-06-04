<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulario/buscador.css">
    <link rel="stylesheet" href="../css/formulario/bootstrap.min.css">
    <title>Contadores</title>
</head>
<body>
<?php include '../includes/menu.php';
    
 ?>



<!-- buscador con sede -->
<div id="body">
<form action="buscar_contadores.php" method="POST"  >
  
    <div class="fieldset">
    <br>
    <center><legend>Buscar Contadores</legend></center>


    <div class="form-group">
      <label for="sedes_nom" class="form-label mt-2">Sede</label>
      <select name="sedes_nom" id=""  >

      <?php 
        require '../includes/conexion_mysql.php';

        $sql = "SELECT sedes_nom FROM sedes  ";
        $consulta = mysqli_query($conn,$sql);

        while ($res=mysqli_fetch_array($consulta)) {
            
            $sede=$res['sedes_nom'];
            
        ?>
            <option value="<?=$sede?>"><?=$sede?></option>

        <?php } ?>
      
      </select>
      
    </div>

    <label for="fecha_desde">Desde</label>
    <input type="date" name="fecha_desde" id="" required class="form-control">

    <label for="fecha_hasta">Hasta</label>
    <input type="date" name="fecha_hasta" id="" required  class="form-control">

    
  
   
 
    <br>
   
    <center><button type="submit" class="btn btn-success">Buscar</button></center>
    <br>
    </div>
</form>
</div>

<!-- buscador sin sdede -->


<div id="body">
<form action="buscar_contadores.php" method="POST"  >
  
    <div class="fieldset">
    <br>
    <center><legend>Buscar Contadores</legend></center>



    <label for="fecha_desde">Desde</label>
    <input type="date" name="fecha_desde" id="" required class="form-control">

    <label for="fecha_hasta">Hasta</label>
    <input type="date" name="fecha_hasta" id="" required  class="form-control">

    
  
   
 
    <br>
   
    <center><button type="submit" class="btn btn-success">Buscar</button></center>
    <br>
    </div>
</form>
</div>


    
  




    
</body>
</html>