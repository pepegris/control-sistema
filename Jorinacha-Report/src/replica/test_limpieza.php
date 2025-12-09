<?php
require '../../includes/log.php';
include '../../services/adm/replica/config_replicas.php';

echo "<h1>🕵️ Diagnóstico de Configuración</h1>";

// 1. Verificar Credenciales
$pass = 'Zeus33$';
echo "<h3>1. Verificando Contraseña</h3>";
echo "Longitud esperada: 7 caracteres.<br>";
echo "Longitud real: <b>" . strlen($pass) . "</b> caracteres.<br>";
if (strlen($pass) > 7) {
    echo "<span style='color:red'>⚠️ ALERTA: La contraseña tiene espacios o caracteres ocultos.</span><br>";
} else {
    echo "<span style='color:green'>✅ Contraseña limpia.</span><br>";
}

// 2. Verificar Array de Tiendas
echo "<h3>2. Verificando config_replicas.php</h3>";

if (!isset($lista_replicas)) {
    die("<h2 style='color:red'>❌ Error: No se cargó la variable \$lista_replicas</h2>");
}

// Probamos con la primera tienda (ACARIGUA)
$tienda = 'ACARIGUA';
if (isset($lista_replicas[$tienda])) {
    $datos = $lista_replicas[$tienda];
    $db_remota = $datos['db_remota'];
    
    echo "Analizando tienda: <b>$tienda</b><br>";
    echo "IP: [" . $datos['ip'] . "]<br>";
    echo "DB Remota: [" . $db_remota . "]<br>";
    
    // Verificamos si hay espacios en el nombre de la DB
    if (trim($db_remota) !== $db_remota) {
        echo "<span style='color:red'>❌ ERROR: El nombre de la base de datos tiene espacios ocultos.</span><br>";
        var_dump($db_remota);
    } else {
        echo "<span style='color:green'>✅ Nombre de DB limpio.</span><br>";
    }

    // 3. INTENTO DE CONEXIÓN PURA (Sin variables externas)
    echo "<h3>3. Prueba de Fuego: Conexión Manual</h3>";
    
    $connectionInfo = array(
        "Database" => trim($db_remota), // Limpiamos por si acaso
        "UID" => "mezcla",
        "PWD" => "Zeus33$",
        "LoginTimeout" => 5
    );
    
    // Forzamos la conexión
    $conn = sqlsrv_connect($datos['ip'], $connectionInfo);
    
    if ($conn) {
        echo "<h2 style='color:green'>✅ ¡CONEXIÓN EXITOSA!</h2>";
        echo "El problema estaba en cómo se pasaban las variables en el otro archivo.";
        sqlsrv_close($conn);
    } else {
        echo "<h2 style='color:red'>❌ FALLÓ LA CONEXIÓN</h2>";
        if (($errors = sqlsrv_errors()) != null) {
            foreach ($errors as $error) {
                echo "Mensaje SQL: " . $error['message'] . "<br>";
            }
        }
    }

} else {
    echo "No se encontró la tienda Acarigua para probar.";
}
?>