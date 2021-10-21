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
<body>
<?php include '../includes/menu.php';
 
    
 ?>
<br> 
<br>

<br>
<br>
<div id="body" >
<form action="fiscales/fiscales_post.php" method="POST" enctype="multipart/form-data"  >
  
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
      <label for="serv_fecha" class="form-label mt-2">Fecha</label>
      
      <input type="date" name="serv_fecha" id="" class="form-control"  required>
    </div>
   
    <div class="form-group">
      <label for="imagen" class="form-label mt-2">Cargar imagen</label>
      <input type="file" class="form-control" name="imagen" size="100" id="">
    </div>
   
         <!-- FISCAL NUMERO ONE 1 -->
    <div class="form-group">
      <label for="fis_marca1" class="form-label mt-2">Marca Fiscal 1</label> 
      <input type="text" name="fis_marca1" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="fis_modelo1" class="form-label mt-2">Modelo Fiscal 1</label> 
      <input type="text" name="fis_modelo1" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="fis_serial1" class="form-label mt-2">Serial Fiscal 1</label> 
      <input type="text" name="fis_serial1" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="fis_nregistro1" class="form-label mt-2">Nº Registro Fiscal 1</label> 
      <input type="text" name="fis_nregistro1" id="" class="form-control"  required>
    </div>

   
    <div class="form-group">
      <label for="estado1" class="form-label mt-2">Estado Fiscal 1</label> 
      <select name="estado1" id="">
          <option value="OPERATIVA">OPERATIVA</option>
          <option value="AVERIADA">AVERIADA</option>
          <option value="REPARANDO">REPARANDO</option>
      </select>
    </div>

     <!-- FISCAL NUMERO TWOO 2-->

     <div class="form-group">
      <label for="fis_marca2" class="form-label mt-2">Marca Fiscal 2</label> 
      <input type="text" name="fis_marca2" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="fis_modelo2" class="form-label mt-2">Modelo Fiscal 2</label> 
      <input type="text" name="fis_modelo2" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="fis_serial2" class="form-label mt-2">Serial Fiscal 2</label> 
      <input type="text" name="fis_serial2" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="fis_nregistro2" class="form-label mt-2">Nº Registro Fiscal 2</label> 
      <input type="text" name="fis_nregistro2" id="" class="form-control"  required>
    </div>

   
    <div class="form-group">
      <label for="estado2" class="form-label mt-2">Estado Fiscal 2</label> 
      <select name="estado2" id="">
          <option value="OPERATIVA">OPERATIVA</option>
          <option value="AVERIADA">AVERIADA</option>
          <option value="REPARANDO">REPARANDO</option>
      </select>
    </div>
    <!-- FISCAL NUMERO TRHEE 3-->

    <div class="form-group">
      <label for="fis_marca3" class="form-label mt-2">Marca Fiscal 3</label> 
      <input type="text" name="fis_marca3" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="fis_modelo3" class="form-label mt-2">Modelo Fiscal 3</label> 
      <input type="text" name="fis_modelo3" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="fis_serial3" class="form-label mt-2">Serial Fiscal 3</label> 
      <input type="text" name="fis_serial3" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="fis_nregistro3" class="form-label mt-2">Nº Registro Fiscal 3</label> 
      <input type="text" name="fis_nregistro3" id="" class="form-control"  required>
    </div>

   
    <div class="form-group">
      <label for="estado3" class="form-label mt-2">Estado Fiscal 3</label> 
      <select name="estado3" id="">
          <option value="OPERATIVA">OPERATIVA</option>
          <option value="AVERIADA">AVERIADA</option>
          <option value="REPARANDO">REPARANDO</option>
      </select>
    </div>
    <!-- FISCAL NUMERO FOUR 4-->

    <div class="form-group">
      <label for="fis_marca4" class="form-label mt-2">Marca Fiscal 4</label> 
      <input type="text" name="fis_marca4" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="fis_modelo4" class="form-label mt-2">Modelo Fiscal 4</label> 
      <input type="text" name="fis_modelo4" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="fis_serial4" class="form-label mt-2">Serial Fiscal 4</label> 
      <input type="text" name="fis_serial4" id="" class="form-control"  required>
    </div>

    <div class="form-group">
      <label for="fis_nregistro4" class="form-label mt-2">Nº Registro Fiscal 4</label> 
      <input type="text" name="fis_nregistro4" id="" class="form-control"  required>
    </div>

   
    <div class="form-group">
      <label for="estado4" class="form-label mt-2">Estado Fiscal 4</label> 
      <select name="estado4" id="">
          <option value="OPERATIVA">OPERATIVA</option>
          <option value="AVERIADA">AVERIADA</option>
          <option value="REPARANDO">REPARANDO</option>
      </select>
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