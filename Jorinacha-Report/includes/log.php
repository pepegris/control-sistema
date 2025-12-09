<?php
// includes/log.php

// Solo iniciamos sesión si NO hay una activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificamos el usuario logueado
$cuenta_on = isset($_SESSION['username']) ? $_SESSION['username'] : null;

if (!$cuenta_on) {
    // Si no está logueado, lo mandamos al login general
    header("location:../../home.php");
    exit;
}

$cuenta_on = ucwords($cuenta_on); 
?>