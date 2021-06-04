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

 <center><h2>Equipos <?=$sedes_nom?></h2></center>
<table  class='table table-hover' id="tblData" >
        <thead>
        
            <tr class='table-primary'>
                <th scope='col' abbr='Starter'>Nº</th>
                <th scope='col' abbr='Starter'>Destino</th>
                <th scope='col' abbr='Starter'>Estado</th>
                <th scope='col' abbr='Starter'>Descripcion</th>
                <th scope='col' abbr='Starter'>Fecha</th>
                <th scope='col' abbr='Starter'>Accion</th>
                
               
				
			<tr>
		</thead>

<?php 






    if ($conn) {

        

        $sql;
        if ($sedes_nom) {

            $sql="SELECT * from equipos WHERE usuario='$sedes_nom' and eq_fecha BETWEEN '$fecha_desde' and '$fecha_hasta' order by eq_fecha desc ";

        }elseif ($sedes_nom==null) {

            $sql="SELECT * from equipos WHERE eq_fecha BETWEEN '$fecha_desde' and '$fecha_hasta' order by eq_fecha desc ";
        }
        

       
        $consulta= mysqli_query($conn,$sql);

        while ($res=mysqli_fetch_array($consulta)) {

            $id=$res['id'];
            $usuario=$res['usuario'];
            $equipo=$res['equipo'];
            $estado=$res['estado'];
            $eq_fecha=$res['eq_fecha'];
            $eq_des=$res['eq_des']; ?>
    
    <tr>
    <td><?=$id?></td>
    <td><?=$usuario?></td>
    <td><?=$estado?></td>
    <td><?=$eq_des?></td>
    <td><?=$eq_fecha?></td>
    <td><a href='edit.php?id=<?php echo $id?>' class='btn btn-info'>
                <i class='fas fa-marker'></i>
       </a>
        <a href='delete.php?id=<?php echo $id?>' class='btn btn-danger'>
                <i class='far fa-trash-alt'></i>
        </a>
      <!-- ORDEN DE ENTREGA -->
        <a href='orden_entrega.php?id=<?php echo $id?>' class='btn btn-success'>
        <i class="fa fa-print" aria-hidden="true"></i>

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