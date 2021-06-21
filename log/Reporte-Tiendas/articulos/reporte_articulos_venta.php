<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Consolidado</title>
</head>

<body>
<?php 
include '../includes/cabecera.php'  ;
include '../includes/icono.php';

?>
  

        
<?php 


if (isset($_POST)) {

    $fecha=$_POST['fecha'];

    require 'includes/tiendas.php';


   
    
/*     echo "<br>";
    echo $tienda_total;
    */
 

   
    
    for ($i=5; $i <= $tienda_total; $i+=10) { 


    /*    echo $bd[$i].$tiendas_seleccionadas[$i].'<br>'; */
        $base_dato=$bd[$i];
        $servidor=$serv[$i];
        $proceso=$tiendas_seleccionadas[$i];


            
/* variable para la validacion si hay conexion al servidor */
    $conexion=0;
        
        if ($i == $proceso) {
           /*  echo 'este es el if '.$bd[$i].'<br>'; */
           
           $serverName = "$servidor"; 
           $connectionInfo = array( "Database"=>"$base_dato", "UID"=>"Mezcla", "PWD"=>"Zeus33$");
           $conn = sqlsrv_connect( $serverName, $connectionInfo);

           

           
        

        echo "
        
        <table  class='table table-hover' id='tblData' >
                <thead>
                    <tr class='table-primary'>
                        <th scope='col' abbr='Starter'>Codigo Articulo</th>
                        <th scope='col' abbr='Starter'>Articulos</th>
                        <th scope='col' abbr='Starter'>Precio en Bolivares</th>
                        <th scope='col' abbr='Starter'>Referencia</th>
                        <th scope='col' abbr='Starter'>Ultimo Costo</th>
                        
                        <th scope='col' abbr='Starter'>Total Vendido</th>
                        
                    
                        
                    <tr>
                </thead>

                
         ";

                
                $total=0;


                $sql_factura="SELECT * from reng_fac where fec_lote='$fecha'";

                $consulta= sqlsrv_query($conn,$sql_factura);

                $articulos=array();
                $precio=array();

                while ($row=sqlsrv_fetch_array($consulta)) {

                    $articulos+=$row[co_art];
                    $precio+=$row[prec_vta];
                    
                }
                
                
                  


               

                

        while ($row=sqlsrv_fetch_array($consulta)) {

        $descripcion = $row[art_des];
        $dolares = round($row[prec_vta5]) ;

        $ult_cost = round($row[ult_cos_un]) ;
        $ultimo=number_format($ult_cost, 2, ',', '.');

        $precio = round($row[prec_vta1]) ;
        $bolivares=number_format($precio, 2, ',', '.');



        $codigo_articulo=round($row[co_art]);

        $stock = round($row[stock_act]);


        //sacando el total
        $total+=$stock;
        $sum_ult+=$ult_cost;
        $total_ult=number_format($sum_ult, 2, ',', '.');
        $sum_bol+=$precio;
        $total_bol=number_format($sum_bol, 2, ',', '.');

        /* variable para la validacion si hay conexion al servidor */
        $conexion+=1;


        echo "
        <tr>
        <td>".$codigo_articulo."</td>
        <td>".$descripcion."</td>
        <td>Bs. ".$bolivares."</td>
        <td>$ ".$dolares."</td>
        <td>Bs. ".$ultimo ."</td>
        
        
        <td>".$stock."</td>
        



        </tr>";




        }
        
       

        if ($conexion == 0) {
    
            echo "<center><h1>ERROR</h1>";
            echo "<h3>No es Posible hacer conexion con la base de dato de $servidor</h3>";
            echo "</center>";
            sqlsrv_close($conn);
        }else {

            echo "<tr><td ></td><td class='text-right ' >Total</td><td >Bs. $total_bol</td><td></td><td >Bs. $total_ult</td><td>$total </td> </tr>";
            echo "<center><h2>".$base_dato."</h2></center>";

            sqlsrv_close($conn);
            include '../includes/excel.php';
        }
        
        }
       
        
       
      
        
        
      

    }
    

   


 
    



}



sqlsrv_close($conn);



?>



        
    
</body>

</html>




