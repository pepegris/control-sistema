<?php

$serverName = "SQL"; 
$connectionInfo = array( "Database"=>"VALENA_A", "UID"=>"Mezcla", "PWD"=>"Zeus33$");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

var_dump($conn);
echo "<br>";

$sql_art = "SELECT *  from reng_fac inner join factura  on factura.fact_num=reng_fac.fact_num where reng_fac.fec_lote ='20210621' and factura.fec_emis ='20210621'";

$consulta_art= sqlsrv_query($conn,$sql_art);

$total_art=array();
$total_co=array();

var_dump($sql_art);
echo "<br>";
var_dump($consulta_art);
echo "<br>";

while ($art=sqlsrv_fetch_array($consulta_art)) {

    $cantidad_art=round($art[total_art]);
    $total_art[]=$cantidad_art;

    $co_art=$art[co_art];
    $total_co[]=$co_art;

   

    
}
echo "<br>";
  for ($i=0; $i < count($total_art) ; $i++) { 
     
    
    echo "mostranto $total_art[$i] - $total_co[$i]";
    echo "<br>";

 } 








?>