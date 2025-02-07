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


		.flex-container {
			display: flex;
			justify-content: space-between;

		}

		table{
			align-items: center;
			justify-content: center;
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
	<br><br><br><br><br><br>
	<br><br><br><br><br><br>


	<div class="container">

		<div class="flex-container">
			<div class="div" >
				<h3>CLIENTE: <?= $cli_des; ?>
				</h3>
				<h3>COD CLIENTE: <?= $co_cli; ?>
				</h3>
				<h3>TELEFONO: <?= $telefonos; ?>
				</h3>
				<h3>RIF: <?= $rif; ?>
				</h3>
				<h3>DIR: <?= $direc1; ?>
				</h3>
			</div>
			<div class="div"></div>
			<div class="div" >
				<h3>FACTURA NUM: <?= $fact_num; ?>
				</h3>
				<h3>FECHA:
				</h3>
				<h3>CONDICION: CREDITO 30 DIAS
				</h3>
			</div>
		</div>
		<div class="row">




			<pre></pre>
			<table class="">
				<thead style=" text-align: center; line-height: 0.1;padding: -2px;">
					<tr>
						<th>
							<h3>DESCRIPCION</h3>
						</th>
						<th>
							<h3>CANT</h3>
						</th>
						<th>
							<h3>PRECIO</h3>
						</th>
						<th>
							<h3>IMPORTE</h3>
						</th>

					</tr>
				</thead>
				<tbody>


					<?php

					$res_reng_fact = get_Reng_Fact((int)$factura);
					$total_pares = 0;
					for ($e = 0; $e < count($res_reng_fact); $e++) {

						$art_des = $res_reng_fact[$e]['art_des'];
						$total_art = $res_reng_fact[$e]['total_art'];
						$prec_vta = number_format($res_reng_fact[$e]['prec_vta'],  2,',', '.');
						$reng_neto = number_format($res_reng_fact[$e]['reng_neto'],  2,',', '.');

						$total_pares += $total_art;



					?>


						<tr style="line-height: 1; padding: 1px;">
							<td style="font-size: 18px; line-height: 1; padding: 1px;"><?= $art_des; ?></td>
							<td style="text-align: center;  font-size: 18px; line-height: 1;padding: 1px;"><?= $total_art; ?></td>
							<td style=" text-align: right; font-size: 18px; line-height: 1;padding: 1px;"><?= $prec_vta ; ?></td>
							<td style=" text-align: right; font-size: 18px; line-height: 1;padding: 1px;"><?= $reng_neto ; ?></td>


						</tr>
					<?php
					}
					?>
					<tr><td></td></tr>
					<tr style="line-height: 1; padding: 1px; text-align:right; font-size: 18px;">

						<td colspan="4">
							Total Pares : <?= $total_pares; ?>  
							Total Bultos : *,***.**
						</td>
					</tr>
					<tr style="line-height: 1; padding: 1px; text-align:right;">
						<td colspan="4" style="line-height: 1; padding: 1px; text-align:right; font-size: 18px; ">

							<p> <b>Sub Total: </b><?= number_format($tot_bruto,  2,',', '.'); ?></p>
							<p><b>%Desc. % :</b>0,00</p>
							<p><b>I.V.A 16% : </b><?= number_format($iva,  2,',', '.'); ?></p>
							<p><b>Neto: </b><?=number_format($tot_neto,  2,',', '.'); ?></p>

						</td>


					</tr>


				</tbody>
			</table>





		</div>







	</div>


</head>

<body>
<!-- 	<center><input type="button" name="imprimir" value="Imprimir" onclick="window.print();"> </center>
	<br> -->

</body>

</html>