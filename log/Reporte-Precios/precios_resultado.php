<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
<?php 
include '../includes/cabecera.php'  ;
if (isset($_POST)) {
    $titulo=$_POST['art'];
    
}

?>
<br>
<center><h2> <?php echo $titulo; ?> </h2></center>
<br>

<table  class='table table-hover' id="tblData" >
        <thead>
            <tr class='table-primary'>
                <th scope='col' abbr='Starter'>Codigo Articulo</th>
                <th scope='col' abbr='Starter'>Articulos</th>
                <th scope='col' abbr='Starter'>Precio 1</th>
                <th scope='col' abbr='Starter'>Precio 2</th>
        
				
			<tr>
		</thead>

        
<?php 


if (isset($_POST)) {

    $articulos=$_POST['art'];
    $escala=$_POST['escala'];
  
    var_dump($_POST);
  
    $serverName = "SQL"; 
    $connectionInfo = array( "Database"=>"previa_a", "UID"=>"syncro", "PWD"=>"syncro");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);

   

 
    
   $solicitud="select co_art,art_des,prec_vta1,prec_vta5 from art where art_des like '%$articulos%' and art_des like '%$escala%'";
   $consulta= sqlsrv_query($conn,$solicitud);




    if ($consulta) {

        $contador;


       
    while ($row=sqlsrv_fetch_array($consulta)) {

        $descripcion = $row[art_des];  
        $codigo_articulo=$row[co_art];
        $precio_dolares=round($row[prec_vta5]);
        $precio_bolivares=round($row[prec_vta1]);
        
        
        


        echo "
        <form action='precios_actualizado.php' method='post' enctype='multipart/form-data'>

        <tr>
            <td for='codigo' >".$codigo_articulo."</td>
            <td>".$descripcion."</td>
            <td>$".$precio_dolares."</td>
            <td><input type='number' name='precio_nuevo' for='precio_nuevo' id=''></td>
            <td>Bs.".$precio_bolivares."</td>
            <td><input type='number' name='precio_nuevo_2' for='precio_nuevo' id=''></td>
        </tr> ";
        $contador ++;
    

   
}




    } else {

        echo "Error en la consulta datos ingresados erroneos";
       
    }
    





}

 



?>



        </table>   
        <center><input type='submit' value='Enviar'>
        </form></center>
        <br>
        <br>
        
    
</body>

</html>
