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


?>
  

        
<?php 


if (isset($_POST)) {
    $linea=$_POST['linea'];

    $tienda1=$_POST['acari_a'];
    $tienda2=$_POST['higue_a'];
    $tienda3=$_POST['valena_a'];
    $tienda4=$_POST['apura_a'];
    $tienda5=$_POST['catica2a'];
    $tienda6=$_POST['corina2a'];
    $tienda7=$_POST['kagu_a'];
    $tienda8=$_POST['matur_a'];
    $tienda9=$_POST['merina_a'];
    $tienda10=$_POST['merina3a'];
    $tienda11=$_POST['nacharia'];
    $tienda12=$_POST['ojena_a'];
    $tienda13=$_POST['puecruza'];
    $tienda14=$_POST['pufijo_a'];
    $tienda15=$_POST['trina_a'];
    $tienda16=$_POST['turme_a'];
    $tienda17=$_POST['vallepaa'];


  

   /*  var_dump($_POST); */
    $tienda_total=0;
    $tienda_total=$tienda1+$tienda2+$tienda3+$tienda4+$tienda5+$tienda6+$tienda7+$tienda8+$tienda9+$tienda10+$tienda11+$tienda12+$tienda13+$tienda14+$tienda15+$tienda16+$tienda17;
    
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
    );


    $bd= array(15=>'acari_a',
                25=>'higue_a',
                35=>'valena_a',
                45=>'apura_a',
                55=>'catica2a',
                65=>'corina2a',
                75=>'kagu_a',
                85=>'matur_a',
                95=>'merina_a',
                105=>'merina3a',
                115=>'nacharia',
                125=>'ojena_a',
                135=>'puecruza',
                145=>'pufijo_a',
                155=>'trina_a',
                165=>'turme_a',
                175=>'vallepaa',);
    
/*     echo "<br>";
    echo $tienda_total;
    */

    
    for ($i=5; $i <= $tienda_total; $i+=10) { 


    /*    echo $bd[$i].$tiendas_seleccionadas[$i].'<br>'; */
        $base_dato=$bd[$i];
        $proceso=$tiendas_seleccionadas[$i];
        
        if ($i == $proceso) {
           /*  echo 'este es el if '.$bd[$i].'<br>'; */
           
            $serverName = "SQL"; 
            $connectionInfo = array( "Database"=>"$base_dato", "UID"=>"syncro", "PWD"=>"syncro");
            $conn = sqlsrv_connect( $serverName, $connectionInfo);

$solicitud="SELECT * from st_almac inner join art on st_almac.co_art=art.co_art where art.co_lin='$linea' and st_almac.stock_act > 0 and st_almac.co_alma=1";
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


        echo "
        <tr>
        <td>".$codigo_articulo."</td>
        <td>".$descripcion."</td>
        <td>".$stock."</td>
        



        </tr>";




        }
        
        echo "<tr><td></td><td class='text-right '>Total</td><td>$total </td> </tr>";

        
        echo "<center><h2>".$base_dato."</h2></center>";
        sqlsrv_close($conn);

        include '../includes/excel.php';
        
        }
       
      
        
        
      

    }
    

   


 
    



}







?>



<script type="text/javascript">


function exportTableToExcel(tableID, filename='' ){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
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

    
</script>


        </table>   
        <center><button class='btn btn-success btn-lg' onclick="exportTableToExcel('tblData')">Exportar a Excel</button></center>
        <br>
        <br>
        
    
</body>

</html>




