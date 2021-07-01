<?php

 



 session_start();

$cuenta_on=$_SESSION['username'];

if (!isset($cuenta_on)) {
    header("location:../inicio.php");
}

$cuenta_on = ucwords($cuenta_on); 


 
?>


<a href="#" class="btn btn-dark" style="position: fixed;
    bottom: 90%;
    right: 5%;
    font-size:30px;" >Usuario: <?=$cuenta_on?></a>


  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="log/css/formulario/buscador.css">
    <link rel="stylesheet" href="log/css/formulario/bootstrap.min.css">
    <link rel="stylesheet" href="log/css/fondo.css">
  
    <title>Inicio</title>
</head>
<style>
h3{
  color: black;
}
</style>
<body>
<?php 
    require 'log/includes/conexion_control.php';
    include 'log/includes/menu_inicio.php';


    $fecha_actual = date("d-m-Y");
    //sumo 1 mes
    //echo date("d-m-Y",strtotime($fecha_actual."+ 1 month")); 
    //resto 1 mes
    $fecha= date("d-m-Y",strtotime($fecha_actual."- 1 month"));

    //fecha actual del equipo
    //$fecha=date_default_timezone_get();

    $rango_fecha='Desde '.$fecha.' hasta '.$fecha_actual;
 ?>







<br>

 <center><h3>Nota de Entrega de Equipos</h3><p><?=$rango_fecha ?></p></center>
 <!-- equipos actuales -->

 
<div class="container mt-2">
 
  
 <!-- fiscales actuales -->
  <div class="row" id="contadores">
<?php 


  

$sql2= "SELECT * from equipos WHERE eq_fecha>='$fecha' ORDER BY eq_fecha desc";
$consulta2 = mysqli_query($conn,$sql2);

while ($res2=mysqli_fetch_array($consulta2)) {

    $id=$res2['id'];
    $usuario=$res2['usuario'];
    $equipo=$res2['equipo'];
    $eq_des=$res2['eq_des'];
    $estado=$res2['estado']; 
    $eq_fecha=$res2['eq_fecha'];?>

<div class="col-sm-4">
<div class="card">
  <div class="card-body">
    <h5 class="card-title"><?=$usuario?></h5>
    <p class="card-text"><?=$eq_fecha?></p>
    <p class="card-text"><?=$estado?></p>
    <p class="card-text"><?=$eq_des?></p>
   <!--  <a href='edit.php?id=<?php echo $id?>' class='btn btn-info'>
            <i class='fas fa-marker'></i>
   </a>
    <a href='delete.php?id=<?php echo $id?>' class='btn btn-danger'>
            <i class='far fa-trash-alt'></i>
    </a> -->

    <a href='log/equipos/orden_entrega.php?id=<?php echo $id?>' class='btn btn-success'>
    <i class="fa fa-print" aria-hidden="true"></i>

        </a>


  </div>
</div>
</div>





    
<?php   } ?>

</div>

</div>



<br>
<center><h3>Fiscales</h3><p><?=$rango_fecha ?></p></center>
<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

   <!-- fiscales actuales -->
<div class="container mt-2">
 
  
   <!-- fiscales actuales -->
    <div class="row" id="contadores">
<?php 


    

    $sql= "SELECT * from fiscal WHERE fis_fecha>='$fecha' ORDER BY fis_fecha desc";
    $consulta = mysqli_query($conn,$sql);

    while ($res=mysqli_fetch_array($consulta)) {

        $id=$res['id'];
        $tienda=$res['tienda'];
        $descripcion=$res['fis_des'];
        $imagen=$res['fis_img'];
        $fis_fecha=$res['fis_fecha']; ?>

<div class="col-sm-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title"><?=$tienda?></h5>
        <p class="card-text"><?=$fis_fecha?></p>
        <p class="card-text"><?=$descripcion?></p>
        <img src="log/uploads/img/<?=$imagen?>" style="height: 90px;" alt="">
        <hr>
        <a href="log/uploads/img/<?=$imagen?>" download="<?=$tienda?>" class="btn btn-primary">Descargar</a>
       <!--  <a href='edit.php?id=<?php echo $id?>' class='btn btn-info'>
                <i class='fas fa-marker'></i>
       </a>
        <a href='delete.php?id=<?php echo $id?>' class='btn btn-danger'>
                <i class='far fa-trash-alt'></i>
        </a> -->
      </div>
    </div>
  </div>
  




        
 <?php   } ?>

 </div>
</div>


<br>
<center><h3>Contadores</h3><p><?=$rango_fecha ?></p></center>
 <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

<!-- contadores actuales -->
 
<div class="container mt-2">
   <!-- contadores actuales -->
   <div class="row" id="contadores">
<?php 


   

    $sql3= "SELECT * from contador WHERE con_fecha>='$fecha' ORDER BY con_fecha desc";
    $consulta3 = mysqli_query($conn,$sql3);

    while ($res3=mysqli_fetch_array($consulta3)) {

      $id=$res3['id'];
        $tienda=$res3['tienda'];
        $descripcion=$res3['con_des'];
        $excel=$res3['con_img'];
        $fecha_con=$res3['con_fecha']; ?>

<div class="col-sm-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title"><?=$tienda?></h5>
        <p class="card-text"><?=$fecha_con?></p>
        <p class="card-text"><?=$descripcion?></p>
        <a href="log/uploads/img/<?=$excel?>" download="<?=$tienda.'_'.$fecha_con?>" class="btn btn-primary">Descargar</a>
        <!-- <a href='edit.php?id=<?php echo $id?>' class='btn btn-info'>
                <i class='fas fa-marker'></i>
       </a>
        <a href='delete.php?id=<?php echo $id?>' class='btn btn-danger'>
                <i class='far fa-trash-alt'></i>
        </a> -->
      </div>
    </div>
  </div>
  




        
 <?php   } ?>

 </div>

</div>



    
</body>
</html>



  

</body>

</html>