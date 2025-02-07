<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../services/mysql.php';
include '../../services/adm/fac_jorinacha/fac.php';


?>
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


	if ($_GET) {
		$empresa = $_GET['empresa'];
		$factura = $_GET['factura'];

		$res_fact = getFact((int)$factura);

		$fact_num = $res_fact['fact_num'];
		#$fec_emis = $res_fact['fec_emis'];
		$tot_bruto = $res_fact['tot_bruto'];
		$iva =  $res_fact['iva'];
		$tot_neto = $res_fact['tot_neto'];
		$cli_des = $res_fact['cli_des'];
		$co_cli = $res_fact['co_cli'];
		$direc1 =  $res_fact['direc1'];
		$telefonos =  $res_fact['telefonos'];
		$rif =  $res_fact['rif'];


		#$fecha = $fec_emis->format('d-m-Y');

	}



	?>
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>

	<div class="container">

		<div class="flex-container">
			<div class="div">
				<h5>CLIENTE: <?= $cli_des; ?>
				</h5>
				<h5>COD CLIENTE: <?= $co_cli; ?>
				</h5>
				<h5>TELEFONO: <?= $telefonos; ?>
				</h5>
				<h5>RIF: <?= $rif; ?>
				</h5>
				<h5>DIR: <?= $direc1; ?>
				</h5>
			</div>
			<div class="div"></div>
			<div class="div">
				<h5>FACTURA NUM: <?= $fact_num; ?>
				</h5>
				<h5>FECHA:
				</h5>
				<h5>CONDICION: CREDITO 30 DIAS
				</h5>
			</div>
		</div>
		<div class="row">




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


					<?php

					$res_reng_fact = get_Reng_Fact((int)$factura);
					for ($e = 0; $e < count($res_reng_fact); $e++) {

						$art_des = $res_reng_fact[$e]['art_des'];
						$total_art = $res_reng_fact[$e]['total_art'];
						$prec_vta = $res_reng_fact[$e]['prec_vta'];
						$reng_neto = $res_reng_fact[$e]['reng_neto'];

					



					?>
					

					<tr>
						<td style="text-align: center;"><?= $art_des; ?></td>

						<td style="text-align: center;"><?= $total_art; ?></td>

						<td class=" text-align: center; "><?= $prec_vta; ?></td>
						<td class=" text-align: center;"><?= $reng_neto; ?></td>


					</tr>
					<?php
					}
					?>
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