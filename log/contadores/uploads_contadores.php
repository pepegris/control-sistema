
<?php

if (isset($_FILES) && isset($_POST)) {

    include '../includes/loading.php';
    require '../includes/conexion_control.php';

    $contador = $_FILES['excel'];
    $nombre=$contador['name'];
    $tipo=$contador['type'];

         $sedes_nom=isset ($_POST ['sedes_nom']) ? mysqli_real_escape_string($conn,$_POST ['sedes_nom'] ): false ;
        $fecha=isset($_POST ['fecha']) ? mysqli_real_escape_string($conn,$_POST['fecha']):false ;
        $con_des=isset($_POST ['con_des']) ? mysqli_real_escape_string($conn,$_POST['con_des']):false ;
  /* 
        $sedes_nom=$_POST ['sedes_nom'] ;
        $fecha=$_POST ['fecha'];
        $con_des=$_POST ['con_des'];  */
      
        if ($tipo =='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {

                        //ruta del destino del servidor
                        /*  $carpeta = $_SERVER['DOCUMENT_ROOT'] . '/jorinacha/control/log/uploads/documents/'; */ 

                         $carpeta = $_SERVER['DOCUMENT_ROOT'] . '/php/control/log/uploads/documents/'; 

                        //almacenando nombre y direccion de la excel
            
                        $excel='_'.$fecha.'_'.$nombre;
            
                        
                    
                        //mover excel a directorio temporal
                    
                        move_uploaded_file($_FILES['excel']['tmp_name'],$carpeta.$excel);

    if ($conn) {

    $sql= "INSERT INTO contador VALUES (null,'$sedes_nom','$con_des','$excel','$fecha',now())"; 
   /*  $sql= "INSERT INTO contador (tienda,con_des,con_img,con_fecha,fecha) VALUES ('$sedes_nom','$con_des','$excel','$fecha',now())";    */         
    $guardar = mysqli_query($conn,$sql);
                    
        if (!$guardar) {
        
        
        //mandando mensaje de error de la base de datos
                                        
        $error= mysqli_error($conn);
        echo "<br><center><h3>ERROR</h3></center>";
        echo "<h4>$error</h4>";
                                
                                            
        echo "<a href='contadores.php' class='btn btn-danger'>Salir</a>";
        die();
                                        
                                            
            }else {
                header('refresh:2;url= contadores.php');
                exit;
            }
                    
                                
                    
    } else {
                            
                                            
         echo "<a href='contadores.php' class='btn btn-danger'>Salir</a>";
         die("La conexión ha fallado: " . mysqli_connect_error());
    }
        
        
        }else {
        
            //REDIRECCIONAR DESPUES DE 5 SEGUNDOS
            header("Refresh:5;URL=contadores.php");
            echo "sube un archivo con un formato permitido: $tipo";
        }





   
    
}else {
                            
                                            
    echo "<a href='contadores.php' class='btn btn-danger'>Salir</a>";
    die("La conexión ha fallado: " . mysqli_connect_error());
}


?>