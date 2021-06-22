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
<style>
#equipos .conta form{

  padding:50px
}


</style>

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
      <div class="card-body" class="stretchLeft">

      <form action="buscar_equipos.php" method="POST"  >
  
    
  <br>
  <center><legend>Buscar Equipos</legend></center>



  <label for="fecha_desde">Desde</label>
  <input type="date" name="fecha_desde" id="" required class="form-control">

  <label for="fecha_hasta">Hasta</label>
  <input type="date" name="fecha_hasta" id="" required  class="form-control">

  

 

  <br>
 
  <center><button type="submit" class="btn btn-primary">Buscar</button></center>
  <br>
  
</form>
      </div>
     </div>

     
     <div class="col">
     <h2>Por Sede</h2>
      <div class="card-body" class="stretchLeft">

      <form action="buscar_equipos.php" method="POST"  >
  
  
  <br>
  <center><legend>Buscar Equipos</legend></center>


  <div class="form-group">
    <label for="sedes_nom" class="form-label mt-2">Usuario</label>
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
      <option value="Empleado">Empleado</option>
    </select>
    
  </div>


  

  <label for="fecha_desde">Desde</label>
  <input type="date" name="fecha_desde" id="" required class="form-control">

  <label for="fecha_hasta">Hasta</label>
  <input type="date" name="fecha_hasta" id="" required  class="form-control">

  

 

  <br>
 
  <center><button type="submit" class="btn btn-primary">Buscar</button></center>
  <br>
  
</form>
      </div>
     </div>
    

    </div>

    <hr>
  
   <!-- equipos nuevo-->
    <div class="row" id="equipos">

    <div class="conta">
    
        <form action="up_equipo.php" method="post">
          <h2>Registrar equipo nuevo</h2>
        <label for="usuario" class="form-label mt-2">Destino:</label>
        <select name="usuario" id="" class="form-control" >

    <?php 
     

      $sql2 = "SELECT sedes_nom FROM sedes  ";
      $consulta2 = mysqli_query($conn,$sql2);

      while ($res=mysqli_fetch_array($consulta2)) {
          
          $sede=$res['sedes_nom'];
          
      ?>
          <option value="<?=$sede?>"><?=$sede?></option>

      <?php } ?>
      <option value="Empleado">Empleado</option>
        
    </select>
    <label for="equipo" >Equipo:</label>
    <input type="text" name="equipo" class="form-control" id="" required>
    <br>
    <label for="estado" >Estado:</label>
    <select name="estado" id="" >
    <option value="Operativo">Operativo</option>
    <option value="Averiado">Averiado</option>
    <option value="Espera">Espera</option>
    </select>

    <label for="eq_fecha" >Fecha:</label>
    <input type="date" name="eq_fecha"  id="" required>

    
    <br>

    <label for="eq_des" >Comentario:</label>
    <textarea name="eq_des" class="form-control" id="" cols="5" rows="3" required></textarea>
    
    <center><button type="submit" class="btn btn-primary">Guardar</button></center>    


        
        </form>
     
  </div>

  <hr>

     <!-- equipos actuales -->
 
<?php 
    
    $fecha_actual = date("d-m-Y");
    //sumo 1 mes
    //echo date("d-m-Y",strtotime($fecha_actual."+ 1 month")); 
    //resto 1 mes
    $fecha= date("d-m-Y",strtotime($fecha_actual."- 1 month"));

    //fecha actual del equipo
    //$fecha=date_default_timezone_get();

    $sql3= "SELECT * from equipos WHERE eq_fecha>='$fecha'";
    $consulta3 = mysqli_query($conn,$sql3);

    while ($res=mysqli_fetch_array($consulta3)) {

        $id=$res['id'];
        $usuario=$res['usuario'];
        $equipo=$res['equipo'];
        $eq_des=$res['eq_des'];
        $estado=$res['estado']; 
        $eq_fecha=$res['eq_fecha'];?>

<div class="col-sm-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title"><?=$usuario?></h5>
        <p class="card-text"><?=$equipo?></p>
        <p class="card-text"><?=$eq_fecha?></p>
        <p class="card-text"><?=$estado?></p>
        <p class="card-text"><?=$eq_des?></p>
        <a href='edit.php?id=<?php echo $id?>' class='btn btn-info'>
                <i class='fas fa-marker'></i>
       </a>
        <a href='delete.php?id=<?php echo $id?>' class='btn btn-danger'>
                <i class='far fa-trash-alt'></i>
        </a>
      <!-- ORDEN DE ENTREGA -->
        <a href='orden_entrega.php?id=<?php echo $id?>' class='btn btn-success'>
        <i class="fa fa-print" aria-hidden="true"></i>

        </a>
      </div>
    </div>
  </div>
  




        
 <?php   } ?>

 </div>




    
</body>
</html>