<?php
// backend_prediccion.php
header('Content-Type: application/json');
set_time_limit(300); 

require_once "../../services/empresas.php";
require_once "../../services/db_connection.php";

$input = json_decode(file_get_contents('php://input'), true);

// ⚠️ TU API KEY AQUÍ
$apiKey = "AIzaSyCcDEFD1k_unQiRUl9YaDcf9V-G1KE7PSc"; 

// 🛡️ LISTA DE MODELOS (Estrategia Anti-Caídas)
$modelosDisponibles = ["gemini-2.5-flash","gemini-1.5-flash", "gemini-flash-latest", ];

// 1. DATOS RECIBIDOS DEL FRONTEND
$producto  = $input['producto'] ?? '';
$linea     = $input['linea'] ?? '';
$sublinea  = $input['sublinea'] ?? '';
$meses     = intval($input['meses'] ?? 12); // Por defecto 12 meses

// Validación de seguridad para los meses
if (!in_array($meses, [3, 6, 9, 12])) { $meses = 12; }

// 2. CONSTRUCCIÓN DE FILTROS DINÁMICOS (Para Ventas y Devoluciones)
$modoAnalisis = "";
$filtroVenta = ""; 
$filtroDevol = "";
$paramsSQL = [];

if (!empty($producto)) {
    $modoAnalisis = "PRODUCTO: $producto";
    $filtroVenta = " AND rf.co_art = ? ";
    $filtroDevol = " AND rd.co_art = ? "; // rd = renglón devolución
    // Se duplica el parámetro porque la consulta SQL tiene 2 partes (UNION)
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
} else {
    echo json_encode(['error' => 'Falta selección de producto o línea.']);
    exit;
}

// 3. EXTRACCIÓN DE DATOS (17 TIENDAS)
$datosCompletos = []; // Almacena Ventas y Devoluciones por separado
$datosGrafica = [];   // Almacena Ventas Netas para el Chart.js
$tiendasConsultadas = 0;

foreach ($lista_replicas as $nombreSede => $datos) {
    $conn = ConectarSQLServer_local_vpn($datos['db'], $datos['ip']);
    if (!$conn) $conn = ConectarSQLServer_local_vpn($datos['db_local'], '172.16.1.39');
    if (!$conn) continue;

    $tiendasConsultadas++;

    // SQL PODEROSO: Une Facturas (V) y Devoluciones (D)
    $sql = "
        /* VENTAS */
        SELECT 'V' as tipo, YEAR(f.fec_emis) as anio, MONTH(f.fec_emis) as mes, SUM(rf.total_art) as cantidad
        FROM factura f
        INNER JOIN reng_fac rf ON f.fact_num = rf.fact_num
        INNER JOIN art a ON rf.co_art = a.co_art
        WHERE f.anulada = 0 AND f.fec_emis >= DATEADD(month, -$meses, GETDATE()) $filtroVenta
        GROUP BY YEAR(f.fec_emis), MONTH(f.fec_emis)

        UNION ALL

        /* DEVOLUCIONES */
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
            
            if (!isset($datosCompletos[$key])) {
                $datosCompletos[$key] = ['ventas' => 0, 'devoluciones' => 0];
            }

            if ($row['tipo'] == 'V') {
                $datosCompletos[$key]['ventas'] += $row['cantidad'];
            } else {
                $datosCompletos[$key]['devoluciones'] += $row['cantidad'];
            }
        }
    }
    sqlsrv_close($conn);
}

if (empty($datosCompletos)) {
    echo json_encode(['error' => "No hay movimientos en los últimos $meses meses."]);
    exit;
}
ksort($datosCompletos);

// Preparar datos limpios para la Gráfica (Ventas Netas)
foreach ($datosCompletos as $mes => $vals) {
    // Calculamos: Lo que se vendió MENOS lo que devolvieron
    $neto = $vals['ventas'] - $vals['devoluciones'];
    $datosGrafica[$mes] = $neto > 0 ? $neto : 0; // Evitamos negativos feos en la gráfica
}

// 4. PREPARACIÓN DEL PROMPT PARA IA (CALIDAD Y DEMANDA)
$prompt = "
Actúa como Gerente de Producto y Calidad.
Analiza estos datos (Ventas vs Devoluciones) de $modoAnalisis en los últimos $meses meses.

DATOS (Mes: {Ventas, Devoluciones}): " . json_encode($datosCompletos) . "

TU MISIÓN:
1. Calcula la Tasa de Devolución Global.
2. Si la tasa es > 5%, debes poner una ALERTA sobre calidad.
3. Pronostica la venta neta del próximo mes (considerando las devoluciones).

RESPONDE SOLO EN ESTE JSON VÁLIDO:
{
    \"prediccion\": numero_entero_neto, 
    \"tendencia\": \"Resumen ejecutivo (Max 15 palabras)\", 
    \"alerta_calidad\": \"Texto breve. Si hay devoluciones altas, dilo aquí. Si no, di 'Calidad Estable'.\",
    \"accion\": \"Recomendación estratégica de inventario (Max 15 palabras)\"
}";

// 5. CONEXIÓN A GEMINI (FAILOVER LOOP)
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
            break; // ¡Éxito! Salimos del bucle
        }
    }
}

// 6. RESPUESTA FINAL AL FRONTEND
if ($respuestaExitosa) {
    $txt = str_replace(['```json', '```'], '', $respuestaExitosa);
    $dataIA = json_decode($txt, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        echo json_encode([
            'success' => true, 
            'data' => $dataIA,
            'historia' => $datosGrafica // Enviamos ventas netas para que Chart.js las pinte
        ]);
    } else {
        echo json_encode(['error' => 'La IA respondió pero el formato JSON falló.']);
    }
} else {
    echo json_encode(['error' => "Servicio de IA sobrecargado. Intente en 1 minuto."]);
}
?>