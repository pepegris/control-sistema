<?php
// backend_prediccion.php
header('Content-Type: application/json');
set_time_limit(300); // Damos tiempo extra porque conectar a 17 BDs toma su tiempo

// Rutas a tus archivos de configuración
require_once "../../services/empresas.php";
require_once "../../services/db_connection.php";

// LEER DATOS DEL FRONTEND
$input = json_decode(file_get_contents('php://input'), true);
$articuloAAnalizar = $input['codigo'] ?? '';
$apiKey = "TU_API_KEY_GEMINI"; // ⚠️ Pega tu Key aquí

if (empty($articuloAAnalizar)) {
    echo json_encode(['error' => 'No se recibió el código del artículo']);
    exit;
}

$historialConsolidado = [];
$tiendasProcesadas = 0;
$errores = [];

// --- PROCESO DE DATOS ---
foreach ($lista_replicas as $nombreSede => $datos) {
    $conn = null;
    
    // 1. Intento VPN
    $conn = ConectarSQLServer_local_vpn($datos['db'], $datos['ip']);
    
    // 2. Failover Local
    if (!$conn) {
        $conn = ConectarSQLServer_local_vpn($datos['db_local'], '172.16.1.39');
    }

    if (!$conn) {
        $errores[] = "Falló conexión a $nombreSede";
        continue;
    }

    $tiendasProcesadas++;

    // SQL: Ventas últimos 12 meses
    $sql = "SELECT YEAR(f.fec_emis) as anio, MONTH(f.fec_emis) as mes, SUM(rf.total_art) as cantidad
            FROM factura f
            INNER JOIN reng_fac rf ON f.fact_num = rf.fact_num
            WHERE rf.co_art = ? AND f.anulada = 0 AND f.fec_emis >= DATEADD(month, -12, GETDATE())
            GROUP BY YEAR(f.fec_emis), MONTH(f.fec_emis)";

    $stmt = sqlsrv_query($conn, $sql, array($articuloAAnalizar));

    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $key = $row['anio'] . "-" . str_pad($row['mes'], 2, "0", STR_PAD_LEFT);
            if (!isset($historialConsolidado[$key])) $historialConsolidado[$key] = 0;
            $historialConsolidado[$key] += $row['cantidad'];
        }
    }
    sqlsrv_close($conn);
}

// --- CONSULTA A GEMINI ---
if (empty($historialConsolidado)) {
    echo json_encode(['error' => "No se encontraron ventas para '$articuloAAnalizar' en ninguna tienda."]);
    exit;
}

ksort($historialConsolidado);
$datosJson = json_encode($historialConsolidado);

$prompt = "Actúa como planificador de demanda experto. Historial de ventas de 17 tiendas para el item '$articuloAAnalizar': $datosJson. 
Analiza estacionalidad. 
Responde SOLO este JSON sin markdown: {\"prediccion\": numero, \"tendencia\": \"texto breve\", \"accion\": \"texto breve\"}";

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey;
$data = ["contents" => [["parts" => [["text" => $prompt]]]]];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// --- PROCESAR RESPUESTA ---
if ($response) {
    $jsonResponse = json_decode($response, true);
    $textoIA = $jsonResponse['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
    $textoIA = str_replace(['```json', '```'], '', $textoIA); // Limpieza
    
    $resultadoIA = json_decode($textoIA, true);
    
    echo json_encode([
        'success' => true,
        'data' => $resultadoIA,
        'debug' => "Procesadas $tiendasProcesadas de " . count($lista_replicas) . " tiendas."
    ]);
} else {
    echo json_encode(['error' => 'Error al conectar con Gemini']);
}
?>