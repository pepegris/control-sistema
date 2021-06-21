<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulario/formulario.css">
    <link rel="stylesheet" href="../css/formulario/bootstrap.min.css">

    
    <title>Fiscales</title>
</head>
<body>
<?php include '../includes/menu.php';
       include 'includes/icono.php';
    
 ?>

<div id="body" >
<form action="up_fiscales.php" method="POST" enctype="multipart/form-data"  >
  
    <div class="fieldset">
    <br>
    <center><legend>Registrar Fiscal</legend></center>

    
  
    <div class="form-group">
      <label for="tienda" class="form-label mt-2">Sede</label>
      <select name="tienda" id="" required >

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
      <label for="fis_fecha" class="form-label mt-2">Fecha</label>
      
      <input type="date" name="fis_fecha" id="" class="form-control"  required>
    </div>
   
    <div class="form-group">
      <label for="imagen" class="form-label mt-2">imagen</label>
      <input type="file" class="form-control" name="imagen" size="100" id="">
    </div>
    <div class="form-group">
      <label for="fis_des" class="form-label mt-2">Comentario</label>
      <textarea name="fis_des" id="" class="form-control"cols="15" rows="3" required ></textarea>
    </div>
   
   
    
   
 
    <br>
   
    
    </div>
    <center><button type="submit" class="btn btn-success">Save</button></center>
    <br>
    
    
</form>

</div>


    
  






    
</body>
</html>