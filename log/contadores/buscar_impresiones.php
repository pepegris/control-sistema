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

 <center><h2>Contadores</h2><p>Desde <?=$fecha_desde?> hasta <?=$fecha_hasta?></p></center>
<table  class='table table-hover' id="tblData" >
        <thead>
        
            <tr class='table-primary'>

                <th scope='col' abbr='Starter'>Sede</th>
                <th scope='col' abbr='Starter'>Contador</th>
                <th scope='col' abbr='Starter'>Impresiones</th>
                <th scope='col' abbr='Starter'>Diferencia</th>
                
                
                
               
				
			<tr>
		</thead>

<?php 






    if ($conn) {

        $sql;
        if ($sedes_nom=='todos') {

            $sql="SELECT * from contador WHERE con_fecha BETWEEN '$fecha_desde' and '$fecha_hasta' "; 

        }else {

            $sql="SELECT * from contador WHERE tienda='$sedes_nom' and con_fecha BETWEEN '$fecha_desde' and '$fecha_hasta' ";
            
        }

        

     
                                              //calculando los contadores del mes anterior
 /*calculando los contadores del mes anterior*/       require 'includes/almacenando_contador.php'; //calculando los contadores del mes anterior
                                                 //calculando los contadores del mes anterior  
                                                 


      $consulta= mysqli_query($conn,$sql);
        
        
        while ($res=mysqli_fetch_array($consulta)) {

            $id=$res['id'];
            $tienda=$res['tienda'];
            $con_img=$res['con_img'];
            $impresora1=$res['impresora1'];
            $impresora2=$res['impresora2'];
            $impresora3=$res['impresora3'];
            $serial_imp1=$res['serial_imp1'];
            $serial_imp2=$res['serial_imp2'];
            $serial_imp3=$res['serial_imp3'];
            $con_fecha=$res['con_fecha'];
            $con_des=$res['con_des']; 
            $inicial1=$res ['inicial1'];
            $inicial2=$res ['inicial2'];
            $inicial3=$res ['inicial3'];

            //almacenando los contadores del mes anterior en la variable $final
            require 'includes/contador_inicial.php';
             
            if ($impresora2 == null ) {

                

                echo "
                <tr>
                <td>$tienda</td>
                <td>$impresora1</td>
                <td>$serial_imp1</td>

                <td>$final</td>
                
                <td>$inicial1</td>
                <td>$con_des</td>

                
                <td><img src='../uploads/img/$con_img' style='height: 90px;' alt=''>
                <a href='../uploads/img/$con_img' download='$tienda' class='btn btn-success'>Descargar</a></td>
                </tr>";
                
                





            } else {

                

                require 'includes/contador.php';
                

                echo "
                <tr>
                <td>$tienda</td>
                <td>$impresora1 <br> $impresora2 <br> $impresora3</td>
                <td>$serial_imp1 <br> $serial_imp2 <br> $serial_imp3</td>

                <td>$contador[0] <br> $contador[1] <br> $contador[2] </td>

                <td>$inicial1 <br> $inicial2 <br> $inicial3 </td>
                <td>$con_des</td>

                

                <td><img src='../uploads/img/$con_img' style='height: 90px;' alt=''>
                <a href='../uploads/img/$con_img' download='$tienda' class='btn btn-success'>Descargar</a></td>
                </tr>
                  ";

            }
            
              
            

    




        
     }}
 
 
 include '../includes/excel.php';
 mysqli_close($conn);
 
}
 
 
 ?>

 </div>





    
</body>
</html>



<?php






?>