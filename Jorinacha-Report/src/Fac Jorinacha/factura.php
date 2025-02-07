<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="bootstrap.css">
	<title>Factura</title>

	<style>
		@import url(http://fonts.googleapis.com/css?family=Bree+Serif);

		body,
		h1,
		h2,
		h3,
		h4,
		h5,
		h6 {
			font-family: 'Bree Serif', serif;
		}

		@media (max-width: 900px) {
			#panel input {
				width: 105px;
				height: 30px;
			}

			#factura input {
				width: 150px;
				height: 30px;
			}


		}

		.flex-container {
			display: flex;
			justify-content: space-between;

		}
	</style>


	<?php



	if ($_POST) {
		$empresa = $_POST['empresa'];
		$factura = $_POST['factura'];

		$res0 = getFact($factura);
		$fact_num = $row['fact_num'];
		$fec_emis = $row['fec_emis'];
		$tot_bruto = $row['tot_bruto'];
		$iva =  $row['iva'];
		$tot_neto = $row['tot_neto'];
		$cli_des = $row['cli_des'];
		$direc1 =  $row['direc1'];
		$telefonos =  $row['telefonos'];
		$rif =  $row['rif'];

		$fecha = $fec_emis->format('d-m-Y');

	}

	?>
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>

	<div class="container">

		<div class="flex-container">
			<div class="div">
				<h4>CLIENTE: <?= $cli_des ?>
				</h4>
				<h4>TELEFONO: <?= $telefonos ?>
				</h4>
				<h4>RIF: <?= $rif ?>
				</h4>
			</div>
			<div class="div"></div>
			<div class="div">
				<h4>CLIENTE:
				</h4>
				<h4>TELEFONO:
				</h4>
				<h4>RIF:
				</h4>
			</div>
		</div>
		<div class="row">





			<!-- 
			<div class="col-xs-6 text-right">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4>CLIENTE:
						</h4>
						<h4>TELEFONO:
						</h4>
						<h4>RIF:
						</h4>

					</div>
					<div class="panel-body" id="factura">
						<h4>Orden Nº :
							NUMERO
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
							<h4> 

							</h4>
						</div>
						<div class="panel-body" id="panel">


							<h4>Destino: DIR /
								Equipo: Descrip
							</h4>

						</div>
					</div>
				</div>

			</div> -->
			<pre></pre>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th style="text-align: center;">
							<h4>DESCRIPCION</h4>
						</th>
						<th style="text-align: center;">
							<h4>CANT</h4>
						</th>
						<th style="text-align: center;">
							<h4>PRECIO</h4>
						</th>
						<th style="text-align: center;">
							<h4>IMPORTE</h4>
						</th>

					</tr>
				</thead>
				<tbody>

					<tr>
						<td style="text-align: center;">EQUIOP</td>

						<td style="text-align: center;">ESTA</td>

						<td class=" text-align: center; ">A></td>
						<td class=" text-align: center;">B></td>


					</tr>


					<tr>
						<td style="text-align: center;">EQUIOP</td>

						<td style="text-align: center;">ESTA</td>

						<td class=" text-align: center; ">A></td>
						<td class=" text-align: center;">B></td>


					</tr>

					<tr>
						<td>&nbsp;</td>
						<td><a href="#"> &nbsp; </a></td>
						<td class="text-right">&nbsp;</td>
						<td class="text-right ">&nbsp;</td>

					</tr>

					<tr>

						<td colspan="3" style="text-align: right;">Recibido Firma :</td>
						<td style="text-align: right;">
							<hr>
						</td>


					</tr>


				</tbody>
			</table>





		</div>



		<p class="text-right ">der</p>
		<p class="text-right "><b>Dir: </b>der</p>
		<p class="text-right "><b>Tlf: </b>der/p>



	</div>


</head>

<body>
	<center><input type="button" name="imprimir" value="Imprimir" onclick="window.print();"> </center>
	<br>

</body>

</html>