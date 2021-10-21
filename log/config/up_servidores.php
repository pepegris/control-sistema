
<?php
require_once '../includes/log.php';
if ( isset($_POST)) {

    include '../includes/loading.php';
    require '../includes/conexion_control.php';

        $nombre_imagen = $_FILES['imagen']['name'];
        $tipo_imagen   = $_FILES['imagen']['type'];
        $tam_imagen    = $_FILES['imagen']['size'];

        $tienda=isset ($_POST ['tienda']) ? mysqli_real_escape_string($conn,$_POST ['tienda'] ): false ;
        $serv_mac=isset($_POST ['serv_mac']) ? mysqli_real_escape_string($conn,$_POST['serv_mac']):false ;
        $serv_proc=isset($_POST ['serv_proc']) ? mysqli_real_escape_string($conn,$_POST['serv_proc']):false ;
        $serv_ram=isset($_POST ['serv_ram']) ? mysqli_real_escape_string($conn,$_POST['serv_ram']):false ;
        $serv_disc=isset($_POST ['serv_disc']) ? mysqli_real_escape_string($conn,$_POST['serv_disc']):false ;
        $serv_vid=isset($_POST ['serv_vid']) ? mysqli_real_escape_string($conn,$_POST['serv_vid']):false ;
        $serv_red=isset($_POST ['serv_red']) ? mysqli_real_escape_string($conn,$_POST['serv_red']):false ;
        $serv_des=isset($_POST ['serv_des']) ? mysqli_real_escape_string($conn,$_POST['serv_des']):false ;
        $serv_fecha=isset($_POST ['serv_fecha']) ? mysqli_real_escape_string($conn,$_POST['serv_fecha']):false ;
      
        

   if ($nombre_imagen == ''  ) {
        if ($conn) {

  // $sql= "INSERT INTO servidor_auditoria VALUES (null,'$tienda','$serv_des',null,'$serv_fecha',now())";
    $sql= "UPDATE servidor_auditoria SET serv_mac='$serv_mac', serv_proc='$serv_proc', serv_ram='$serv_ram', serv_disc='$serv_disc', serv_vid='$serv_vid', serv_red='$serv_red', serv_des='$serv_des', serv_fecha='$serv_fecha' WHERE tienda ='$tienda' ";
                    
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
            $sql= "UPDATE servidor_auditoria SET serv_mac='$serv_mac', serv_proc='$serv_proc', serv_ram='$serv_ram', serv_disc='$serv_disc', serv_vid='$serv_vid', serv_red='$serv_red', serv_des='$serv_des', serv_fecha='$serv_fecha' WHERE tienda ='$tienda' ";
                            
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