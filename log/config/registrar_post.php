<?php


include 'includes/loading.php';

if (isset($_POST)) {
   // require '../includes/log.php';
    require '../includes/conexion_mysql.php';

   // var_dump($_POST);
  $usuario=isset($_POST ['nombre']) ? mysqli_real_escape_string($conn,trim($_POST ['nombre'])) :false;

  $telefono=isset($_POST ['telefono']) ? mysqli_real_escape_string($conn,$_POST ['telefono']) : false;
  $password=isset($_POST ['pass']) ? mysqli_real_escape_string($conn,trim($_POST ['pass'])) :false;

        //validar formulario

      

        if ($conn) {

       

              
            


            //CIFRAR LA CONTRASEÑA !!!!!!!!!!!!!
 
          $password_segura = password_hash($password,PASSWORD_BCRYPT,['cost'=>4]);
       
         // var_dump(password_verify($password,$password_segura));
            

         //insertar usuario en la base de datos
        $sql= "INSERT INTO  usuario VALUES (null,'$usuario','$password_segura','$telefono',now())";
 
        $guardar = mysqli_query($conn,$sql);

        //mostrar error
        if (!$guardar) {
             
          $error= mysqli_error($conn);
          echo "<br><center><h3>ERROR</h3></center>";
          echo "<h4>$error</h4>";
           echo "<a href='registrar.php' class='btn btn-danger'>Salir</a>";
          die();
          
           
           
       }else {
           header('refresh:2;url= registrar.php');
           exit;
       }
              


          
         
  
              
   
      }else {
        header("location: registrar.php");
        exit;
    }

            
            
            
            
     






}else {
  header("location: registrar.php");
}
?>