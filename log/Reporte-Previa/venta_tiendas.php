
  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulario/buscador.css">
    <link rel="stylesheet" href="../css/formulario/bootstrap.min.css">
    <title>Equipos</title>
</head>

<body>
<?php include '../includes/menu.php';
     require '../includes/conexion_control.php';
     include 'includes/icono.php';
 ?>




<div class="container mt-2">
  <div class="row">
    <!-- CARGAR ARTICULO -->
    
    <div class="row">
    <div class="col">
    <h2>Factura Sin Renglones</h2>
      <div class="card-body">

      
  <form action="reporte_venta_diariap.php" method="post" id="formulario" enctype="multipart/form-data">
  <center><h2>Total de Venta</h2></center>
  

  <label for="sedes_nom" class="form-label mt-2">Tiendas</label>
    <select name="sedes_nom" id=""  >

    <?php 
      require '../includes/conexion_control.php';

      $sql = "SELECT sedes_nom FROM sedes  ";
      $consulta = mysqli_query($conn,$sql);

      $i=5;
      while ($res=mysqli_fetch_array($consulta)) {
          
          $sede=$res['sedes_nom'];
          $i+=10;
          
      ?>
          <option value="<?=$i?>"><?=$sede?></option>

      <?php } ?>
  
    </select>
    

 


  
      <label for="fecha">Fecha</label>

      <input type="date" name="fecha" id="">
     

  

<!--    

<label for="fecha">Fecha</label>
  <input type="date" name="fecha" id="" required >


   -->



     <center> 
      <input type="submit" value="Enviar" class="btn btn-info">
    
  </center>
  
  </form>


      </div>
     </div>

     
     <div class="col">
     <h2>Factura Con Renglones</h2>
      <div class="card-body">

      <form action="reporte_venta_diaria.php" method="post" id="formulario" enctype="multipart/form-data">
  <center><h2>Venta Diarias</h2></center>
  

  <label for="sedes_nom" class="form-label mt-2">Tiendas</label>
    <select name="sedes_nom" id=""  >

    <?php 
      require '../includes/conexion_control.php';

      $sql = "SELECT sedes_nom FROM sedes  ";
      $consulta = mysqli_query($conn,$sql);

      $i=5;
      while ($res=mysqli_fetch_array($consulta)) {
          
          $sede=$res['sedes_nom'];
          $i+=10;
          
      ?>
          <option value="<?=$i?>"><?=$sede?></option>

      <?php } ?>
  
    </select>
    

 


  
      <label for="fecha">Fecha</label>

      <input type="date" name="fecha" id="">
     

  

<!--    

<label for="fecha">Fecha</label>
  <input type="date" name="fecha" id="" required >


   -->



     <center> 
      <input type="submit" value="Enviar" class="btn btn-info">
    
  </center>
  
  </form>
      </div>
     </div>
    

    </div>

    <hr>

    <div class="col">
     <h2>Por tiendas</h2>
      <div class="card-body">

      <form action="reporte_venta_diaria.php" method="post" id="formulario" enctype="multipart/form-data">
  <center><h2>Venta Diarias</h2></center>
  

  <label for="sedes_nom" class="form-label mt-2">Tiendas</label>
    <select name="sedes_nom" id=""  >

    <?php 
      require '../includes/conexion_control.php';

      $sql = "SELECT sedes_nom FROM sedes  ";
      $consulta = mysqli_query($conn,$sql);

      $i=5;
      while ($res=mysqli_fetch_array($consulta)) {
          
          $sede=$res['sedes_nom'];
          $i+=10;
          
      ?>
          <option value="<?=$i?>"><?=$sede?></option>

      <?php } ?>
  
    </select>
    

 


  
      <label for="fecha">Fecha</label>

      <input type="date" name="fecha" id="">
     

  

<!--    

<label for="fecha">Fecha</label>
  <input type="date" name="fecha" id="" required >


   -->



     <center> 
      <input type="submit" value="Enviar" class="btn btn-info">
    
  </center>
  
  </form>
      </div>
     </div>
    

    </div>

  



    
</body>
</html>