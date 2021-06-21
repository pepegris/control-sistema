<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <link rel="stylesheet" href="../css/bootstrap.css">
	<title>Orden de Entrega</title>

	<style>@import url(http://fonts.googleapis.com/css?family=Bree+Serif);
  			body, h1, h2, h3, h4, h5, h6{
    			font-family: 'Bree Serif', serif;
 	 									}
	@media (max-width: 900px){
    #panel input{
        width: 105px;
        height: 30px;
    }
	#factura input{
        width: 150px;
        height: 30px;
    }
	
    
}				  
			
  	</style>


<?php 

 require '../includes/conexion_control.php';
 require_once '../includes/datos_empresa.php';

$usuario=''; 
 $eq_fecha=''; 
 $eq_des=''; 
 $estado='';
 $equipo='';
 
 

if  (isset($_GET['id'])) {
  $id = $_GET['id'];
  $query1 = "SELECT * FROM equipos WHERE id=$id";
  $result = mysqli_query($conn, $query1);
  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_array($result);
    $usuario=$row['usuario']; 
    $eq_fecha=$row['eq_fecha']; 
    $eq_des=$row['eq_des']; 
    $estado=$row['estado'];
    $equipo=$row['equipo'];

    
    
  }
 
}

?>
<br>
		<div class="container">
			<div class="row">
            
		<div class="col-xs-6">
			<h1><a href=" "> <!-- COLOCAR PAGINA WEB DE LA EMPRESA  --><img id="imagen" style="height: 220px;" alt=""  src="../css/img/sistema-logo.jpg" /></a></h1>
		</div>
		<div class="col-xs-6 text-right">
							<div class="panel panel-default">
							<div class="panel-heading">
                                    <h4><?php echo "$empresa"; ?>
									</h4>
									<h4>RIF: 
										<?php echo "$rif"; ?>
									</h4>
									
							</div>
							<div class="panel-body" id="factura">
								<h4>Orden Nº : 
									<?=$id?>
								</h4>
							</div>
						</div>
					</div>
 
			<hr />
 
			
				<h1 style="text-align: center;">Nota de Entrega</h1> 
			
				<div class="row">
					<div class="col-xs-12">
						<div class="panel panel-default">
								<div class="panel-heading">
									<h4> <?php echo "Caracas " . date("d") . " del " . date("m") . " de " . date("Y"); ?> 
									
									</h4>
								</div>
						<div class="panel-body" id="panel" >
						
							
								<h4>Destino:  <?=$usuario?> / 
                                Equipo: <?=$equipo?>
								</h4>
					
						</div>
						</div>
					</div>
					
				</div>
<pre></pre>
<table class="table table-bordered">
	<thead >
		<tr >
			<th style="text-align: center;">
				<h4>Equipo</h4>
			</th>
			<th style="text-align: center;">
				<h4>Estado</h4>
			</th>
			<th style="text-align: center;">
				<h4>Descipcion</h4>
			</th>
			<th style="text-align: center;">
				<h4>Fecha</h4>
			</th>
			
		</tr>
	</thead>
	<tbody>
	
		<tr>
			<td style="text-align: center;"><?=$equipo?></td>

			<td style="text-align: center;"><?=$estado?></td>
			
			<td class=" text-align: center; "><?=$eq_des?></td>
			<td class=" text-align: center;"><?=$eq_fecha?></td>
			
			
		</tr>

        <tr>
			<td>&nbsp;</td>
			<td><a href="#"> &nbsp; </a></td>
			<td class="text-right">&nbsp;</td>
			<td class="text-right ">&nbsp;</td>
			
		</tr> 

		<tr >
        
			<td colspan="3" style="text-align: right;">Recibido Firma :</td>
			<td style="text-align: right;"><hr></td>
			
			
		</tr> 
		
		
	</tbody>
</table>

		


		
</div>



    <p class="text-right "><?=$departamento?></p>
    <p class="text-right "><b>Dir: </b><?=$direccion?></p>
    <p class="text-right "><b>Tlf: </b><?=$numero?></p>


    <div class="row">

	    <div class="col-xs-4">
			<h1><a href=" "><img alt="" src="../css/img/qr.png" /></a></h1>
		</div>
		
	</div>
</div>


</head>
<body>
	<center><input type="button" name="imprimir" value="Imprimir" onclick="window.print();"> </center>
	<br>
	
</body>
</html>