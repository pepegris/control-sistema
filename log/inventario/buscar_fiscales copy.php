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
<style>
    #contadores {
        margin-left: 95px;
    }
    
</style>
<body>
<?php 
if (isset($_POST)) {

    require '../includes/conexion_control.php';
    include '../includes/menu.php';
    
    
 ?>


<div class="row" id="contadores">

<?php 



    $sedes_nom=isset ($_POST ['sedes_nom']) ? mysqli_real_escape_string($conn,$_POST ['sedes_nom'] ): false ;
    $fecha_desde=isset($_POST ['fecha_desde']) ? mysqli_real_escape_string($conn,$_POST['fecha_desde']):false ;
    $fecha_hasta=isset($_POST ['fecha_hasta']) ? mysqli_real_escape_string($conn,$_POST['fecha_hasta']):false ;


    if ($conn) {

        $sql;
        if ($sedes_nom) {

            $sql="SELECT * from fiscal WHERE tienda='$sedes_nom' and fis_fecha BETWEEN '$fecha_desde' and '$fecha_hasta' order by fis_fecha desc ";

        }elseif ($sedes_nom==null) {

            $sql="SELECT * from fiscal WHERE fis_fecha BETWEEN '$fecha_desde' and '$fecha_hasta' order by fis_fecha desc ";
        }
        

       
        $consulta= mysqli_query($conn,$sql);

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
            <img src="../uploads/img/<?=$imagen?>" style="height: 90px;" alt="">
            <hr>
            <a href="../uploads/img/<?=$imagen?>" download="<?=$tienda?>" class="btn btn-primary">Descargar</a>
            <a href='edit.php?id=<?php echo $id?>' class='btn btn-info'>
                    <i class='fas fa-marker'></i>
           </a>
            <a href='delete.php?id=<?php echo $id?>' class='btn btn-danger'>
                    <i class='far fa-trash-alt'></i>
            </a>
          </div>
        </div>
      </div>
      
    



        
 <?php     }}}?>

 </div>





    
</body>
</html>



<?php






?>