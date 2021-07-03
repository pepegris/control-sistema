<?php

 



 session_start();

$cuenta_on=$_SESSION['username'];

if (!isset($cuenta_on)) {
    header("location:../salir.php");
}elseif ($cuenta_on != 'sistema') {
        header("location:../salir.php");
}

$cuenta_on = ucwords($cuenta_on); 

 
 
?>