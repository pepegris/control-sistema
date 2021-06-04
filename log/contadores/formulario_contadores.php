<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulario/formulario.css">
    <link rel="stylesheet" href="../css/formulario/bootstrap.min.css">
    <title>Contadores</title>
</head>
<body>
<?php include '../includes/menu.php';
    
    /* $prueba=$_SERVER['DOCUMENT_ROOT'];
    var_dump($prueba);
    $carpeta = $_SERVER['DOCUMENT_ROOT'] . '\php\control\log\uploads\documents';
    var_dump($carpeta); */
 ?>

<div id="body">


<form action="uploads_contadores.php" method="POST" enctype="multipart/form-data"  >
  
    <div class="fieldset">
    <br>
    <center><legend>Registrar Contador</legend></center>

    
  
    <div class="form-group">
      <label for="sedes_nom" class="form-label mt-2">Sede</label>
      <select name="sedes_nom" id="" required >

      <?php 
        require '../includes/conexion_control.php';

        $sql = "SELECT sedes_nom FROM sedes  ";
        $consulta = mysqli_query($conn,$sql);

        while ($res=mysqli_fetch_array($consulta)) {
            
            $sede=$res['sedes_nom'];
            
        ?>
            <option value="<?=$sede?>"><?=$sede?></option>

        <?php } ?>
      
      </select>
      
    </div>
    <div class="form-group">
      <label for="fecha" class="form-label mt-2">Fecha</label> 
      <input type="date" name="fecha" id="" class="form-control"  required>
    </div>
    
    <div class="form-group">
      <label for="cantidad" class="form-label mt-2">Contador </label>
      <input type="number" class="form-control" name="cantidad"  id="" required>
    </div>

    <div class="form-group">
      <label for="ip" class="form-label mt-2">Direccion IP </label>
      <input type="text" class="form-control" name="ip"  id="" required>
    </div>



   
    <div class="form-group">
      <label for="excel" class="form-label mt-2">Contador Imagen</label>
      <input type="file" class="form-control" name="excel" size="100" id="" required>
    </div>

    


    <div class="form-group">
      <label for="con_des" class="form-label mt-2">Comentario</label>
      <textarea name="con_des" id="" class="form-control"cols="15" rows="3" ></textarea>
    </div>
   
   
    
   
 
    <br>
   
    
    </div>
    <center><button type="submit" class="btn btn-success">Save</button></center>
    <br>
    
    
</form>

</div>

<center><a href="buscador.php"class="btn btn-warning" >Buscar Contadores</a></center>

<br>
    
  




    
</body>
</html>