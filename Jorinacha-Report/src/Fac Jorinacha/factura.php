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

		.flex-container>.div {
			background-color: #f1f1f1;
			width: 100px;
			margin: 10px;
			text-align: center;
			line-height: 75px;
			font-size: 30px;
		}
	</style>


	<?php



	if ($_POST) {
		$empresa = $_POST['empresa'];
		$factura = $_POST['factura'];
	}

	?>
	<br><br><br><br><br>
	<br><br><br><br><br>
	<br><br><br><br><br>

	<div class="container">

		<div class="flex-container">
			<div class="div">

							<h4>CLIENTE:
							</h4>
							<h4>TELEFONO:
							</h4>
							<h4>RIF:
							</h4>


				</div>

				<div class="div">

								<h4>CLIENTE:
								</h4>
								<h4>TELEFONO:
								</h4>
								<h4>RIF:
								</h4>

	
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