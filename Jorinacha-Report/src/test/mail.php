
<?php
$para      = 'soporte.it.jorinacha@gmail.com';
$titulo    = 'El título';
$mensaje   = 'Hola';
$cabeceras = 'From: webmaster@example.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($para, $titulo, $mensaje, $cabeceras);
?>
