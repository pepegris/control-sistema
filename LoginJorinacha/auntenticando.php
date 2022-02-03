
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='shortcut icon' href='favicon.ico' />
    <title>Sistema</title>
</head>

<?php
require 'bd/conexion-mysql.php';

session_start();

 if (isset($_POST)) {


    $usuario=$_POST['user'];
    $clave=$_POST['pass'];

    $sql="SELECT * FROM usuario WHERE usuario ='$usuario'";
    $login=mysqli_query($conn,$sql);
    $array = mysqli_fetch_array($login);


    $verify=password_verify($clave,$array['clave']);

    if ($verify ) {

        if ($array['usuario'] == $usuario and $verify == $clave ) {


            $_SESSION['username']=$usuario;

         //   var_dump($_SESSION['username']);
            header("location:inicio.php");
        

            
        }else {

            echo "error1";
            header("location:autentica.php");
            
        }
        
    }else {
        echo "error 2";
        header("location:autentica.php");
    }

    
/* 
    if ($login && mysqli_num_rows($login) == 1 ) {

        $user = mysqli_fetch_assoc($login);

        $verify=password_verify($clave,$user['clave']);

        var_dump($user);

        if ($verify) {

            $cuenta_on=$_SESSION['usuario'];


            //si logra ingresar borrar el erro de la sesion

            if (isset( $_SESSION['error_login'])) {
                
                session_unset( $_SESSION['error_login']);
            }

           
        }else {

            $_SESSION['error_login']='error';
            echo "error";
        }


   

        
    }else {
        echo "error";
    } */


  /*   
    if ($usuario == 'sistema' && $clave == 'sistema123' ) {

        header('refresh:5;url=  inicio.php');

    //    header('refresh:5;url= inicio.php');
        exit;

        
    } else {
        
        header('refresh:5;url= autentica.php');
        exit;


    } */
    
    
} 
?>
</html>