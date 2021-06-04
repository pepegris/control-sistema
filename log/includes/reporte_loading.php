<link rel="stylesheet" href="../css/bootstrap-4.5.2-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../js/animations.css">
<style>


/* ------------------------- */
/* POPUP */
/* ------------------------- */
/* 
.overlay {
    position: relative;
	background: rgba(0,0,0,.3);

	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	align-items: center;
	justify-content: center;
	display: flex;
	visibility: hidden;
}

.overlay.active {
	visibility: visible;
}

.popup {
	background: #F8F8F8;
	box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.3);
	border-radius: 3px;
	font-family: 'Montserrat', sans-serif;
	padding: 20px;
	text-align: center;
	width: 600px;
	
	transition: .3s ease all;
	transform: scale(0.7);
	opacity: 0;
}

.popup .btn-cerrar-popup {
	font-size: 16px;
	line-height: 16px;
	display: block;
	text-align: right;
	transition: .3s ease all;
	color: #BBBBBB;
}

.popup .btn-cerrar-popup:hover {
	color: #000;
}

.popup h3 {
	font-size: 36px;
	font-weight: 600;
	margin-bottom: 10px;
	opacity: 0;
}

.popup h4 {
	font-size: 26px;
	font-weight: 300;
	margin-bottom: 40px;
	opacity: 0;
}

.popup form .contenedor-inputs {
	opacity: 0;
}

.popup form .contenedor-inputs input {
	width: 100%;
	margin-bottom: 20px;
	height: 52px;
	font-size: 18px;
	line-height: 52px;
	text-align: center;
	border: 1px solid #BBBBBB;
}

.popup form .btn-submit {
	padding: 0 20px;
	height: 40px;
	line-height: 40px;
	border: none;
	color: #fff;
	background: #5E7DE3;
	border-radius: 3px;
	font-family: 'Montserrat', sans-serif;
	font-size: 16px;
	cursor: pointer;
	transition: .3s ease all;
}

.popup form .btn-submit:hover {
	background: rgba(94,125,227, .9);
} */

/* ------------------------- */
/* ANIMACIONES */
/* ------------------------- *//* 
.popup.active {	transform: scale(1); opacity: 1; }
.popup.active h3 { animation: entradaTitulo .8s ease .5s forwards; }
.popup.active h4 { animation: entradaSubtitulo .8s ease .5s forwards; }
.popup.active .contenedor-inputs { animation: entradaInputs 1s linear 1s forwards; }

@keyframes entradaTitulo {
	from {
		opacity: 0;
		transform: translateY(-25px);
	}

	to {
		transform: translateY(0);
		opacity: 1;
	}
}

@keyframes entradaSubtitulo {
	from {
		opacity: 0;
		transform: translateY(25px);
	}

	to {
		transform: translateY(0);
		opacity: 1;
	}
}

@keyframes entradaInputs {
	from { opacity: 0; }
	to { opacity: 1; }
}





.preloader {
    
  width: 100px;
  height: 100px;
  border: 10px solid #eee;
  border-top: 10px solid #666;
  border-radius: 50%;
  animation-name: girar;
  animation-duration: 2s;
  animation-iteration-count: infinite;
  animation-timing-function: linear;
}
@keyframes girar {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
 */

</style>


<!-- <div class="overlay" id="overlay">
			<div class="popup" id="popup">
				<!-- <a href="#" id="btn-cerrar-popup" class="btn-cerrar-popup"><i class="fas fa-times"></i></a> -->
        <!-- <h1>Revisando Datos</h1>
        <center>   <div class="preloader"></div></center>
			</div>
		</div>
	</div> --> 

	<script src="../js/popup.js"></script>


    
	<script src="../js/jquery-1.10.1.min.js"></script>