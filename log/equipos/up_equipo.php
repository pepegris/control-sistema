<?php

if (isset($_POST)) {

    include '../includes/loading.php';
    require '../includes/conexion_control.php';

    $usuario=isset ($_POST ['usuario']) ? mysqli_real_escape_string($conn,$_POST ['usuario'] ): false ;
    $equipo=isset($_POST ['equipo']) ? mysqli_real_escape_string($conn,$_POST['equipo']):false ;
    $eq_fecha=isset($_POST ['eq_fecha']) ? mysqli_real_escape_string($conn,$_POST['eq_fecha']):false ;
    $estado=isset($_POST ['estado']) ? mysqli_real_escape_string($conn,$_POST['estado']):false ;
    $eq_des=isset($_POST ['eq_des']) ? mysqli_real_escape_string($conn,$_POST['eq_des']):false ;

    if ($conn) {

        $sql= "INSERT INTO equipos VALUES (null,'$usuario','$equipo','$eq_des','$estado','$eq_fecha',now())";

        $guardar=mysqli_query($conn,$sql);

        if (!$guardar) {
        
        
            //mandando mensaje de error de la base de datos
                                            
            $error= mysqli_error($conn);
            echo "<br><center><h3>ERROR</h3></center>";
            echo "<h4>$error</h4>";
                                    
                                                
            echo "<a href='equipo.php' class='btn btn-danger'>Salir</a>";
            die();
                                            
                                                
                }else {
                    header('refresh:2;url= equipo.php');
                    exit;
                }

        
    }else {
                            
                                            
        echo "<a href='equipo.php' class='btn btn-danger'>Salir</a>";
        die("La conexión ha fallado: " . mysqli_connect_error());
   }

    
}else {
    header('refresh:1;url= equipo.php');
    exit;
}










?>