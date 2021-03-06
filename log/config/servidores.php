<?php require_once 'includes/log.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulario/formulario.css">
    <link rel="stylesheet" href="../css/formulario/bootstrap.min.css">

    
    <title>Conf Servidores</title>
</head>
<body>
<?php include '../includes/menu.php';
 
    
 ?>
<br> 
<br>

<br>
<br>
<div id="body" >
<form action="servidores/servidores_post.php" method="POST" enctype="multipart/form-data"  >
  
    <div class="fieldset">
    <br>
    <center><legend>Registrar Servidor</legend></center>

    
  
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
      <label for="serv_fecha" class="form-label mt-2">Fecha</label>
      
      <input type="date" name="serv_fecha" id="" class="form-control"  required>
    </div>
   
    <div class="form-group">
      <label for="imagen" class="form-label mt-2">Cargar imagen</label>
      <input type="file" class="form-control" name="imagen" size="100" id="">
    </div>
   

    <div class="form-group">
      <label for="serv_mac" class="form-label mt-2">Dir Mac</label> 
      <input type="text" name="serv_mac" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="serv_proc" class="form-label mt-2">Procesador</label> 
      <input type="text" name="serv_proc" id="" class="form-control"  required>
    </div>
 

    <div class="form-group">
      <label for="serv_ram" class="form-label mt-2">Memoria Ram</label> 
      <input type="text" name="serv_ram" id="" class="form-control"  required>
    </div>


    <div class="form-group">
      <label for="serv_disc" class="form-label mt-2">Disco Duro</label> 
      <input type="text" name="serv_disc" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="serv_vid class="form-label mt-2">Tarjeta de Video</label> 
      <input type="text" name="serv_vid" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="serv_red" class="form-label mt-2">Tarjeta de Red</label> 
      <input type="text" name="serv_red" id="" class="form-control"  required>
    </div>


    <div class="form-group">
      <label for="serv_des" class="form-label mt-2">Detalles del Servidor</label>
      <textarea name="serv_des" id="" class="form-control"cols="15" rows="3" ></textarea>
    </div>

    

   
   
    
   
 
    <br>
   
    
    </div>
    <center><button type="submit" class="btn btn-primary">Save</button></center>
    <br>
    
    
</form>


</div>

<!-- BUSCADOR -->

<br>
<br>

<br>
<br>
    <div id="buscador">
    <center><h2>Buscar</h2></center>
<div class="container mt-2">
  <div class="row">

    
    <div class="row">
    <div class="col">
    <h2>Por Fecha</h2>
      <div class="card-body">

      <form action="servidores/servidores_buscar.php" method="POST"  >
  
    
  <br>
  <center><legend>Buscar Servidores</legend></center>



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

      <form action="servidores/servidores_buscar.php" method="POST"  >
  
  
  <br>
  <center><legend>Buscar Servidores</legend></center>


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