<?php
header('Content-Type: text/html; charset=utf-8');

// ⚠️ PEGA TU API KEY AQUÍ OTRA VEZ
$apiKey = "AIzaSyAv_LmWLvbm4QmbfTJ0927cOHpHYEY5KP4"; 

$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $apiKey;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Saltamos SSL local
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<h1>Diagnóstico de Modelos Disponibles</h1>";

if ($httpCode != 200) {
    echo "<h3 style='color:red'>Error conectando a Google (Código $httpCode)</h3>";
    echo "Respuesta: " . $response;
    exit;
}

$data = json_decode($response, true);

if (isset($data['models'])) {
    echo "<ul>";
    foreach ($data['models'] as $model) {
        // Filtramos solo los que sirven para generar contenido
        if (in_array("generateContent", $model['supportedGenerationMethods'])) {
            echo "<li><strong>" . $model['name'] . "</strong><br>";
            echo "<small>" . $model['description'] . "</small></li><br>";
        }
    }
    echo "</ul>";
} else {
    echo "No se encontraron modelos. Respuesta: <pre>" . print_r($data, true) . "</pre>";
}
?>