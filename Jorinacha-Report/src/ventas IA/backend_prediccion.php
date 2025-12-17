<?php
// backend_prediccion.php
header('Content-Type: application/json');
set_time_limit(300); 

require_once "../../services/empresas.php";
require_once "../../services/db_connection.php";

$input = json_decode(file_get_contents('php://input'), true);

// ⚠️ TU API KEY NUEVA (Solo en el código)
$apiKey = "AIzaSyCcDEFD1k_unQiRUl9YaDcf9V-G1KE7PSc"; 

// 🛡️ LISTA DE MODELOS (Plan A -> Plan B)
// Si el primero falla (está lleno), usará el segundo automáticamente.
$modelosDisponibles = [
    "gemini-1.5-flash",    // El "Tanque": 15 peticiones/minuto (Muy estable)
    "gemini-flash-latest", // Alias seguro a la última versión estable
    "gemini-2.5-flash"     // El más nuevo (Úsalo solo si los otros fallan)
];

// ... (Lógica de Filtros igual que antes) ...
$producto  = $input['producto'] ?? '';
$linea     = $input['linea'] ?? '';
$sublinea  = $input['sublinea'] ?? '';

$modoAnalisis = "";
$filtroSQL = "";
$paramsSQL = [];

if (!empty($producto)) {
    $modoAnalisis = "PRODUCTO ESPECÍFICO ($producto)";
    $filtroSQL = " AND rf.co_art = ? ";
    $paramsSQL = [$producto];
} elseif (!empty($linea)) {
    $modoAnalisis = "LÍNEA DE PRODUCTOS ($linea)";
    $filtroSQL = " AND a.co_lin = ? ";
    $paramsSQL = [$linea];
    if (!empty($sublinea)) {
        $modoAnalisis .= " - SUBLÍNEA ($sublinea)";
        $filtroSQL .= " AND a.co_sub = ? "; 
        $paramsSQL[] = $sublinea;
    }
} else {
    echo json_encode(['error' => 'Debes seleccionar un producto o una línea.']);
    exit;
}

// ... (Extracción SQL igual que antes) ...
$historialConsolidado = [];
$tiendasConsultadas = 0;

foreach ($lista_replicas as $nombreSede => $datos) {
    $conn = ConectarSQLServer_local_vpn($datos['db'], $datos['ip']);
    if (!$conn) $conn = ConectarSQLServer_local_vpn($datos['db_local'], '172.16.1.39');
    if (!$conn) continue;

    $tiendasConsultadas++;

    $sql = "SELECT YEAR(f.fec_emis) as anio, MONTH(f.fec_emis) as mes, SUM(rf.total_art) as cantidad
            FROM factura f
            INNER JOIN reng_fac rf ON f.fact_num = rf.fact_num
            INNER JOIN art a ON rf.co_art = a.co_art
            WHERE f.anulada = 0 AND f.fec_emis >= DATEADD(month, -12, GETDATE()) $filtroSQL
            GROUP BY YEAR(f.fec_emis), MONTH(f.fec_emis)";

    $stmt = sqlsrv_query($conn, $sql, $paramsSQL);
    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $key = $row['anio'] . "-" . str_pad($row['mes'], 2, "0", STR_PAD_LEFT);
            if (!isset($historialConsolidado[$key])) $historialConsolidado[$key] = 0;
            $historialConsolidado[$key] += $row['cantidad'];
        }
    }
    sqlsrv_close($conn);
}

if (empty($historialConsolidado)) {
    echo json_encode(['error' => "No se encontraron ventas (Tiendas: $tiendasConsultadas)."]);
    exit;
}
ksort($historialConsolidado);

// --- 🤖 AQUÍ EMPIEZA LA MAGIA DEL FAILOVER ---

$prompt = "Eres un experto analista. Analiza ventas de 17 tiendas: $modoAnalisis. 
Datos: " . json_encode($historialConsolidado) . ".
1. Predice venta PRÓXIMO MES.
2. Tendencia.
3. Responde SOLO JSON: {\"prediccion\": numero_entero, \"tendencia\": \"texto corto\", \"accion\": \"texto corto\"}";

$respuestaExitosa = null;
$ultimoError = "";

// Intentamos con cada modelo de la lista
foreach ($modelosDisponibles as $modeloActual) {
    
    // URL dinámica según el modelo actual
    $url = "https://generativelanguage.googleapis.com/v1beta/models/$modeloActual:generateContent?key=" . $apiKey;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["contents" => [["parts" => [["text" => $prompt]]]]]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Si funciona (Código 200), procesamos y ROMPEMOS el bucle
    if ($httpCode == 200 && $response) {
        $json = json_decode($response, true);
        if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
            $respuestaExitosa = $json['candidates'][0]['content']['parts'][0]['text'];
            $modeloUsado = $modeloActual; // Guardamos cuál funcionó para saber
            break; // ¡ÉXITO! Salimos del foreach
        }
    } else {
        // Si falló, guardamos el error y dejamos que el bucle siga al siguiente modelo
        $ultimoError = "Fallo modelo $modeloActual (Código $httpCode)";
    }
}

// --- RESULTADO FINAL ---

if ($respuestaExitosa) {
    $txt = str_replace(['```json', '```'], '', $respuestaExitosa);
    $dataIA = json_decode($txt, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        echo json_encode([
            'success' => true, 
            'data' => $dataIA,
            'debug' => "Analizado con modelo: $modeloUsado" // Para que sepas cuál respondió
        ]);
    } else {
        echo json_encode(['error' => 'La IA respondió pero el JSON no es válido.']);
    }
} else {
    // Si llegamos aquí, fallaron TODOS los modelos
    echo json_encode(['error' => "Todos los modelos fallaron. Último error: $ultimoError"]);
}
?>