<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Autentica</title>
  </head>
  <body>
    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>
<?PHP
error_reporting(0);
session_start();



 $userposs= $_SESSION['user'] = $_POST['user'];
 $passposs= $_SESSION['pass'] = $_POST['pass'];

 $respuesta='
 <div class="container mt-4">  
 <div class="alert alert-danger col-sm-6 centro" role="alert">
  No eres ni 
  <a class="alert-link">Usuario ni Administrador</a>. 
  Intenta de nuevo
 </div>
 </div>';

    //admin
    $admin="samsung";
    //User
    $user = "saturno";
    //Pass
    $pass="jupiter23";


    if ($userposs == $admin and $passposs == $pass) 
    {
        echo "Bienvenido Admin";
        header('Location: main.php');
        exit;
    }
    elseif ($userposs == $user and $passposs == $pass) 
    {
      echo "Bienvenido User";
        header('Location: main.php');
        exit;
    }
    else
    {
      echo $respuesta.'<div class="container mt-2"><a href="autentica.php" class="alert-link">Click para continuar</a></div>';
    }

?>

