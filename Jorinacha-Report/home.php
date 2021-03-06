<?php

 



 session_start();

$cuenta_on=$_SESSION['username'];

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
    <title>Jorinacha Report</title>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="/">Jorinacha</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
  
      <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav ml-auto">

          <li class="nav-item">
            <a class="nav-link" href="#" >Articulos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Pricing</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>

        </ul>
<!--          <form class="d-flex">
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
            <i class="lni lni-write"></i>
            <h5>Stock</h5>
            <h4>Articulos vendidos a una fecha y stock Actual</h4>
        </div>
    </a>

    <a href="./services/mysql.php" >
        <div class="box">
            <i class="lni lni-coffee-cup"></i>
            <h5>Pronto</h5>
            <h4>Pronto aun En Desarrollo</h4>
        </div>
    </a>
    <div class="box">
        <i class="lni lni-construction"></i>
        <h5>Pronto</h5>
        <h4>Pronto aun En Desarrollo</h4>
    </div>
    <div class="box">
        <i class="lni lni-coffee-cup"></i>
        <h5>Pronto</h5>
        <h4>Pronto aun En Desarrollo</h4>
    </div>
    <div class="box">
        <i class="lni lni-construction"></i>
        <h5>Pronto</h5>
        <h4>Pronto aun En Desarrollo</h4>
    </div>
    <div class="box">
        <i class="lni lni-coffee-cup"></i>
        <h5>Pronto</h5>
        <h4>Pronto aun En Desarrollo</h4>
    </div>
    <div class="box">
        <i class="lni lni-construction"></i>
        <h5>Pronto</h5>
        <h4>Pronto aun En Desarrollo</h4>
    </div>
    <div class="box">
        <i class="lni lni-coffee-cup"></i>
        <h5>Pronto</h5>
        <h4>Pronto aun En Desarrollo</h4>
    </div>
    <div class="box">
        <i class="lni lni-construction"></i>
        <h5>Pronto</h5>
        <h4>Pronto aun En Desarrollo</h4>
    </div>
    <div class="box">
        <i class="lni lni-coffee-cup"></i>
        <h5>Pronto</h5>
        <h4>Pronto aun En Desarrollo</h4>
    </div> 
</div>

</main>

<?php  include 'includes/footer.php'; ?>