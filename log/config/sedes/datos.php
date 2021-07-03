<?php require_once '../includes/log.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    
    <title>Inicio</title>
</head>
<style>

.empresa #inf-empresa {
    border-radius: 10px;  
  background-color:  rgba(29, 27, 27, 0.205);
  box-shadow: 2px 2px 5px #999;
}

.empresa{
    height: 100vh;
  
    
    display: flex;
    align-items: center;
    justify-content: center;

}
.empresa #inf-empresa{
    width: 500px;
}
</style>
<body>
 

<?php

require_once '../includes/cabecera.php';


?>



    
       
     

<h2>Datos de las sedes</h2>
<table  class='table table-hover' id='tblData' >
                <thead>
                    <tr class='table-primary'>
                        <th scope='col' abbr='Starter'>Sede</th>
                        <th scope='col' abbr='Starter'>Rif</th>
                        <th scope='col' abbr='Starter'>Numero</th>
                
                        
                    
                        
                    </tr>
                </thead>
                
            <?php
            require '../includes/conexion.php';

            $sql="SELECT * from sedes";
            $query=mysqli_query($conn,$sql);

            while ($res=mysqli_fetch_array($query)) {

                $sedes_nom=$res['sedes_nom'];
                $rif=$res['rif'];
                $numero=$res['numero'];?>

<tr>
        <td><?=$sedes_nom?></td>
        <td><?=$rif?></td>
        <td><?=$numero?></td>
        

        </tr>

    
        
        
        
        <?php   }?>
       
        </table>
            
            















    </body>
</html>