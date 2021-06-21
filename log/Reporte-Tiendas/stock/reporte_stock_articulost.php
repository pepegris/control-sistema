<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Stock Consolidado</title>
</head>

<body>
<?php 
include '../includes/cabecera.php'  ;
include '../includes/icono.php';

?>
  

        
<?php 


if (isset($_POST)) {
    $linea=$_POST['linea'];

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

           if ($linea=='Todos') {

            $solicitud="SELECT * from st_almac inner join art on st_almac.co_art=art.co_art where  st_almac.stock_act > 0 and st_almac.co_alma=1";

           }else {

            $solicitud="SELECT * from st_almac inner join art on st_almac.co_art=art.co_art where art.co_lin='$linea' and st_almac.stock_act > 0 and st_almac.co_alma=1";

           }
$consulta= sqlsrv_query($conn,$solicitud);

        

        echo "
        
        <table  class='table table-hover' id='<?=$servidor?>' >
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

        echo "<script type='text/javascript'>

        let filename='';
        function exportTableToExcel(tableID, filename='' ){
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
            
            // Specify file name
            filename = filename?filename+'.xls':'excel_data.xls';
            
            // Create download link element
            downloadLink = document.createElement('a');
            
            document.body.appendChild(downloadLink);
            
            if(navigator.msSaveOrOpenBlob){
                var blob = new Blob(['ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob( blob, filename);
            }else{
                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            
                // Setting the file name
                downloadLink.download = filename;
                
                //triggering the function
                downloadLink.click();
            }
        }
        
            
        </script>";

      


        
       

        if ($conexion == 0) {
    
            echo "<center><h1>ERROR</h1>";
            echo "<h3>No es Posible hacer conexion con la base de dato de $servidor</h3>";
            echo "</center>";
            sqlsrv_close($conn);
        }else {

          







            echo "<tr><td></td><td class='text-right '>Total</td><td>$total </td> </tr>";
            echo "<center><h2>".$base_dato."</h2></center>";

            sqlsrv_close($conn);

            echo "</table>   
            <center><button class='btn btn-success btn-lg' onclick='exportTableToExcel(".$servidor.")'>Exportar a Excel</button></center>
            <br>
            <br>";
        }
        
        }
       
        
       
      
        
        
      

    }
    

   


 
    



}



sqlsrv_close($conn);



?>



        
    
</body>

</html>




