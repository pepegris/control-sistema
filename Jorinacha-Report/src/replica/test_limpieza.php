<?php
$ruta_carpeta = __DIR__ . '/assets';
$ruta_archivo = $ruta_carpeta . '/ultima_fecha.txt';

echo "<h2>Diagnóstico de Escritura</h2>";
echo "Ruta intentada: " . $ruta_archivo . "<br><br>";

// 1. Verificar si la carpeta existe
if (!is_dir($ruta_carpeta)) {
    die("❌ La carpeta 'assets' no existe.");
}

// 2. Verificar si PHP puede escribir en la carpeta
if (is_writable($ruta_carpeta)) {
    echo "✅ La carpeta 'assets' tiene permisos de escritura.<br>";
} else {
    echo "❌ <b>ERROR CRÍTICO:</b> PHP no tiene permiso para escribir en la carpeta 'assets'.<br>";
    echo "Solución: Dale permiso 'Modificar' al usuario <b>IUSR</b> sobre la carpeta assets.<br>";
}

// 3. Intentar crear/modificar el archivo
$fecha_test = date('Y-m-d H:i:s');
if (file_put_contents($ruta_archivo, $fecha_test) !== false) {
    echo "✅ <b>¡ÉXITO!</b> Se ha creado/actualizado el archivo correctamente.<br>";
    echo "Contenido guardado: " . file_get_contents($ruta_archivo);
} else {
    echo "❌ Falló la escritura del archivo.";
}
?>