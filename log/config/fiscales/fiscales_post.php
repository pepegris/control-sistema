
<?php
require_once '../includes/log.php';
if ( isset($_POST)) {

    include '../includes/loading.php';
    require '../includes/conexion_control.php';

        $nombre_imagen = $_FILES['imagen']['name'];
        $tipo_imagen   = $_FILES['imagen']['type'];
        $tam_imagen    = $_FILES['imagen']['size'];

        $tienda=isset ($_POST ['tienda']) ? mysqli_real_escape_string($conn,$_POST ['tienda'] ): false ;
        $fis_marca1=isset($_POST ['fis_marca1']) ? mysqli_real_escape_string($conn,$_POST['fis_marca1']):false ;
        $fis_marca2=isset($_POST ['fis_marca2']) ? mysqli_real_escape_string($conn,$_POST['fis_marca2']):false ;
        $fis_marca3=isset($_POST ['fis_marca3']) ? mysqli_real_escape_string($conn,$_POST['fis_marca3']):false ;
        $fis_marca4=isset($_POST ['fis_marca4']) ? mysqli_real_escape_string($conn,$_POST['fis_marca4']):false ;

        $fis_modelo1=isset($_POST ['fis_modelo1']) ? mysqli_real_escape_string($conn,$_POST['fis_modelo1']):false ;
        $fis_modelo2=isset($_POST ['fis_modelo2']) ? mysqli_real_escape_string($conn,$_POST['fis_modelo2']):false ;
        $fis_modelo3=isset($_POST ['fis_modelo3']) ? mysqli_real_escape_string($conn,$_POST['fis_modelo3']):false ;
        $fis_modelo4=isset($_POST ['fis_modelo4']) ? mysqli_real_escape_string($conn,$_POST['fis_modelo4']):false ;

        $fis_serial1=isset($_POST ['fis_serial1']) ? mysqli_real_escape_string($conn,$_POST['fis_serial1']):false ;
        $fis_serial2=isset($_POST ['fis_serial2']) ? mysqli_real_escape_string($conn,$_POST['fis_serial2']):false ;
        $fis_serial3=isset($_POST ['fis_serial3']) ? mysqli_real_escape_string($conn,$_POST['fis_serial3']):false ;
        $fis_serial4=isset($_POST ['fis_serial4']) ? mysqli_real_escape_string($conn,$_POST['fis_serial4']):false ;

        $fis_nregistro1=isset($_POST ['fis_nregistro1']) ? mysqli_real_escape_string($conn,$_POST['fis_nregistro1']):false ;
        $fis_nregistro2=isset($_POST ['fis_nregistro2']) ? mysqli_real_escape_string($conn,$_POST['fis_nregistro2']):false ;
        $fis_nregistro3=isset($_POST ['fis_nregistro3']) ? mysqli_real_escape_string($conn,$_POST['fis_nregistro3']):false ;
        $fis_nregistro4=isset($_POST ['fis_nregistro4']) ? mysqli_real_escape_string($conn,$_POST['fis_nregistro4']):false ;

        $estado1=isset($_POST ['estado1']) ? mysqli_real_escape_string($conn,$_POST['estado1']):false ;
        $estado2=isset($_POST ['estado2']) ? mysqli_real_escape_string($conn,$_POST['estado2']):false ;
        $estado3=isset($_POST ['estado3']) ? mysqli_real_escape_string($conn,$_POST['estado3']):false ;
        $estado4=isset($_POST ['estado4']) ? mysqli_real_escape_string($conn,$_POST['estado4']):false ;

        $fis_fecha=isset ($_POST ['fis_fecha']) ? mysqli_real_escape_string($conn,$_POST ['fis_fecha'] ): false ;
      
      
        

   if ($nombre_imagen == ''  ) {
        if ($conn) {

  // $sql= "INSERT INTO servidor_auditoria VALUES (null,'$tienda','$serv_des',null,'$serv_fecha',now())";
    $sql= "UPDATE fiscal_auditoria SET fis_marca1 ='$fis_marca1',
    fis_marca2 ='$fis_marca2',
    fis_marca3 ='$fis_marca3',
    fis_marca4 ='$fis_marca4',
    fis_modelo1 ='$fis_modelo1',
    fis_modelo2 ='$fis_modelo2',
    fis_modelo3 ='$fis_modelo3',
    fis_modelo4 ='$fis_modelo4',
    fis_serial1 ='$fis_serial1' ,
    fis_serial2 ='$fis_serial2' ,
    fis_serial3 ='$fis_serial3' ,
    fis_serial4 ='$fis_serial4' ,
    fis_nregistro1 ='$fis_nregistro1' ,
    fis_nregistro2 ='$fis_nregistro2' ,
    fis_nregistro3 ='$fis_nregistro3' ,
    fis_nregistro4 ='$fis_nregistro4' ,
    fis_des='',
    fis_img='',
    fis_fecha='$fis_fecha' ,
    now(), 
    estado1 ='$estado1',
    estado2 ='$estado2',
    estado3 ='$estado3',
    estado4 ='$estado4'
  
     WHERE tienda ='$tienda' ";
                    
    $guardar = mysqli_query($conn,$sql);
    echo "<br><center><h3>Cargando Servidor sin imagen</h3></center>";
        if (!$guardar) {
        
        
        //mandando mensaje de error de la base de datos
                                        
        $error= mysqli_error($conn);
        echo "<br><center><h3>ERROR</h3></center>";
        echo "<h4>$error</h4>";
                                
                                            
        echo "<a href='../fiscales.php' class='btn btn-danger'>Salir</a>";
        die();
                                        
                                            
            }else {
                header('refresh:2;url= ../fiscales.php');
                exit;
            }
                    
                                
                    
    } else {
                            
                                            
         echo "<a href='../fiscales.php' class='btn btn-danger'>Salir</a>";
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

            $sql= "UPDATE fiscal_auditoria SET fis_marca1 ='$fis_marca1',
                    fis_marca2 ='$fis_marca2',
                    fis_marca3 ='$fis_marca3',
                    fis_marca4 ='$fis_marca4',
                    fis_modelo1 ='$fis_modelo1',
                    fis_modelo2 ='$fis_modelo2',
                    fis_modelo3 ='$fis_modelo3',
                    fis_modelo4 ='$fis_modelo4',
                    fis_serial1 ='$fis_serial1' ,
                    fis_serial2 ='$fis_serial2' ,
                    fis_serial3 ='$fis_serial3' ,
                    fis_serial4 ='$fis_serial4' ,
                    fis_nregistro1 ='$fis_nregistro1' ,
                    fis_nregistro2 ='$fis_nregistro2' ,
                    fis_nregistro3 ='$fis_nregistro3' ,
                    fis_nregistro4 ='$fis_nregistro4' ,
                    null,
                    fis_img='$imagen',
                    fis_fecha='$fis_fecha' ,
                    now(), 
                    estado1 ='$estado1',
                    estado2 ='$estado2',
                    estado3 ='$estado3',
                    estado4 ='$estado4'
                
                    WHERE tienda ='$tienda' ";
                            
            $guardar = mysqli_query($conn,$sql);
                            
                if (!$guardar) {
                
                
                //mandando mensaje de error de la base de datos
                                                
                $error= mysqli_error($conn);
                echo "<br><center><h3>ERROR</h3></center>";
                echo "<h4>$error</h4>";
                                        
                                                    
                echo "<a href='../fiscales.php' class='btn btn-danger'>Salir</a>";
                die();
                                                
                                                    
                    }else {
                        header('refresh:2;url= ../fiscales.php');
                        exit;
                    }
                            
                                        
                            
            } else {
                                    
                                                    
                 echo "<a href='../fiscales.php' class='btn btn-danger'>Salir</a>";
                 die("La conexión ha fallado: " . mysqli_connect_error());
            }




    }else {

        //REDIRECCIONAR 
        echo "<br><center><h3>sube un archivo con un formato permitido:</h3> $tipo_imagen </center>";
        echo "<a href='../fiscales.php' class='btn btn-danger'>Salir</a>";
        die();
    
   }




   
    
}


?>