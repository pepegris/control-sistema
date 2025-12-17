<?php
// backend_prediccion.php
header('Content-Type: application/json');
set_time_limit(300); 

require_once "../../services/empresas.php";
require_once "../../services/db_connection.php";

$input = json_decode(file_get_contents('php://input'), true);
$apiKey = "AIzaSyCcDEFD1k_unQiRUl9YaDcf9V-G1KE7PSc"; 

$modelosDisponibles = ["gemini-1.5-flash", "gemini-flash-latest", "gemini-2.5-flash"];

// DATOS
$producto  = $input['producto'] ?? '';
$linea     = $input['linea'] ?? '';
$sublinea  = $input['sublinea'] ?? '';
$meses     = intval($input['meses'] ?? 12);
if (!in_array($meses, [3, 6, 9, 12])) { $meses = 12; }

// FECHA ACTUAL PARA CONTEXTO
$fechaActual = date("d-m-Y");
$mesProximo  = date("F Y", strtotime("+1 month")); // Ej: January 2026

// FILTROS
$modoAnalisis = "";
$filtroVenta = ""; $filtroDevol = ""; $paramsSQL = [];

if (!empty($producto)) {
    $modoAnalisis = "PRODUCTO: $producto";
    $filtroVenta = " AND rf.co_art = ? ";
    $filtroDevol = " AND rd.co_art = ? ";
    $paramsSQL = [$producto, $producto]; 
} elseif (!empty($linea)) {
    $modoAnalisis = "LÍNEA: $linea";
    $filtroVenta = " AND a.co_lin = ? ";
    $filtroDevol = " AND a.co_lin = ? ";
    $paramsSQL = [$linea, $linea];
    if (!empty($sublinea)) {
        $modoAnalisis .= " / SUB: $sublinea";
        $filtroVenta .= " AND a.co_sub = ? ";
        $filtroDevol .= " AND a.co_sub = ? ";
        $paramsSQL[] = $sublinea;
        $paramsSQL[] = $sublinea;
    }
} else { echo json_encode(['error' => 'Falta selección.']); exit; }

// --- ESTRUCTURAS DE DATOS ---
$datosCompletos = []; // Para la gráfica temporal (Global)
$rankingTiendas = []; // NUEVO: Para saber quién vende más
$tiendasConsultadas = 0;

foreach ($lista_replicas as $nombreSede => $datos) {
    $conn = ConectarSQLServer_local_vpn($datos['db'], $datos['ip']);
    if (!$conn) $conn = ConectarSQLServer_local_vpn($datos['db_local'], '172.16.1.39');
    if (!$conn) continue;

    $tiendasConsultadas++;
    $ventaTotalSede = 0; // Acumulador local

    // SQL (Mismo de antes: Ventas + Devoluciones)
    $sql = "
        SELECT 'V' as tipo, YEAR(f.fec_emis) as anio, MONTH(f.fec_emis) as mes, SUM(rf.total_art) as cantidad
        FROM factura f
        INNER JOIN reng_fac rf ON f.fact_num = rf.fact_num
        INNER JOIN art a ON rf.co_art = a.co_art
        WHERE f.anulada = 0 AND f.fec_emis >= DATEADD(month, -$meses, GETDATE()) $filtroVenta
        GROUP BY YEAR(f.fec_emis), MONTH(f.fec_emis)
        UNION ALL
        SELECT 'D' as tipo, YEAR(d.fec_emis) as anio, MONTH(d.fec_emis) as mes, SUM(rd.total_art) as cantidad
        FROM dev_cli d
        INNER JOIN reng_dvc rd ON d.fact_num = rd.fact_num
        INNER JOIN art a ON rd.co_art = a.co_art
        WHERE d.anulada = 0 AND d.fec_emis >= DATEADD(month, -$meses, GETDATE()) $filtroDevol
        GROUP BY YEAR(d.fec_emis), MONTH(d.fec_emis)
    ";

    $stmt = sqlsrv_query($conn, $sql, $paramsSQL);
    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $key = $row['anio'] . "-" . str_pad($row['mes'], 2, "0", STR_PAD_LEFT);
            if (!isset($datosCompletos[$key])) $datosCompletos[$key] = ['ventas' => 0, 'devoluciones' => 0];

            if ($row['tipo'] == 'V') {
                $datosCompletos[$key]['ventas'] += $row['cantidad'];
                $ventaTotalSede += $row['cantidad']; // Sumamos al total de esta tienda
            } else {
                $datosCompletos[$key]['devoluciones'] += $row['cantidad'];
                $ventaTotalSede -= $row['cantidad']; // Restamos devoluciones al total de tienda
            }
        }
    }
    
    // Guardamos el total neto de esta tienda
    $rankingTiendas[$nombreSede] = $ventaTotalSede;
    
    sqlsrv_close($conn);
}

