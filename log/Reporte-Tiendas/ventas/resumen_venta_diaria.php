<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas Diaria</title>
</head>
<body>
<?php 

include '../includes/cabecera.php'  ;
include 'includes/icono.php';

?>


<center><h1>VENTA</h1>
<hr>

<?php 
if (isset($_POST)) {

    $fecha = $_POST['fecha'];
    $sedes_nom=$_POST['sedes_nom'];
    
    var_dump ($_POST);

        if ($sedes_nom=='todos') {

            $recorrido=175;

        } elseif ($sedes_nom != 'todos') {

            $recorrido=$sedes_nom;

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

           for ($i=0; $i <= $recorrido ; $i++) { 
               
           }
            
        }
        


        $fecha_actual = date("d-m-Y");

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

        
        
        $serv= array(15=>'SRVPREV',
        25=>'APURA',
        35=>'CATICA2',
        45=>'SRVPREV\CORINA',
        55=>'CORINA2',
        65=>'SRVPREV\HIGUE',
        75=>'KAGU',
        85=>'MATUR',
        95=>'MERINA',
        105=>'merina3',
        115=>'nacharia',
        125=>'ojena',
        135=>'puecruza',
        145=>'PUFIJO',
        155=>'TRINA',
        165=>'TURME',
        175=>'VALENA',
        185=>'VALLEPA',);

        $vuelta =5;

    for ($i=15; $i <= $recorrido ; $i+=10) { 

        echo "entrando al FOR<br>";

            $vuelta+=10;
        
            $base_dato=$bd[$i];
            $servidor=$serv[$i];

            if ($i == $vuelta ) {

                echo "$base_dato entrando al IF<br>";

                $serverName = "$servidor"; 
                $connectionInfo = array( "Database"=>"$base_dato", "UID"=>"Mezcla", "PWD"=>"Zeus33$");
                $conn = sqlsrv_connect( $serverName, $connectionInfo);

                if ($conn) {

                $sql_factura="SELECT * from factura where fec_emis = '$fecha'";
                $sql_cobro="SELECT * from cobros where fec_cob = '$fecha'";

                $consulta_factura=sqlsrv_query($conn,$sql_factura);
                
                

                    while ($fac=sqlsrv_fetch_array($consulta_factura)) { 
                        
                        
                        $recorrido=round($fac[tot_neto]);

                        $suma_tot+=$recorrido;
                        $tot_neto=number_format($suma_tot, 2, ',', '.');
                        

                    }

                $consulta_cobro= sqlsrv_query($conn,$sql_cobro);

                    while ($cob=sqlsrv_fetch_array($consulta_cobro)) { 

                        
                        $recorrido=round($cob[monto]);

                        $suma_monto+=$recorrido;
                        $monto=number_format($suma_monto, 2, ',', '.');
                        



                    }
                    sqlsrv_close($conn);
                        ?>

                    <h3> esta tienda es <?=$servidor?> y <?=$base_dato?> </h3>
                    <?php
                    if ($tot_neto == $monto) {
                        echo "<h4>caja cuadrada</h4>";
                        echo "<p>$tot_neto y $monto</p>";
                    }else {
                        echo "<h4>no cuadro caja</h4>";
                    }
                    
                }else {
                    echo "<h4>No conecto al servidor $servidor / $base_dato</h4>";
                }
                     ?>
                    
                    
<?php    } echo "el FOR sigue funcionando REPITIENDO <hr>"; } } 
                                                                else {
                                                                header('refresh:2;url= venta_tiendas.php');
                                                                exit;
    
                                                                    }?>

</center>
    
</body>
</html>