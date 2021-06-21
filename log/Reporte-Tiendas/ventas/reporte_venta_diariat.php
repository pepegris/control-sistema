<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dirario</title>
</head>
<body>
<?php 
if (isset($_POST)) {

    
    include '../includes/cabecera.php';

    $sedes_nom=$_POST ['sedes_nom'];
    $fecha=$_POST ['fecha'];

    include '../includes/bd_serv.php';
    include '../includes/icono.php';
    
   // var_dump($_POST);

?>


 <center><h2><?= $servidor ?> Ventas del <?=$fecha?>  </h2></center>
<table  class='table table-hover' id="tblData" >
        <thead>
        
            <tr class='table-primary'>
                <th scope='col' abbr='Starter'>Nº Factura</th>
                <th scope='col' abbr='Starter'>Fecha</th>
                <th scope='col' abbr='Starter'>Monto</th>
                
                
               
				
			<tr>
		</thead> 

    <?php

               require 'includes/bd_serv.php';


    
/* variable para la validacion si hay conexion al servidor */
    $conexion=0;

if ($conn) {

 $sql="SELECT * from factura where fec_emis ='$fecha'";

$consulta=sqlsrv_query($conn,$sql);

 while ($datos =sqlsrv_fetch_array($consulta)) {
    
    $test = $datos[fact_num];
    
    $precio = round($datos[tot_neto]) ;

    $bolivares=number_format($precio, 2, ',', '.');

    
    $suma += $precio;



    $conexion+=1;
    //echo "<center>si funciona: $test - $precio";?>

    <tr>
        <td><?=$test?></td>
        <td><?=$fecha?></td>
        <td>Bs. <?=$bolivares?></td>
    </tr>




 <?php  } }?>




<?php


                


/* validacion si hay conexion al servidor */

if ($conexion == 0) {
    
    echo "<center><h1>ERROR</h1>";
    echo "<h3>No es Posible hacer conexion con la base de dato</h3>";
    echo "</center>";
    sqlsrv_close($conn);
}else {

    $total=number_format($suma, 2, ',', '.');
    echo "<tr><td></td><td class='text-right '>Total</td><td>Bs. $total </td> </tr>";
    include '../includes/excel.php';
    sqlsrv_close($conn);
}

      

    
    
}
    ?>

  
    
</body>
</html>