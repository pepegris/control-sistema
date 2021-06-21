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
    require '../includes/conexion_control.php';
 ?>




<div class="container mt-2">
  <div class="row">
    <!-- CARGAR ARTICULO -->
    
    <div class="row">
    <div class="col">
    <h2>Por Fecha</h2>
      <div class="card-body">

      <form action="buscar_contadores.php" method="POST"  >
  
    
  <br>
  <center><legend>Buscar Contadores</legend></center>



  <label for="fecha_desde">Desde</label>
  <input type="date" name="fecha_desde" id="" required class="form-control">

  <label for="fecha_hasta">Hasta</label>
  <input type="date" name="fecha_hasta" id="" required  class="form-control">

  

 

  <br>
 
  <center><button type="submit" class="btn btn-success">Buscar</button></center>
  <br>
  
</form>
      </div>
     </div>

     
     <div class="col">
     <h2>Por Sede</h2>
      <div class="card-body">

      <form action="buscar_contadores.php" method="POST"  >
  
  
  <br>
  <center><legend>Buscar Contadores</legend></center>


  <div class="form-group">
    <label for="sedes_nom" class="form-label mt-2">Sede</label>
    <select name="sedes_nom" id=""  >

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


  

  <label for="fecha_desde">Desde</label>
  <input type="date" name="fecha_desde" id="" required class="form-control">

  <label for="fecha_hasta">Hasta</label>
  <input type="date" name="fecha_hasta" id="" required  class="form-control">

  

 

  <br>
 
  <center><button type="submit" class="btn btn-success">Buscar</button></center>
  <br>
  
</form>
      </div>
     </div>
    

    </div>

    <hr>
  
   <!-- contadores actuales -->
    <div class="row" id="contadores">
<?php 


    $fecha_actual = date("d-m-Y");
    //sumo 1 mes
    //echo date("d-m-Y",strtotime($fecha_actual."+ 1 month")); 
    //resto 1 mes
    $fecha= date("d-m-Y",strtotime($fecha_actual."- 1 month"));

    //fecha actual del equipo
    //$fecha=date_default_timezone_get();

    $sql2= "SELECT * from contador WHERE con_fecha>='$fecha'";
    $consulta2 = mysqli_query($conn,$sql2);

    while ($res=mysqli_fetch_array($consulta2)) {

      $id=$res['id'];
        $tienda=$res['tienda'];
        $descripcion=$res['con_des'];
        $img=$res['con_img'];
        $fecha_con=$res['con_fecha']; ?>

<div class="col-sm-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title"><?=$tienda?></h5>
        <p class="card-text"><?=$fecha_con?></p>
        <p class="card-text"><?=$descripcion?></p>
        <a href="../uploads/documents/<?=$img?>" download="<?=$tienda.'_'.$fecha_con?>" class="btn btn-primary">Descargar</a>
        <a href='edit.php?id=<?php echo $id?>' class='btn btn-info'>
                <i class='fas fa-marker'></i>
       </a>
        <a href='delete.php?id=<?php echo $id?>' class='btn btn-danger'>
                <i class='far fa-trash-alt'></i>
        </a>
      </div>
    </div>
  </div>
  




        
 <?php   } ?>

 </div>




    
</body>
</html>