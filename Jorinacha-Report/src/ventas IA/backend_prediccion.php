<?php
// backend_prediccion.php
header('Content-Type: application/json');
set_time_limit(300);

require_once "../../services/empresas.php";
require_once "../../services/db_connection.php";

$input = json_decode(file_get_contents('php://input'), true);
$apiKey = "AIzaSyAv_LmWLvbm4QmbfTJ0927cOHpHYEY5KP4"; // ⚠️ NO OLVIDES TU KEY

// Filtros recibidos
$producto  = $input['producto'] ?? ''; // Código de artículo
$linea     = $input['linea'] ?? '';
$sublinea  = $input['sublinea'] ?? '';

// Definir el modo de operación para el Prompt y el SQL
$modoAnalisis = "";
$filtroSQL = "";
$paramsSQL = [];

// LOGICA DE PRIORIDAD: Producto > Sublínea > Línea
if (!empty($producto)) {
    $modoAnalisis = "PRODUCTO ESPECÍFICO ($producto)";
    // Join con 'art' para asegurar que existe, aunque filtra por reng_fac
    $filtroSQL = " AND rf.co_art = ? ";
    $paramsSQL = [$producto];
} 
elseif (!empty($linea)) {
    $modoAnalisis = "LÍNEA DE PRODUCTOS ($linea)";
    $filtroSQL = " AND a.co_lin = ? ";
    $paramsSQL = [$linea];
    
    if (!empty($sublinea)) {
        $modoAnalisis .= " - SUBLÍNEA ($sublinea)";
        $filtroSQL .= " AND a.co_sub = ? "; // Ajuste: Profit suele usar co_sub en tabla art
        $paramsSQL[] = $sublinea;
    }
} else {
    echo json_encode(['error' => 'Debes seleccionar un producto o una línea.']);
    exit;
}

$historialConsolidado = [];

foreach ($lista_replicas as $nombreSede => $datos) {
    $conn = ConectarSQLServer_local_vpn($datos['db'], $datos['ip']);
    if (!$conn) $conn = ConectarSQLServer_local_vpn($datos['db_local'], '172.16.1.39');
    if (!$conn) continue;

    // Query avanzado con JOIN a la tabla de Artículos (art) para filtrar por línea
    $sql = "
        SELECT 
            YEAR(f.fec_emis) as anio,
            MONTH(f.fec_emis) as mes,
            SUM(rf.total_art) as cantidad
        FROM 
            factura f
            INNER JOIN reng_fac rf ON f.fact_num = rf.fact_num
            INNER JOIN art a ON rf.co_art = a.co_art
        WHERE 
            f.anulada = 0 
            AND f.fec_emis >= DATEADD(month, -12, GETDATE())
            $filtroSQL
        GROUP BY 
            YEAR(f.fec_emis), MONTH(f.fec_emis)
    ";

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
    echo json_encode(['error' => "No hay datos de venta para: $modoAnalisis"]);
    exit;
}

ksort($historialConsolidado);

// Prompt ajustado al contexto
$prompt = "Eres un experto analista. Analiza este historial de ventas consolidado (17 tiendas) para: $modoAnalisis. 
Datos (Mes: Cantidad): " . json_encode($historialConsolidado) . ".
Responde SOLO JSON: {\"prediccion\": numero_entero, \"tendencia\": \"texto corto\", \"accion\": \"texto corto\"}";

// Llamada a Gemini (Igual que antes)
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["contents" => [["parts" => [["text" => $prompt]]]]]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $json = json_decode($response, true);
    $txt = $json['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
    $txt = str_replace(['```json', '```'], '', $txt);
    echo json_encode(['success' => true, 'data' => json_decode($txt)]);
} else {
    echo json_encode(['error' => 'Error API IA']);
}
?>