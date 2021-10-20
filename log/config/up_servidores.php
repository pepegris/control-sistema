
<?php
require_once '../includes/log.php';
if ( isset($_POST)) {

    include '../includes/loading.php';
    require '../includes/conexion_control.php';

        $nombre_imagen = $_FILES['imagen']['name'];
        $tipo_imagen   = $_FILES['imagen']['type'];
        $tam_imagen    = $_FILES['imagen']['size'];

        $tienda=isset ($_POST ['tienda']) ? mysqli_real_escape_string($conn,$_POST ['tienda'] ): false ;
        $serv_fecha=isset($_POST ['serv_fecha']) ? mysqli_real_escape_string($conn,$_POST['serv_fecha']):false ;
        $serv_des=isset($_POST ['serv_des']) ? mysqli_real_escape_string($conn,$_POST['serv_des']):false ;
      
        

   if ($nombre_imagen == ''  ) {
        if ($conn) {

  // $sql= "INSERT INTO servidor_auditoria VALUES (null,'$tienda','$serv_des',null,'$serv_fecha',now())";
    $sql= "UPDATE servidor_auditoria SET  serv_des='$serv_des', serv_fecha='$serv_fecha' WHERE tienda ='$tienda' ";
                    
    $guardar = mysqli_query($conn,$sql);
    echo "<br><center><h3>Cargando Servidor sin imagen</h3></center>";
        if (!$guardar) {
        
        
        //mandando mensaje de error de la base de datos
                                        
        $error= mysqli_error($conn);
        echo "<br><center><h3>ERROR</h3></center>";
        echo "<h4>$error</h4>";
                                
                                            
        echo "<a href='servidores_buscar.php' class='btn btn-danger'>Salir</a>";
        die();
                                        
                                            
            }else {
                header('refresh:2;url= servidores_buscar.php');
                exit;
            }
                    
                                
                    
    } else {
                            
                                            
         echo "<a href='servidores_buscar.php' class='btn btn-danger'>Salir</a>";
         die("La conexión ha fallado: " . mysqli_connect_error());
    }
        
        
        

   }elseif ( $tipo_imagen=="image/jpeg" or $tipo_imagen=="image/jpg" or $tipo_imagen=="image/png" or $tipo_imagen=="image/gif" ) {
   


        //ruta del destino del servidor
        $carpeta = $_SERVER['DOCUMENT_ROOT'] . '/php/control/log/uploads/img/servidores/';



        //almacenando nombre y direccion de la imagen

        $imagen=$serv_fecha.'_'.$tienda.'_'.$nombre_imagen;

        
    
        //mover imagen a directorio temporal
    
        move_uploaded_file($_FILES['imagen']['tmp_name'],$carpeta.$imagen);

        if ($conn) {

            //$sql= "INSERT INTO fiscal VALUES (null,'$tienda','$serv_des','$imagen','$serv_fecha',now())";
            $sql= "UPDATE servidor_auditoria SET  serv_des='$serv_des', serv_img='$imagen' , serv_fecha='$serv_fecha' WHERE tienda ='$tienda' ";
                            
            $guardar = mysqli_query($conn,$sql);
                            
                if (!$guardar) {
                
                
                //mandando mensaje de error de la base de datos
                                                
                $error= mysqli_error($conn);
                echo "<br><center><h3>ERROR</h3></center>";
                echo "<h4>$error</h4>";
                                        
                                                    
                echo "<a href='servidores_buscar.php' class='btn btn-danger'>Salir</a>";
                die();
                                                
                                                    
                    }else {
                        header('refresh:2;url= servidores_buscar.php');
                        exit;
                    }
                            
                                        
                            
            } else {
                                    
                                                    
                 echo "<a href='servidores_buscar.php' class='btn btn-danger'>Salir</a>";
                 die("La conexión ha fallado: " . mysqli_connect_error());
            }




    }else {

        //REDIRECCIONAR 
        echo "<br><center><h3>sube un archivo con un formato permitido:</h3> $tipo_imagen </center>";
        echo "<a href='servidores_buscar.php' class='btn btn-danger'>Salir</a>";
        die();
    
   }




   
    
}


?>