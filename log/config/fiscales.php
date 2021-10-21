<?php require_once 'includes/log.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulario/formulario.css">
    <link rel="stylesheet" href="../css/formulario/bootstrap.min.css">

    
    <title>Conf Fiscales</title>
</head>
<style>

</style>
<body>
<?php include '../includes/menu.php';
 
    
 ?>
 <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<div id="body" >
<form action="fiscales/fiscales_formulario.php" method="POST" enctype="multipart/form-data"  >
  
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
      <label for="fiscal" class="form-label mt-2">Cantidad de Fiscales</label>
      <select name="fiscal" id="">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select>
    </div>


   
   
    
   
 
    <br>
   
    
    </div>
    <center><button type="submit" class="btn btn-primary">Save</button></center>
    <br>
    
    
</form>


</div>

<!-- BUSCADOR -->

    <div id="buscador">
    <center><h2>Buscar</h2></center>
<div class="container mt-2">
  <div class="row">

    
    <div class="row">
    <div class="col">
    <h2>Por Fecha</h2>
      <div class="card-body">

      <form action="fiscales/fiscales_buscar.php" method="POST"  >
  
    
  <br>
  <center><legend>Buscar Fiscales</legend></center>



  <label for="fecha_desde">Desde</label>
  <input type="date" name="fecha_desde" id=""  class="form-control">

  <label for="fecha_hasta">Hasta</label>
  <input type="date" name="fecha_hasta" id=""   class="form-control">

  

 

  <br>
 
  <center><button type="submit" class="btn btn-primary">Buscar</button></center>
  <br>
  
</form>
      </div>
     </div>

     
     <div class="col">
     <h2>Por Sede</h2>
      <div class="card-body">

      <form action="fiscales/fiscales_buscar.php" method="POST"  >
  
  
  <br>
  <center><legend>Buscar Fiscales</legend></center>


  <div class="form-group">
    <label for="sedes_nom" class="form-label mt-2">Sede</label>
    <select name="sedes_nom" id=""  >

    <?php 
      

      $sql = "SELECT sedes_nom FROM sedes ";
      $consulta = mysqli_query($conn,$sql);

      while ($res=mysqli_fetch_array($consulta)) {
          
          $sede=$res['sedes_nom'];
          
      ?>
          <option value="<?=$sede?>"><?=$sede?></option>

      <?php } ?>
    
    </select>
    
  </div>

 
  <center><button type="submit" class="btn btn-primary">Buscar</button></center>
  <br>
  
</form>
      </div>
     </div>
    

    </div>

    </div>
  



    
</body>
</html>