<?php
// backend_prediccion.php
header('Content-Type: application/json');
// Aumentamos el tiempo de ejecución porque consultar 17 tiendas toma tiempo
set_time_limit(300); 

// AJUSTA ESTAS RUTAS SI ES NECESARIO SEGÚN TU CARPETA
require_once "../../services/empresas.php";
require_once "../../services/db_connection.php";

// LEER DATOS DEL FRONTEND
$input = json_decode(file_get_contents('php://input'), true);

// ⚠️ TU API KEY (Mantenla segura)
$apiKey = "AIzaSyAv_LmWLvbm4QmbfTJ0927cOHpHYEY5KP4"; 

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
    // Join con 'art' para asegurar que existe
    $filtroSQL = " AND rf.co_art = ? ";
    $paramsSQL = [$producto];
} 
elseif (!empty($linea)) {
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

$historialConsolidado = [];
$tiendasConsultadas = 0;

// --- 1. EXTRACCIÓN DE DATOS (SQL SERVER) ---
foreach ($lista_replicas as $nombreSede => $datos) {
    $conn = null;

    // Intento 1: VPN
    $conn = ConectarSQLServer_local_vpn($datos['db'], $datos['ip']);
    
    // Intento 2: Failover Local (Si VPN falla)
    if (!$conn) {
        $conn = ConectarSQLServer_local_vpn($datos['db_local'], '172.16.1.39');
    }

    if (!$conn) continue; // Si falla ambas, saltamos tienda

    $tiendasConsultadas++;

    // Query avanzado con JOIN a tabla Articulos (art)
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

// Validación de datos vacíos
if (empty($historialConsolidado)) {
    echo json_encode(['error' => "No se encontraron ventas (Consultadas: $tiendasConsultadas tiendas) para: $modoAnalisis"]);
    exit;
}

ksort($historialConsolidado);

// --- 2. PREPARACIÓN DEL PROMPT ---
$prompt = "Eres un experto analista de inventario. Analiza este historial de ventas consolidado (17 tiendas) para: $modoAnalisis. 
Datos Históricos (Mes: Cantidad): " . json_encode($historialConsolidado) . ".
Tu tarea:
1. Predice la venta para el PRÓXIMO MES.
2. Identifica la tendencia.
3. Responde SOLO en formato JSON estricto: {\"prediccion\": numero_entero, \"tendencia\": \"texto corto\", \"accion\": \"texto corto\"}";

// --- 3. CONEXIÓN A GEMINI (CON FIX SSL) ---
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey;

$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => $prompt]
            ]
        ]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// 🔥 FIX SSL: IGNORAR VERIFICACIÓN DE CERTIFICADO (CRUCIAL PARA LOCALHOST)
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$errorCurl = curl_error($ch); // Capturamos error técnico
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Código de respuesta (200, 400, 500)
curl_close($ch);

// --- 4. MANEJO DE RESPUESTA Y ERRORES ---

// A. Error Técnico (Internet, DNS, SSL)
if ($errorCurl) {
    echo json_encode(['error' => "Error de Conexión cURL: " . $errorCurl]);
    exit;
}

// B. Error de Google (API Key inválida, JSON mal formado, etc)
if ($httpCode != 200) {
    echo json_encode(['error' => "Google rechazó la solicitud (Código $httpCode). Respuesta: " . $response]);
    exit;
}

// C. Procesar Respuesta Exitosa
if ($response) {
    $json = json_decode($response, true);
    
    // Verificar estructura
    if (!isset($json['candidates'][0]['content']['parts'][0]['text'])) {
        echo json_encode(['error' => 'La IA respondió, pero el formato es inesperado.']);
        exit;
    }

    $txt = $json['candidates'][0]['content']['parts'][0]['text'];
    
    // Limpieza agresiva de Markdown (```json ... ```)
    $txt = str_replace(['```json', '```'], '', $txt); 
    
    $dataIA = json_decode($txt, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        echo json_encode(['success' => true, 'data' => $dataIA]);
    } else {
        echo json_encode(['error' => 'La IA no devolvió un JSON válido. Texto recibido: ' . substr($txt, 0, 100) . '...']);
    }
} else {
    echo json_encode(['error' => 'Respuesta vacía del servidor de IA.']);
}
?>