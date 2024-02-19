<?php

session_start();

$cuenta_on = $_SESSION['username'];

if (!isset($cuenta_on)) {
  header("location:../salir.php");
}

$cuenta_on = ucwords($cuenta_on);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <!-- ICONOS -->
  <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">

  <link rel="stylesheet" href="assets/css/bootstrap-5.2.0-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/home.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">


  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- NIEVE -->
<!--   <link rel="stylesheet" href="./assets/navidad/nieve.css"> -->
  <title>Jorinacha Report</title>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="../../LoginJorinacha/inicio.php">Jorinacha</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav ml-auto">

          <li class="nav-item">
            <a class="nav-link" href="../../LoginJorinacha/main.php">Admnistrativo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../control/log/contadores/buscador.php">Contadores</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../control/inicio.php">Sistema IT</a>
          </li>

        </ul>
        <!--          
          <form class="d-flex">
          <input class="form-control me-sm-2" type="text" placeholder="Search">
          <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
        </form>  -->
      </div>
    </div>
  </nav>


  <main>

    <h1>Reportes</h1>

    <div class="container__box">
      <a href="src/art-stock/form.php">
        <div class="box">
          <i class="lni lni-dropbox"></i>
          <h5>Reporte de Fallas</h5>
          <h4>Articulos vendidos a una fecha y stock Actual y Pedidos</h4>
        </div>
      </a>

      <a href="src/art-compras/form.php">
        <div class="box">
          <i class="lni lni-tag"></i>
          <h5>Factura de Compra</h5>
          <h4>Articulos comprados, cantidad y Stock</h4>
        </div>
      </a>

      <a href="src/ordenes/form.php">
        <div class="box">
          <i class="lni lni-investment"></i>
          <h5>Ordenes de Pagos</h5>
          <h4>Reportes de ordenes de pagos a PREVIA de todas las tiendas</h4>
        </div>
      </a>

      <a href="src/art-ajuste/form.php">
      <div class="box">
        <i class="lni lni-consulting"></i>
        <h5>Reporte Ajustes Realizados</h5>
        <h4>Pronto aun En Desarrollo</h4>
      </div>
      </a>

      <a href="src/precios/form.php">
      <div class="box">
      <i class="lni lni-dollar"></i>
        <h5>Articulos con su Precio</h5>
        <h4>Articulos con sus precio y cantidad vendida</h4>
      </div>
      </a>
<!-- VENTANA DE EGRESO Y GASTOS -->
<!-- VENTANA DE EGRESO Y GASTOS -->
   <a href="src/cob-eg-ig/form-2.php">
    <div class="box">
        <i class="lni lni-credit-cards"></i>
        <h5>Desarrollo</h5>
        <h4>Desarrollo</h4>
      </div>
    </a>

<!-- VENTANA DE EGRESO Y GASTOS -->
<!-- VENTANA DE EGRESO Y GASTOS -->
    <a href="src/ventas/form.php">
        <div class="box">
        <i class="lni lni-invest-monitor"></i>
          <h5>Ventas</h5>
          <h4>Reporte de Ventas de las Tiendas</h4>
        </div>
      </a>


    <a href="src/devolucion/form.php">
        <div class="box">
          
        <i class="lni lni-offer"></i>
          <h5>Devoluciones</h5>
          <h4>Reporte de Devoluciones de las Tiendas</h4>
        </div>
      </a>




    <a href="src/replica/form.php">
        <div class="box">
          <i class="lni lni-signal"></i>
          <h5>Replica</h5>
          <h4>Fechas de las Replicas</h4>
        </div>
      </a>
<!-- 
      <a href="src/art-stock-2/form.php">
        <div class="box">
          <i class="lni lni-coffee-cup"></i>
          <h5>Reporte Nuevo</h5>
          <h4>Pronto aun En Desarrollo</h4>
        </div>
      </a> -->

<!--       <a href="src/art-especial/form.php">
        <div class="box">
        <i class="lni lni-invention"></i>
          <h5>Reporte de Mov de Articulos</h5>
          <h4>Pronto aun En Desarrollo</h4>
        </div>
      </a>
 -->
      <a href="src/report-inv/form.php">
        <div class="box">
        <i class="lni lni-graph"></i>
          <h5>Reporte de Valor de Inventario</h5>
          <h4>Valores de Inventarios</h4>
        </div>
      </a>





      <a href="src/ordenes-compra/form.php">
        <div class="box">
          <i class="lni lni-invention"></i>
          <h5>Crear Ordenes de Compras</h5>
          <h4>Generar Ordenes de Compras para las tiendas de su Despacho</h4>
        </div>
      </a>



      <a href="../../PreviaShop_2.0/src/home.php">
        <div class="box">
          <i class="lni lni-coffee-cup"></i>
          <h5>SITIO WEB</h5>
          <h4>Sitio Web en desarrollo</h4>
        </div>
      </a>






  </main>


<!-- <i class="lni lni-shopify"></i> -->
  <?php include 'includes/footer.php'; ?>


<!--  <script src="./assets/navidad/nieve.js"></script> -->