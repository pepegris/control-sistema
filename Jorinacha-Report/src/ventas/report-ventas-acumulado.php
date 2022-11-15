<?php

ini_set('memory_limit','4096M');
ini_set('max_execution_time',3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

  $linea = $_GET['linea'];

  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));  

  for ($i = 0; $i < 20; $i += 1) {
    $sedes[] = $_GET[$i];
  }

echo "
<head>
	<meta charset='UTF-8'>
	<title>Inventario</title>
	<link rel='shortcut icon' href='favicon.ico' />	
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<link rel='stylesheet' href='css/responm.css'>
</head>

<body>
	<div id='page-wrap' align='center'>";
$f="<font face='verdana' size='1'>";
echo "<font face='verdana' size=4>".$e."</font>
      <img src='img/excel.png' onclick=document.forms['Fexcel'].submit();>";

	  
	  
echo "<table  border='1' width='95%' id='tblNeedsScrolling'>
			<thead>
				<tr>
					<th width='70px' nowrap>".$f."<b>Almacen</th>
					<th width='100px' nowrap>".$f."<b>Marca</th>
					<th width='110px' nowrap>".$f."<b>Codigo</th>
					<th width='300px' nowrap>".$f."<b>Descripcion</th>
					<th width='50px' nowrap>".$f."<b>Teorico</th>
					<th width='50px' nowrap>".$f."<b>Real</th>
					<th width='50px' nowrap>".$f."<b>Dif</th>
					<th width='50px' nowrap>".$f."<b>Planilla</th>
					<th width='50px' nowrap>".$f."<b>Renglon</th>
					<th width='100px' nowrap>".$f."<b>Stock en Renglon</th>
				</tr>
			<thead>
			<tbody>";

$consulta="EXEC dbo.Inv_Fis @empresa=N'".$e."', @diferentes=".$d.", @almacen=N'".$a."'";
$run=sqlsrv_query($conn,$consulta);
//echo $consulta;
while($row=sqlsrv_fetch_array($run)) { 

$sub_consulta="	SELECT RF.num_fis AS Planilla, RF.reng_num AS Renglon, CAST(RF.stock_real AS INT) AS SReal
				FROM ".$e.".dbo.reng_fis AS RF
				INNER JOIN ".$e.".dbo.fisico AS F
				ON F.num_fis=RF.num_fis
				WHERE RF.co_art='".$row['Codigo']."' AND F.cerrado=0";
$sub_run=sqlsrv_query($conn,$sub_consulta, array(), array( "Scrollable" => 'static' ));
$row_count = sqlsrv_num_rows($sub_run);
$b="";
echo "<tr>";
echo "<td align='center' rowspan='".$row_count."'  width='70px' nowrap>".$f.$row['Almacen']."</td>";
echo "<td align='center' rowspan='".$row_count."'  width='100px' nowrap>".$f.$row['Marca']."</td>";
echo "<td align='center' rowspan='".$row_count."'  width='110px' nowrap>".$f.$row['Codigo']."</td>";
	if ($row['Marca']!=NULL and $row['Descripcion']==NULL) 
		{ echo "<td align='right' rowspan='".$row_count."' width='300px' nowrap>".$f."<b>Sub-Total:</td>";  $b="<b>"; }
	elseif ($row['Marca']==NULL and $row['Descripcion']==NULL)  
		{ echo "<td align='right' rowspan='".$row_count."' width='300px' nowrap>".$f."<b>Total:</td>"; $b="<b>"; }
	else 
	{ echo "<td rowspan='".$row_count."' width='300px' nowrap>".$f.utf8_encode($row['Descripcion'])."</td>"; }
echo "<td align='center' rowspan='".$row_count."' width='50px' nowrap>".$f.$b.$row['Teorico']."</td>";
echo "<td align='center' rowspan='".$row_count."' width='50px' nowrap>".$f.$b.$row['Real']."</td>";
echo "<td align='center' rowspan='".$row_count."' width='50px' nowrap><font face='verdana' size='1' color='red'>".$b.($row['Real']-$row['Teorico'])."</fon></td>";
$n=0;	
	while($s_row=sqlsrv_fetch_array($sub_run)) {
	$n=$n+1;
	if ($n!=1) { echo "<tr>"; }
	echo "<td align='center' width='50px' nowrap>".$f.$s_row['Planilla']."</td>";
	echo "<td align='center' width='50px' nowrap>".$f.$s_row['Renglon']."</td>";
	echo "<td align='center' width='100px' nowrap>".$f.$s_row['SReal']."</td>";
	if ($n!=1) { echo "</tr>"; }
	}
echo "</tr>";
}

echo "</tbody></table>";
sqlsrv_close( $conn );
?>