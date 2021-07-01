<?php

 



 session_start();

$cuenta_on=$_SESSION['username'];

if (!isset($cuenta_on)) {
    header("location:../inicio.php");
}

$cuenta_on = ucwords($cuenta_on); 


 
?>


<a href="#" class="btn btn-dark" style="position: fixed;
    bottom: 90%;
    right: 5%;
    font-size:30px;" >Usuario: <?=$cuenta_on?></a>
