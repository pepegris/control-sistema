<?php
// includes/security.php

// Solo iniciamos sesión si NO hay una activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$CLAVE_MAESTRA = "Zeus33$"; 

// 1. DETECTAR SI VIENE LA CLAVE EN LA URL (LOGIN)
if (isset($_GET['key'])) {
    if ($_GET['key'] === $CLAVE_MAESTRA) {
        $_SESSION['acceso_autorizado'] = true;
        
        // LIMPIEZA DE URL (Redirección)
        $url_limpia = strtok($_SERVER["REQUEST_URI"], '?');
        header("Location: " . $url_limpia);
        exit;
    } else {
        // Clave incorrecta: borramos flag de seguridad pero mantenemos el login de usuario
        unset($_SESSION['acceso_autorizado']);
    }
}

// 2. VERIFICAR PERMISO ESPECIAL
if (!isset($_SESSION['acceso_autorizado']) || $_SESSION['acceso_autorizado'] !== true) {
    http_response_code(403);
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Acceso Restringido</title>
        <style>
            body { background: #121212; color: #e0e0e0; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
            .lock-screen { text-align: center; border: 1px solid #333; padding: 50px; border-radius: 15px; background: #1e1e1e; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
            h1 { color: #ff5555; margin-bottom: 20px; text-transform: uppercase; }
            .icon { font-size: 60px; margin-bottom: 20px; }
        </style>
    </head>
    <body>
        <div class="lock-screen">
            <div class="icon">🔒</div>
            <h1>Acceso Denegado</h1>
            <p>Se requiere llave de seguridad para esta zona crítica.</p>
        </div>
    </body>
    </html>
    <?php
    exit; 
}
?>