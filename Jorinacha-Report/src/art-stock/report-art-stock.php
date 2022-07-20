<?php
require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

if ($_POST) {


    
    $linea=$_POST['linea'];
    for ($i=5; $i < count($_POST) ; $i+=10) { 
        $sedes [] = $_POST[$i];
    }

    var_dump($linea);
    var_dump( $sedes);
?>










<?php
}else {
    header("location: form.php");
}




include '../../includes/footer.php';?>