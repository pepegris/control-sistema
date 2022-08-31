<?php

session_destroy();

?>
<!DOCTYPE HTML>

<html>

<head>
	<title>Inversiones Jorinacha</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel='shortcut icon' href='favicon.ico' />
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Estilos personal CSS -->
	<link rel="stylesheet" href="css/materialize/css/materialize.min.css">
	<link rel="stylesheet" href="css/login/animations.css">
	<link rel="stylesheet" href="css/login/a_estilo.css">
	<link rel="stylesheet" href="assets/css/main.css" />
	<noscript>
		<link rel="stylesheet" href="assets/css/noscript.css" />
	</noscript>


</head>
<style>
	#celda {


		margin-left: 10px;
	}


	#celda2 {

		margin-left: 10px;
	}
</style>


<body class="is-preload">

	<!-- Wrapper -->


	<!-- Header -->
	<header id="header" class="alt">
		<a href="index.html" class="logo"><strong>Inversiones</strong> <span>Jorinacha</span></a>
		<!-- <nav>
							<a href="#menu">Menu</a>
						</nav> -->
	</header>

	<!-- Menu -->
	<!-- 		<nav id="menu">
						<ul class="links">
							<li><a href="index.html">Home</a></li>
							<li><a href="landing.html">Landing</a></li>
							<li><a href="generic.html">Generic</a></li>
							<li><a href="elements.html">Elements</a></li>
						</ul>
						<ul class="actions stacked">
							<li><a href="#" class="button primary fit">Get Started</a></li>
							<li><a href="#" class="button fit">Log In</a></li>
						</ul>
					</nav> -->

	<form class="slideUp" method="POST" action="auntenticando.php">
		<div id="login" class="z-depth-5">
			<div id="sistema">
				<h2>Jorinacha</h2>
				<div class="row" id="celda">
					<div class="col s12">
						<div class="row">
							<div class="input-field col s12">

								<input type="text" id="autocomplete-input" name="user" class="autocomplete">
								<label for="autocomplete-input">Usuario</label>
							</div>
						</div>
					</div>
				</div>

				<div class="row" id="celda2">
					<div class="col s12">
						<div class="row">
							<div class="input-field col s12">

								<input type="password" name="pass" id="autocomplete-input" class="autocomplete">
								<label for="autocomplete-input">Contraseña</label>
							</div>
						</div>
					</div>
				</div>
				<button type="submit" class="btn ">Entrar</button>
				<br>
				<!--  <a href="../resumen.php" class="btn blue darken-1 ">Ver Resumen Diario</a>
									<a href="../monitoreo_remotos/usuarios_remotos.php" class="btn blue darken-1">Usuarios Conectado</a> -->
			</div>


			<!--    <div class="derecha">
									<div id="invitado">
										<h4>Ver Resumen Diario</h4>
									  
										
									   </div>
									  </div>
							  	</div> -->



		</div>
	</form>

	<!-- Scripts -->


	<script src="css/materialize/js/materialize.min.js"></script>
	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/jquery.scrolly.min.js"></script>
	<script src="assets/js/jquery.scrollex.min.js"></script>
	<script src="assets/js/browser.min.js"></script>
	<script src="assets/js/breakpoints.min.js"></script>
	<script src="assets/js/util.js"></script>
	<script src="assets/js/main.js"></script>

</body>

</html>


<!-- 
<!doctype html>
<html lang="en">
  <head>
   
  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   

    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    
     <link rel="stylesheet" href="css/style.css" >
  </head>
<body>
    <form class="mrtop text-center border border-dark container col-sm-6 pt-4 pb-4" method="POST" action="auten.php">
        <div class="container ">
        <div class="form-group">
          <h6 class="text-center m-2 pb-4">Iniciar Sesion</h6>
          <label for="User" class="text-center " >Usuario</label>
          <input type="text" class="form-control border-dark text-center" id="User"  name="user" placeholder="Usuario"  >
        </div>
        <div class="form-group">
          <label for="pass" class="text-center ">Password</label>
          <input type="password" class="form-control border-dark text-center" id="pass" name="pass" placeholder="Password"  >
        </div>
        <button type="submit" class="btn btn-primary border-dark">Iniciar</button>
      </form>
    </div>
    

    
    <script src="js/geo.js" type="text/javascript" ></script>
   
    
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>

 -->