<?php require_once '../includes/log.php';?>
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
<style>
    #contadores {
        margin-left: 95px;
    }
    table td{
        color: black;
    }
    
</style>
<body>

<?php 
if (isset($_POST)) {

    require '../includes/conexion_control.php';
    include '../includes/cabecera.php';

    $sedes_nom=isset ($_POST ['sedes_nom']) ? mysqli_real_escape_string($conn,$_POST ['sedes_nom'] ): false ;
 /*    $fecha_desde=isset($_POST ['fecha_desde']) ? mysqli_real_escape_string($conn,$_POST['fecha_desde']):false ;
    $fecha_hasta=isset($_POST ['fecha_hasta']) ? mysqli_real_escape_string($conn,$_POST['fecha_hasta']):false ; */
    
    
 ?>
 

 <center><h2>Servidores <?=$sedes_nom?></h2></center>
<table  class='table table-hover' id="tblData" >
        <thead>
        
            <tr class='table-primary'>
  
                <th scope='col' abbr='Starter'>Tienda</th>
                <th scope='col' abbr='Starter'>Fiscal1</th>
                <th scope='col' abbr='Starter'>Fiscal2</th>
                <th scope='col' abbr='Starter'>Fiscal3</th>
                <th scope='col' abbr='Starter'>Fiscal4</th>
                <th scope='col' abbr='Starter'>Fecha</th>
                <th scope='col' abbr='Starter'>Imagen</th>
                <th scope='col' abbr='Starter'>Accion</th>
                
               
				
			<tr>
		</thead>

<?php 






    if ($conn) {

        $sql;
        if ($sedes_nom=="Todos") {


           
             $sql="SELECT * from fiscal_auditoria  ";

               
           
        }else{


            $sql="SELECT * from fiscal_auditoria WHERE tienda='$sedes_nom' ";


        }
        

       
        $consulta= mysqli_query($conn,$sql);

        while ($res=mysqli_fetch_array($consulta)) {

        

            $id=$res['id'];
            $tienda=$res['tienda'] ;
        $fis_marca1=$res['fis_marca1'];
        $fis_marca2=$res['fis_marca2'];
        $fis_marca3=$res['fis_marca3'];
        $fis_marca4=$res['fis_marca4'];

        $fis_modelo1=$res['fis_modelo1'];
        $fis_modelo2=$res['fis_modelo2'];
        $fis_modelo3=$res['fis_modelo3'];
        $fis_modelo4=$res['fis_modelo4'];

        $fis_serial1=$res['fis_serial1'];
        $fis_serial2=$res['fis_serial2'];
        $fis_serial3=$res['fis_serial3'];
        $fis_serial4=$res['fis_serial4'];

        $fis_nregistro1=$res['fis_nregistro1'];
        $fis_nregistro2=$res['fis_nregistro2'];
        $fis_nregistro3=$res['fis_nregistro3'];
        $fis_nregistro4=$res['fis_nregistro4'];

        $estado1=$res['estado1'];
        $estado2=$res['estado2'];
        $estado3=$res['estado3'];
        $estado4=$res['estado4'];

        $imagen=$res['fis_img'];
        $fis_fecha=$res['fis_fecha'];?>
    
    <tr>


        <td><?=$tienda?></td>
        <!-- FISCAL1 -->
        <td>
        <?=$fis_marca1;?><br>
       
        <?=$fis_modelo1;?><br>


        <?=$fis_serial1;?><br>


        <?=$fis_nregistro1;?><br>


        <?=$estado1;?>
       
        </td>
             <!-- FISCAL2 -->
        <td>
        <?=$fis_marca2;?><br>
   
        <?=$fis_modelo2;?><br>

        <?=$fis_serial2;?><br>

        <?=$fis_nregistro2;?><br>

        <?=$estado2;?>
        </td>

            <!-- FISCAL 3 -->
        <td>
        <?=$fis_marca3;?><br>

        <?=$fis_modelo3;?><br>

        <?=$fis_serial3;?><br>

        <?=$fis_nregistro3;?><br>

        <?=$estado3;?>
        </td>
        <!-- FISCAL 4 -->
        <td>
        <?= $fis_marca4;?><br>

        <?=$fis_modelo4;?><br>

        <?=$fis_serial4;?><br>

        <?=$fis_nregistro4;?><br>

        <?=$estado4;?>
        </td>

        <td><?=$fis_fecha?></td>
        <td><img src="/log/uploads/img/fiscales/<?=$imagen?>" style="height: 90px;" alt=""></td>

        <td><?php


        if ($cuenta_on=='sistema') {
            
            echo "<a href='edit_fiscales.php?id=<?php echo $id?>' class='btn btn-info'>
            <i class='fas fa-marker'></i></a>";
        }else {
            echo "<a href='#' class='btn btn-info'>
            <i class='fas fa-marker'></i></a>";
        }

        
        
        
        ?>
            <!-- <a href='delete_fiscales.php?id=<?php echo $id?>' class='btn btn-danger'>
                    <i class='far fa-trash-alt'></i>
            </a> -->
        </td>
        
     
    </tr>
      
    



        
 <?php     }}}
 
 
 include '../includes/excel.php';
 
 
 
 
 ?>

 </div>





    
</body>
</html>



<?php






?>