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


    <div class="empresa">
       
     
<center>
        <div class="card text-black mb-3" id="inf-empresa" >
            <div class="card-header" style="color:white;" >Datos de las sedes</div>
            <div class="card-body">

            <?php
            require '../includes/conexion.php';

            $sql="SELECT * from sedes";
            $query=mysqli_query($conn,$sql);

            while ($res=mysqli_fetch_array($query)) {

                $sedes_nom=$res['sedes_nom'];
                $rif=$res['rif'];
                $numero=$res['numero'];?>

            <h3 class="card-title"><?php echo "$sedes_nom" ?></h3>
            
            <p class="card-text"><b>RIF: </b><?php echo "$rif" ?></p>
            <p><b>Tlf:</b> <?php echo "$numero" ?></p>
            <hr>
        
        
        
        <?php   }?>
            
            
            
            
        </div>
        </center>  
            
           
            
            
       
        
     

        
    
    
    </div>
















    </body>
</html>