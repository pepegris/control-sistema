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
                        <th scope='col' abbr='Starter'>Estado</th>
                        <th scope='col' abbr='Starter'>Accion</th>
                
                        
                    
                        
                    </tr>
                </thead>
                
            <?php
            require '../includes/conexion_control.php';

            $sql="SELECT id,sedes_nom,rif,numero,estado_sede from sedes";
            $query=mysqli_query($conn,$sql);

            while ($res=mysqli_fetch_array($query)) {

                $id=$res['id'];
                $sedes_nom=$res['sedes_nom'];
                $rif=$res['rif'];
                $numero=$res['numero'];
                $estado = $res['estado_sede'] ?>

<tr>
        <td><?=$sedes_nom?></td>
        <td><?=$rif?></td>
        <td><?=$numero?></td>
        <td><?=$estado?></td>
        <td><a href="edit.php?id=<?=$id?>" class="btn btn-warning"><i class='fas fa-marker'></i></a></td>


        

        </tr>

    
        
        
        
        <?php   }?>
       
        </table>
            
            















    </body>
</html>