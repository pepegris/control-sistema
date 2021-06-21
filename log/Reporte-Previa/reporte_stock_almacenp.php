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
include 'includes/icono.php';

?>
  

        
<?php 


if (isset($_POST)) {
    $linea=$_POST['linea'];

    $tienda1=$_POST['Comercial_Acari'];
    $tienda2=$_POST['Comercial_Apura'];
    $tienda3=$_POST['Comercial_Catica_II'];
    $tienda4=$_POST['Comercial_Corina_I'];
    $tienda5=$_POST['Comercial_Corina_II'];
    $tienda6=$_POST['Comercial_Higue'];
    $tienda7=$_POST['Comercial_Kagu'];
    $tienda8=$_POST['Comercial_Matur'];
    $tienda9=$_POST['Comercial_Merina'];
    $tienda10=$_POST['Comercial_Merina_III'];
    $tienda11=$_POST['Comercial_Nachari'];
    $tienda12=$_POST['Comercial_Ojena'];
    $tienda13=$_POST['Comercial_Puecruz'];
    $tienda14=$_POST['Comercial_Punto_Fijo'];
    $tienda15=$_POST['Comercial_Trina'];
    $tienda16=$_POST['Comercial_Turme'];
    $tienda17=$_POST['Comercial_Valena'];
    $tienda18=$_POST['Comercial_Vallepa'];

    



  

    //var_dump($_POST);

    $tienda_total=0;
    $tienda_total=$tienda1+$tienda2+$tienda3+$tienda4+$tienda5+$tienda6+$tienda7+$tienda8+$tienda9+$tienda10+$tienda11+$tienda12+$tienda13+$tienda14+$tienda15+$tienda16+$tienda17+$tienda18;
    
    $tiendas_seleccionadas=array(
        15=>$tienda1,
        25=>$tienda2,
        35=>$tienda3,
        45=>$tienda4,
        55=>$tienda5,
        65=>$tienda6,
        75=>$tienda7,
        85=>$tienda8,
        95=>$tienda9,
        105=>$tienda10,
        115=>$tienda11,
        125=>$tienda12,
        135=>$tienda13,
        145=>$tienda14,
        155=>$tienda15,
        165=>$tienda16,
        175=>$tienda17,
        185=>$tienda18,
    );


    
        $bd= array(15=>'ACARI_A',
        25=>'APURA_A',
        35=>'CATICA2A',
        45=>'CORINA_A',
        55=>'CORINA2A',
        65=>'HIGUE_A',
        75=>'KAGU_A',
        85=>'MATUR_A',
        95=>'MERINA_A',
        105=>'merina3a',
        115=>'nacharia',
        125=>'ojena_a',
        135=>'puecruza',
        145=>'PUFIJO_A',
        155=>'TRINA_A',
        165=>'TURME_A',
        175=>'VALENA_A',
        185=>'VALLEPAA',);

   
    
/*     echo "<br>";
    echo $tienda_total;
    */
 

   
    
    for ($i=5; $i <= $tienda_total; $i+=10) { 


    /*    echo $bd[$i].$tiendas_seleccionadas[$i].'<br>'; */
        $base_dato=$bd[$i];
       
        $proceso=$tiendas_seleccionadas[$i];


            
/* variable para la validacion si hay conexion al servidor */
    $conexion=0;
        
        if ($i == $proceso) {
           /*  echo 'este es el if '.$bd[$i].'<br>'; */
           
           $serverName = "SQL"; 
           $connectionInfo = array( "Database"=>"$base_dato", "UID"=>"Mezcla", "PWD"=>"Zeus33$");
           $conn = sqlsrv_connect( $serverName, $connectionInfo);

$solicitud="SELECT * from st_almac inner join art on st_almac.co_art=art.co_art where  st_almac.stock_act > 0 and st_almac.co_alma=1";
$consulta= sqlsrv_query($conn,$solicitud);

        

        echo "
        
        <table  class='table table-hover' id='tblData' >
                <thead>
                    <tr class='table-primary'>
                        <th scope='col' abbr='Starter'>Codigo Articulo</th>
                        <th scope='col' abbr='Starter'>Articulos</th>
                        <th scope='col' abbr='Starter'>Stock</th>
                        
                    
                        
                    <tr>
                </thead>

                
         ";

                
                $total=0;
                

        while ($row=sqlsrv_fetch_array($consulta)) {

        $descripcion = $row[art_des];

        $codigo_articulo=round($row[co_art]);

        $stock = round($row[stock_act]);

        $total+=$stock;

        $conexion+=1;


        echo "
        <tr>
        <td>".$codigo_articulo."</td>
        <td>".$descripcion."</td>
        <td>".$stock."</td>
        



        </tr>";




        }
        
       

        if ($conexion == 0) {
    
            echo "<center><h1>ERROR</h1>";
            echo "<h3>No es Posible hacer conexion con la base de dato de $servidor</h3>";
            echo "</center>";
            sqlsrv_close($conn);
        }else {

            echo "<tr><td></td><td class='text-right '>Total</td><td>$total </td> </tr>";
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




