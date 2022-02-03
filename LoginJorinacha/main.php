<?php
/* session_start();
$userposs= $_SESSION['user'];
$passposs= $_SESSION['pass'];
$userposs;
$passposs;


if ($userposs == null || $userposs == "") {

    echo ("Usted no tiene autorización. Póngase en contacto con el personal de sistema.");
    header('Refresh:10; url=autentica.php', true, 303);
    die();
} */
?>

<?php

 



 session_start();

$cuenta_on=$_SESSION['username'];



  if (!isset($cuenta_on)) {
    header("location:autentica.php");
}

$cuenta_on = ucwords($cuenta_on); 
 
 
 
?>


<a href="#" class="btn btn-dark" style="position: fixed;
    bottom: 90%;
    right: 5%;
    font-size:30px;" >Usuario: <?=$cuenta_on?></a>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel='shortcut icon' href='favicon.ico' />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Mi estilos CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
    <title>Menu</title>
  </head>
  <style>

    p a{
      color: black;
    }

  </style>
  <body>
	<header id="header" class="alt">
						<a href="inicio.php" class="logo"><strong>Inversiones</strong> <span>Jorinacha</span></a>
						<!-- <nav>
							<a href="#menu">Menu</a>
						</nav> -->
					</header>

  <div class="mx-auto" style="width: 17em;">
    <h1 class="display-4 mt-2 ">Solicitudes</h1>
  </div>
  
  <div class="container mt-5 pb-5">
    <div class="row row-cols-1 row-cols-md-3 ">
      <!--Contenedor de cajas-->
        <!--caja 1-->
        <div class="col mb-4">
          <div class="card">       
            <div class="card-body">
              <p class="card-title text-center "><a href='../orden.php'>Ordenes No Generadas de Previa a las Tiendas</a></p>  
            </div>
          </div>
        </div>
        <!--caja 2-->
        <div class="col mb-4">
          <div class="card" >        
            <div class="card-body " >
              <p class="card-title text-center  pb-4 "><a href='../merge.php'> Conexiones de Tiendas</a></p>
            </div>
          </div>
        </div>
        <!--caja 3-->
        <div class="col mb-4">
          <div class="card">
            <div class="card-body">
              <p class="card-title text-center flex-nowrap"><a href='../comparativo2.php'> Comparativo de Tiendas respecto al Servidor Principal</a></p>  
            </div>
          </div>
        </div>
         <!--caja 1-->
         <div class="col mb-4">
          <div class="card">       
            <div class="card-body">
              <p class="card-title text-center"><a href='../conectados.php'> Ver Usuarios conectados a las Bases de Datos</a></p>  
            </div>
          </div>
        </div>
        <!--caja 2-->
        <div class="col mb-4">
          <div class="card">        
            <div class="card-body pb-">
              <p class="card-title text-center  pb-4"><a href='/contabilidad'> Contabilidad</a></p>
            </div>
          </div>
        </div>
        <!--caja 3-->
        <div class="col mb-4">
          <div class="card">
            <div class="card-body">
              <p class="card-title text-center flex-nowrap pb-4"><a href='../ventas.php'> Reportes de Ventas</a></p>  
            </div>
          </div>
        </div>
        <!--caja 1-->
        <div class="col mb-4">
          <div class="card">       
            <div class="card-body">
              <p class="card-title text-center pb-4"><a href='../devoluciones.php'> Facturas de Compra por Devolucion</a></p>  
            </div>
          </div>
        </div>
        <!--caja 2-->
        <div class="col mb-4">
          <div class="card">        
            <div class="card-body pb-">
              <p class="card-title text-center  pb-4"><a href='../compra.php'> Articulos sin compras por tienda</a></p>
            </div>
          </div>
        </div>
        <!--caja 3-->
        <div class="col mb-4">
          <div class="card">
            <div class="card-body">
              <p class="card-title text-center flex-nowrap "><a href='../../costo/index.php'> Comparativo Costo de Ficha y Precio de Compra por Tienda</a></p>  
            </div>
          </div>
        </div>
         <!--caja 1-->
         <div class="col mb-4">
          <div class="card">       
            <div class="card-body">
              <p class="card-title text-center"><a href='../validacion_margen.php'> Margen de los Articulos respecto a su ultima Compra</a></p>  
            </div>
          </div>
        </div>
        <!--caja 2-->
        <div class="col mb-4">
          <div class="card">        
            <div class="card-body pb-">
              <p class="card-title text-center pb-4 "><a href='../../remate/index.php'> Ultimas 4 Facturas por Articulos por Tienda</a></p>
            </div>
          </div>
        </div>
        <!--caja 3-->
        <div class="col mb-4">
          <div class="card">
            <div class="card-body">
              <p class="card-title text-center flex-nowrap "><a href='../../remate/indexT.php'> Ultimas 4 Facturas por Articulos de Todas las Tiendas</a></p>  
            </div>
          </div>
        </div>
        <!--caja 1-->
        <div class="col mb-4">
          <div class="card">       
            <div class="card-body">
              <p class="card-title text-center"><a href='../../remate/sinmovimiento.php'> Articulos sin Moviento en las Tiendas</a></p>  
            </div>
          </div>
        </div>
        <!--caja 2-->
        <div class="col mb-4">
          <div class="card">        
            <div class="card-body pb-">
              <p class="card-title text-center  "><a href='../../inventario/index.php'> Inventario Fisico</a></p>
            </div>
          </div>
        </div>
        <!--caja 3-->
        <!--Hecho por Lázaro Pérez Departamento de Sistema -->
        <div class="col mb-4">
          <div class="card">
            <div class="card-body">
              <p class="card-title text-center flex-nowrap "><a href='../ReportesVentas/index.php'>Reportes De Ventas Individual</a></p>  
            </div>
          </div>
        </div>
        <!--caja 1-->
        <div class="col mb-4">
          <div class="card">       
            <div class="card-body">
              <p class="card-title text-center"><a href='../../php/ReporteDiarioOrdenDePago/index.html'>Ordenes de Pago</a></p>  
            </div>
          </div>
        </div>
        <!--Hecho por Lázaro Pérez Departamento de Sistema -->
        <!--Contenedor de cajas-->
          <!--caja 1-->
          <div class="col mb-4">
          <div class="card">       
            <div class="card-body">
              <p class="card-title text-center"><a href='../../php/ReporteUSD'>Reporte Divisas</a></p>  
            </div>
          </div>
        </div>
         <!--caja 1-->
         <div class="col mb-4">
          <div class="card">       
            <div class="card-body">
              <p class="card-title text-center"><a href='../../php/ConsolidadoOrdenDePago'>Consolidado Orden de Pago</a></p>  
            </div>
          </div>
        </div>
        <!--caja 1-->
        <div class="col mb-4">
          <div class="card">       
            <div class="card-body">
              <p class="card-title text-center"><a href='../../php/BuscadorArt'>Buscador Articulo</a></p>  
            </div>
          </div>
        </div>
         <!--caja 1-->
         <div class="col mb-4">
          <div class="card">       
            <div class="card-body">
              <p class="card-title text-center"><a href='../../php/BuscadorArticuloTienda'>Buscador Art Tiendas</a></p>  
            </div>
          </div>
        </div>
        <!--caja1-->
      </div>
</div>

    
<!-- Footer -->
<center>
<footer id="footer">
						<div class="inner">
						
							<ul class="copyright">
								<li><p>Inversiones Jorinacha © 2021 By Andres Salcedo | Departamento de Sistema</p> </li>
							</ul>
						</div>
					</footer>
</center>
  <!-- Footer -->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>