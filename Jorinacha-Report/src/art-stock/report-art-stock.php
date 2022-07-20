<?php
require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

if ($_POST) {


    $linea=$_POST['linea'];
    $almacen=$_POST['sedes'];

    var_dump($linea);
    var_dump($almacen);
?>










<?php
}else {
    header("location: form.php");
}




include '../../includes/footer.php';?>