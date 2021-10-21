<?php require_once '../includes/log.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulario/formulario.css">
    <link rel="stylesheet" href="../css/formulario/bootstrap.min.css">

    
    <title>Servidoreses</title>
</head>
<body>
<?php include '../includes/menu.php';
       include 'includes/icono.php';
    
 ?>
<br> 
<br>
<br>
<br>
<div id="body" >
<form action="up_servidores.php" method="POST" enctype="multipart/form-data"  >
  
    <div class="fieldset">
    <br>
    <center><legend>Registrar Servidor</legend></center>

    
  
    <div class="form-group">
      <label for="tienda" class="form-label mt-2">Sede</label>
      <select name="tienda" id="" required >

      <?php 
        require '../includes/conexion_control.php';

        $sql = "SELECT tienda FROM servidor_auditoria  ";
        $consulta = mysqli_query($conn,$sql);

        while ($res=mysqli_fetch_array($consulta)) {
            
            $sede=$res['tienda'];
            
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
      <label for="imagen" class="form-label mt-2">imagen</label>
      <input type="file" class="form-control" name="imagen" size="100" id="">
    </div>
   

    <div class="form-group">
      <label for="serv_mac" class="form-label mt-2">Mac</label> 
      <input type="text" name="serv_mac" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="serv_procesador" class="form-label mt-2">procesador</label> 
      <input type="text" name="serv_procesador" id="" class="form-control"  required>
    </div>
 

    <div class="form-group">
      <label for="serv_memoria" class="form-label mt-2">memoria ram</label> 
      <input type="text" name="serv_memoria" id="" class="form-control"  required>
    </div>


    <div class="form-group">
      <label for="serv_seriat" class="form-label mt-2">disco duro</label> 
      <input type="text" name="serv_serial" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="serv_tarjeta_v" class="form-label mt-2">tarjeta de video</label> 
      <input type="text" name="serv_tarjeta_v" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="serv_tarjeta_r" class="form-label mt-2">tarjeta de red</label> 
      <input type="text" name="serv_tarjeta_r" id="" class="form-control"  required>
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

<br>
<br>
<br>
<br>

</div>

<!-- BUSCADOR -->
    
<div class="container mt-2">
  <div class="row">

    
    <div class="row">
    <div class="col">
    <h2>Por Fecha</h2>
      <div class="card-body">

      <form action="servidores_buscar.php" method="POST"  >
  
    
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

      <form action="servidores_buscar.php" method="POST"  >
  
  
  <br>
  <center><legend>Buscar Servidores</legend></center>


  <div class="form-group">
    <label for="sedes_nom" class="form-label mt-2">Sede</label>
    <select name="sedes_nom" id=""  >

    <?php 
      

      $sql = "SELECT tienda FROM servidor_auditoria  ";
      $consulta = mysqli_query($conn,$sql);

      while ($res=mysqli_fetch_array($consulta)) {
          
          $sede=$res['tienda'];
          
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

    
  



    
</body>
</html>