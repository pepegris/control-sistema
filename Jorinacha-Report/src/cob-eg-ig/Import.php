<?php
  require '../../includes/log.php';
  include '../../includes/loading.php';
  include '../../services/sqlserver.php';
  include '../../services/adm/cob-eg-ig/import.php';

if (isset($_POST)) {


    $scripts=$_POST['scripts'];

    if ($scripts== 'backups') {

      $query=getImport();

      if ($query==true) {
        $mensaje='Se realizo los respaldos correctamente';
      }else {
        $mensaje='No se pudo realizar los respaldos ERROR IN THE DATABASE';
      }

    }elseif ($scripts== 'restore') {

        $query=getRestore();
      
    }else{

        header('refresh:1;url= Import-database.php');

    }

    echo "<center><h3>$mensaje</h3></center>";
    echo "<center><a href='Import-database.php' class='btn btn-success'>Volver</a></center>";



} else {
    header('refresh:1;url= Import-database.php');
    exit;
}
