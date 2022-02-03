<?php
//Renaudar sesion
session_start();
// elimina todas las variables de las session.
session_unset();
 
// elimina todas las session.
session_destroy();
header('location: autentica.php');