if (empty($datosCompletos)) { echo json_encode(['error' => "Sin datos."]); exit; }
ksort($datosCompletos);

// Ordenamos tiendas de MAYOR a MENOR venta
arsort($rankingTiendas);
// Tomamos el TOP 5 para no saturar el prompt, pero pasamos el total
$topTiendas = array_slice($rankingTiendas, 0, 5); 

// Datos Gráfica Frontend
foreach ($datosCompletos as $mes => $vals) {
    $neto = $vals['ventas'] - $vals['devoluciones'];
    $datosGrafica[$mes] = $neto > 0 ? $neto : 0;
}

// --- PROMPT ESPECÍFICO ---
$prompt = "
Actúa como Gerente Nacional de Logística en Venezuela.
Analiza $modoAnalisis.
Fecha actual: $fechaActual.
Debes predecir la demanda para el MES COMPLETO DE: $mesProximo.

DATOS HISTÓRICOS GLOBALES (Mes: {Ventas, Devoluciones}): " . json_encode($datosCompletos) . "

DISTRIBUCIÓN GEOGRÁFICA (Ranking de Ventas Acumuladas): " . json_encode($topTiendas) . "

TU MISIÓN:
1. Predice la venta neta total para $mesProximo.
2. Explica la distribución geográfica: Menciona explícitamente qué tiendas/estados impulsan esta demanda y cuáles no.
3. Evalúa la calidad (tasa de devoluciones).

RESPUESTA JSON ESTRICTA:
{
    \"prediccion\": numero_entero, 
    \"periodo_prediccion\": \"Ej: Enero 2026\",
    \"tendencia\": \"Texto explicando qué tiendas lideran la demanda y la tendencia temporal (Max 25 palabras).\", 
    \"alerta_calidad\": \"Estado de la calidad.\",
    \"accion\": \"Recomendación logística específica por región (Ej: Mover stock de Coro a Caracas) (Max 25 palabras)\"
}";

// --- CONEXIÓN IA ---
$respuestaExitosa = null;
foreach ($modelosDisponibles as $modeloActual) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/$modeloActual:generateContent?key=" . $apiKey;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["contents" => [["parts" => [["text" => $prompt]]]]]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200 && $response) {
        $jsonResponse = json_decode($response, true);
        if (isset($jsonResponse['candidates'][0]['content']['parts'][0]['text'])) {
            $respuestaExitosa = $jsonResponse['candidates'][0]['content']['parts'][0]['text'];
            break;
        }
    }
}

if ($respuestaExitosa) {
    $txt = str_replace(['```json', '```'], '', $respuestaExitosa);
    $dataIA = json_decode($txt, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo json_encode(['success' => true, 'data' => $dataIA, 'historia' => $datosGrafica]);
    } else {
        echo json_encode(['error' => 'Error JSON IA']);
    }
} else {
    // Modo Detective
    $errorJson = json_decode($response, true);
    $msg = isset($errorJson['error']['message']) ? $errorJson['error']['message'] : "Error desconocido";
    echo json_encode(['error' => "Fallo IA ($httpCode): $msg"]);
}
?>