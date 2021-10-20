<?php require_once '../includes/log.php';?>
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
    include '../includes/cabecera.php';

    $sedes_nom=isset ($_POST ['sedes_nom']) ? mysqli_real_escape_string($conn,$_POST ['sedes_nom'] ): false ;
    $fecha_desde=isset($_POST ['fecha_desde']) ? mysqli_real_escape_string($conn,$_POST['fecha_desde']):false ;
    $fecha_hasta=isset($_POST ['fecha_hasta']) ? mysqli_real_escape_string($conn,$_POST['fecha_hasta']):false ;
    
    
 ?>

 <center><h2>Servidores <?=$sedes_nom?></h2></center>
<table  class='table table-hover' id="tblData" >
        <thead>
        
            <tr class='table-primary'>
                <th scope='col' abbr='Starter'>Nº</th>
                <th scope='col' abbr='Starter'>Tienda</th>
                <th scope='col' abbr='Starter'>Descripcion</th>
                <th scope='col' abbr='Starter'>Fecha</th>
                <th scope='col' abbr='Starter'>Imagen</th>
                <th scope='col' abbr='Starter'>Accion</th>
                
               
				
			<tr>
		</thead>

<?php 






    if ($conn) {

        $sql;
        if ($sedes_nom) {


                if ($fecha_desde && $fecha_hasta) {
                    $sql="SELECT * from servidor_auditoria WHERE tienda='$sedes_nom' and serv_fecha BETWEEN '$fecha_desde' and '$fecha_hasta' order by serv_fecha desc ";

                } elseif ($fecha_desde==null && !$fecha_hasta) {

                    $sql="SELECT * from servidor_auditoria WHERE tienda='$sedes_nom'  order by serv_fecha desc ";

                } elseif ($fecha_desde && !$fecha_hasta) {

                    $sql="SELECT * from servidor_auditoria WHERE tienda='$sedes_nom' and serv_fecha >='$fecha_desde'  order by serv_fecha desc ";

                }elseif (!$fecha_desde && $fecha_hasta) {

                    $sql="SELECT * from servidor_auditoria WHERE tienda='$sedes_nom' and serv_fecha  <= '$fecha_hasta' order by serv_fecha desc ";

                    
                }
           
        }elseif ($sedes_nom==null) {


                $sql="SELECT * from servidor_auditoria WHERE serv_fecha BETWEEN '$fecha_desde' and '$fecha_hasta' order by serv_fecha desc ";

                if ($fecha_desde && $fecha_hasta==null) {

                    $sql="SELECT * from servidor_auditoria WHERE serv_fecha >='$fecha_desde'  order by serv_fecha desc ";
                }elseif ($fecha_desde==null && $fecha_hasta) {


                    $sql="SELECT * from servidor_auditoria WHERE serv_fecha <='$fecha_hasta'  order by serv_fecha desc ";
                }


        }
        

       
        $consulta= mysqli_query($conn,$sql);

        while ($res=mysqli_fetch_array($consulta)) {

            $id=$res['id'];
            $tienda=$res['tienda'];
            $descripcion=$res['serv_des'];
            $imagen=$res['serv_img'];
            $serv_fecha=$res['serv_fecha']; ?>
    
    <tr>

        <td><?=$id?></td>
        <td><?=$tienda?></td>
        <td><?=$descripcion?></td>
        <td><?=$serv_fecha?></td>
        <td><img src="../uploads/img/<?=$imagen?>" style="height: 90px;" alt=""></td>

        <td><a href='edit.php?id=<?php echo $id?>' class='btn btn-info'>
                    <i class='fas fa-marker'></i>
        </a>
            <a href='delete.php?id=<?php echo $id?>' class='btn btn-danger'>
                    <i class='far fa-trash-alt'></i>
            </a></td>
        
     
    </tr>
      
    



        
 <?php     }}}
 
 
 include '../includes/excel.php';
 
 
 
 
 ?>

 </div>





    
</body>
</html>



<?php






?>