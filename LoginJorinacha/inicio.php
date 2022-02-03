<?php

 



 session_start();

$cuenta_on=$_SESSION['username'];

if (!isset($cuenta_on)) {
    header("location:autentica.php");
}

$cuenta_on = ucwords($cuenta_on); 


 
?>



<!DOCTYPE HTML>

<html>
	<head>
		<title>Inversiones Jorinacha</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel='shortcut icon' href='favicon.ico' />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">

    
<a href="#" class="btn btn-dark" style="position: fixed;
    bottom: 90%;
    right: 5%;
    font-size:30px;" >Usuario: <?=$cuenta_on?></a>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header" class="alt">
						<a href="inicio.php" class="logo"><strong>Inversiones</strong> <span>Jorinacha</span></a>
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

				<!-- Banner -->
					<section id="banner" class="major">
						<div class="inner">
							<header class="major">
								<h1>Sistema Jorinacha y Previa</h1>
							</header>
							<div class="content">
								<p>En desarrollo</p>
							
							</div>
						</div>
					</section>

				<!-- Main -->
					<div id="main">

						<!-- One -->
							<section id="one" class="tiles">
								<article>
									<span class="image">
										<img src="images/pic01.jpg" alt="" />
									</span>
									<header class="major">
										<h3><a href="main.php" class="link"> Sistema Administrativo</a></h3>
										<p>Gestion de Ventas, Articulos e Iventario</p>
									</header>
								</article>
								<article>
									<span class="image">
										<img src="images/pic02.jpg" alt="" />
									</span>
									<header class="major">
										<h3><a href="../control/inicio.php" class="link">Sistema de Soporte IT</a></h3>
										<p>Gestion para el dpto de Sistema</p>
									</header>
								</article>
								<article>
									<span class="image">
										<img src="images/pic03.jpg" alt="" />
									</span>
									<header class="major">
										<h3><a href="#" class="link">Contadores</a></h3>
										<p>Estado de Contadores</p>
									</header>
								</article>
								<article>
									<span class="image">
										<img src="images/pic04.jpg" alt="" />
									</span>
									<header class="major">
										<h3><a href="#" class="link">Asistencia</a></h3>
										<p>Sistema de gestion de Asistencias</p>
									</header>
								</article>
							<!-- 	<article>
									<span class="image">
										<img src="images/pic05.jpg" alt="" />
									</span>
									<header class="major">
										<h3><a href="#" class="link">Consequat</a></h3>
										<p>Ipsum dolor sit amet</p>
									</header>
								</article>
								<article>
									<span class="image">
										<img src="images/pic06.jpg" alt="" />
									</span>
									<header class="major">
										<h3><a href="#" class="link">Etiam</a></h3>
										<p>Feugiat amet tempus</p>
									</header>
								</article> -->
							</section>

						<!-- Two -->
					<!-- 		<section id="two">
								<div class="inner">
									<header class="major">
										<h2>Massa libero</h2>
									</header>
									<p>Nullam et orci eu lorem consequat tincidunt vivamus et sagittis libero. Mauris aliquet magna magna sed nunc rhoncus pharetra. Pellentesque condimentum sem. In efficitur ligula tate urna. Maecenas laoreet massa vel lacinia pellentesque lorem ipsum dolor. Nullam et orci eu lorem consequat tincidunt. Vivamus et sagittis libero. Mauris aliquet magna magna sed nunc rhoncus amet pharetra et feugiat tempus.</p>
									<ul class="actions">
										<li><a href="#" class="button next">Get Started</a></li>
									</ul>
								</div>
							</section> -->

					</div>

				

				<!-- Footer -->
					<footer id="footer">
						<div class="inner">
							<h4>Slack</h4>
							<ul class="icons">
								<li><a href="https://inv-jorinacha.slack.com" target="_blank" class="icon brands alt fa-slack"><span class="label">Slack Jorinacha</span></a></li>
							</ul>
							<ul class="copyright">
								<li><p>Inversiones Jorinacha © 2021 By Andres Salcedo | Departamento de Sistema</p> </li>
							</ul>
						</div>
					</footer>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>