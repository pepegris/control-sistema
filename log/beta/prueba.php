<?php


if (isset($_POST)) {
    
    $fecha=$_POST['fecha'];

    var_dump($_POST);

 $serverName = "SQL"; 
$connectionInfo = array( "Database"=>"ACARI_A", "UID"=>"Mezcla", "PWD"=>"Zeus33$");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

echo "$conn";


$sql_factura="SELECT * from reng_fac where fec_lote='$fecha'";

echo "$sql_factura";

$consulta= sqlsrv_query($conn,$sql_factura);









}
  



?>