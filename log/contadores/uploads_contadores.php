
<?php

include '../includes/loading.php';
    // recibimos los datos de la imagen

if (isset($_FILES) && isset($_POST)) {

        require_once '../includes/conexion_control.php'; 
        
        

        //ruta de la imagen
        $nombre_imagen = $_FILES['imagen']['name'];
        $tipo_imagen   = $_FILES['imagen']['type'];
        $tam_imagen    = $_FILES['imagen']['size'];

        $nombre_imagen2 = $_FILES['imagen2']['name'];
        $tipo_imagen2   = $_FILES['imagen2']['type'];
        $tam_imagen2    = $_FILES['imagen2']['size'];


        $nombre_imagen3 = $_FILES['imagen3']['name'];
        $tipo_imagen3   = $_FILES['imagen3']['type'];
        $tam_imagen3    = $_FILES['imagen3']['size'];
        
        //informacion para la base de dato
        $sede=isset ($_POST ['sede']) ? mysqli_real_escape_string($conn,$_POST ['sede'] ): false ;

        $impresora1=isset($_POST ['impresora1']) ? mysqli_real_escape_string($conn,$_POST['impresora1']):false ;
        $impresora2=isset($_POST ['impresora2']) ? mysqli_real_escape_string($conn,$_POST['impresora2']):false ;
        $impresora3=isset($_POST ['impresora3']) ? mysqli_real_escape_string($conn,$_POST['impresora3']):false ;
        //$impresora4=isset($_POST ['impresora4']) ? mysqli_real_escape_string($conn,$_POST['impresora4']):false ;

        $serial1=isset($_POST ['serial1']) ? mysqli_real_escape_string($conn,$_POST['serial1']):false ;
        $serial2=isset($_POST ['serial2']) ? mysqli_real_escape_string($conn,$_POST['serial2']):false ;
        $serial3=isset($_POST ['serial3']) ? mysqli_real_escape_string($conn,$_POST['serial3']):false ;
        //$serial4=isset($_POST ['serial4']) ? mysqli_real_escape_string($conn,$_POST['serial4']):false ;

        $fecha=isset($_POST ['con_fecha']) ? mysqli_real_escape_string($conn,$_POST['con_fecha']):false ;

        $con_des=isset($_POST ['con_des']) ? mysqli_real_escape_string($conn,$_POST['con_des']):false ;

        $inicial1=isset($_POST ['inicial1']) ? mysqli_real_escape_string($conn,$_POST['inicial1']):false ;
        $inicial2=isset($_POST ['inicial2']) ? mysqli_real_escape_string($conn,$_POST['inicial2']):false ;
        $inicial3=isset($_POST ['inicial3']) ? mysqli_real_escape_string($conn,$_POST['inicial3']):false ;
        // $inicial4=isset($_POST ['inicial4']) ? mysqli_real_escape_string($conn,$_POST['inicial4']):false ;
        
       

        
  //  if ($tam_imagen <= 10000000) {

      //  if ($tipo_imagen=="image/jpeg" or $tipo_imagen=="image/jpg" or $tipo_imagen=="image/png" or $tipo_imagen=="image/gif"  ) {

        
         /*    //ruta del destino del servidor
            $carpeta = $_SERVER['DOCUMENT_ROOT'] . '/php/control/log/uploads/img/';

         

            if ($nombre_imagen3==null ) {

                //almacenando nombre y direccion de la imagen
                $contador_img=$sede.'_'.$fecha.'_'.$nombre_imagen;
                $contador_img2=$sede.'_'.$fecha.'_'.$nombre_imagen2;
                
 
                //mover imagen a directorio temporal
            
                move_uploaded_file($_FILES['imagen']['tmp_name'],$carpeta.$contador_img);
                move_uploaded_file($_FILES['imagen2']['tmp_name'],$carpeta.$contador_img2);
                

                
            } else {

                 //almacenando nombre y direccion de la imagen
                 $contador_img=$sede.'_'.$fecha.'_'.$nombre_imagen;
                 $contador_img2=$sede.'_'.$fecha.'_'.$nombre_imagen2;
                 $contador_img3=$sede.'_'.$fecha.'_'.$nombre_imagen3;
  
                 //mover imagen a directorio temporal
             
                 move_uploaded_file($_FILES['imagen']['tmp_name'],$carpeta.$contador_img);
                 move_uploaded_file($_FILES['imagen2']['tmp_name'],$carpeta.$contador_img2);
                 move_uploaded_file($_FILES['imagen3']['tmp_name'],$carpeta.$contador_img3);

                 
            }
             */

            

                //insertando datos en la base de dato

                if ($conn) {


                    if ($impresora2 == null) {

                        $sql= "INSERT INTO contador VALUES (null,'$sede','$con_des','$contador_img','$contador_img2','$contador_img3',
                                                        '$impresora1',null,null,
                                                        '$serial1',null,null,
                                                        $inicial1,null,null,
                                                        '$fecha',now())";

            
                    } else {
                        
                        $sql= "INSERT INTO contador VALUES (null,'$sede','$con_des','$contador_img','$contador_img2','$contador_img3',
                                                        '$impresora1','$impresora2','$impresora3',
                                                        '$serial1','$serial2','$serial3',
                                                        $inicial1,$inicial2,$inicial3,
                                                        '$fecha',now())";
                        
                    }
                    

                    
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


    
       
/*IMAGENES IMAGENES IMAGENES IMAGENES         
            }else {
                echo "<center><h3>Por favor suba una imagen valida /JPG/JPEG/PNG/GIF: </h3> <p>$tipo_imagen</p></center>";
                echo "<a href='contadores.php' class='btn btn-danger'>Salir</a>";
                                die();
            }
    
     }else {
        echo "<center><h3>Ingrese una imagen de un tamnaño inferior a 10MB: </h3> <p>$tam_imagen</p></center>";
        echo "<a href='contadores.php' class='btn btn-danger'>Salir</a>";
                        die();
    } */


}else{
        header('refresh:2;url= contadores.php');
        exit;
    }

?>