<?php
// backend_prediccion.php
header('Content-Type: application/json');
set_time_limit(300); 

require_once "../../services/empresas.php";
require_once "../../services/db_connection.php";

$input = json_decode(file_get_contents('php://input'), true);

// ⚠️ PEGA TU API KEY AQUÍ
$apiKey = "AIzaSyDhqVoxKLkGdIHRiymOHA2D1D3Q9M4XyWw"; 

$modelosDisponibles = ["gemini-1.5-flash", "gemini-flash-latest", "gemini-2.5-flash"];

// DATOS
$modoReporte = $input['modo'] ?? 'prediccion'; // 'prediccion' O 'compras'
$producto  = $input['producto'] ?? '';
$linea     = $input['linea'] ?? '';
$sublinea  = $input['sublinea'] ?? '';
$meses     = intval($input['meses'] ?? 12);
if (!in_array($meses, [3, 6, 9, 12])) { $meses = 12; }

// FECHAS
$hoy = date("d-m-Y");
$diasRestantesMes = date("t") - date("j");
$nombreMesActual = date("F");
$nombreMesProximo = date("F Y", strtotime("+1 month"));

// FILTROS
$descFiltro = "";
$filtroVenta = ""; $filtroDevol = ""; $paramsSQL = [];

if (!empty($producto)) {
    $descFiltro = "PRODUCTO ESPECÍFICO (Código: $producto)";
    $filtroVenta = " AND rf.co_art = ? ";
    $filtroDevol = " AND rd.co_art = ? ";
    $paramsSQL = [$producto, $producto]; 
} elseif (!empty($linea)) {
    $descFiltro = "LÍNEA: $linea";
    $filtroVenta = " AND a.co_lin = ? ";
    $filtroDevol = " AND a.co_lin = ? ";
    $paramsSQL = [$linea, $linea];
    if (!empty($sublinea)) {
        $descFiltro .= " / SUB: $sublinea";
        $filtroVenta .= " AND a.co_sub = ? ";
        $filtroDevol .= " AND a.co_sub = ? ";
        $paramsSQL[] = $sublinea;
        $paramsSQL[] = $sublinea;
    }
} else { 
    // Si quiere "Todo", limitamos para no explotar la IA en modo compras
    if($modoReporte == 'compras'){
        $descFiltro = "TOP VENTAS GENERALES";
    } else {
        echo json_encode(['error' => 'Para predicción numérica selecciona al menos una línea.']); exit;
    }
}

// --- ESTRUCTURAS DE DATOS ---
$datosCompletos = []; // Para el gráfico (Modo Predicción)
$analisisDetallado = []; // Para la tabla (Modo Compras)
$rankingTiendas = []; 
$tiendasConsultadas = 0;
$datosGrafica = [];

foreach ($lista_replicas as $nombreSede => $datos) {
    $conn = ConectarSQLServer_local_vpn($datos['db'], $datos['ip']);
    if (!$conn) $conn = ConectarSQLServer_local_vpn($datos['db_local'], '172.16.1.39');
    if (!$conn) continue;

    $tiendasConsultadas++;
    $ventaTotalSede = 0; 

    // SQL ADAPTATIVO: Cambia según lo que necesitemos
    if ($modoReporte == 'prediccion') {
        // SQL 1: Agrupado por FECHA (Para Gráficas y Números)
        $sql = "
            SELECT 'V' as tipo, YEAR(f.fec_emis) as anio, MONTH(f.fec_emis) as mes, SUM(rf.total_art) as cantidad
            FROM factura f INNER JOIN reng_fac rf ON f.fact_num = rf.fact_num INNER JOIN art a ON rf.co_art = a.co_art
            WHERE f.anulada = 0 AND f.fec_emis >= DATEADD(month, -$meses, GETDATE()) $filtroVenta
            GROUP BY YEAR(f.fec_emis), MONTH(f.fec_emis)
            UNION ALL
            SELECT 'D' as tipo, YEAR(d.fec_emis) as anio, MONTH(d.fec_emis) as mes, SUM(rd.total_art) as cantidad
            FROM dev_cli d INNER JOIN reng_dvc rd ON d.fact_num = rd.fact_num INNER JOIN art a ON rd.co_art = a.co_art
            WHERE d.anulada = 0 AND d.fec_emis >= DATEADD(month, -$meses, GETDATE()) $filtroDevol
            GROUP BY YEAR(d.fec_emis), MONTH(d.fec_emis)
        ";
    } else {
        // SQL 2: Agrupado por DESCRIPCIÓN (Para analizar Tallas, Colores y Productos)
        // Traemos el TOP 60 para que la IA tenga contexto suficiente sin saturarse
        $sql = "
            SELECT TOP 60 a.art_des as descripcion, SUM(rf.total_art) as cantidad
            FROM factura f INNER JOIN reng_fac rf ON f.fact_num = rf.fact_num INNER JOIN art a ON rf.co_art = a.co_art
            WHERE f.anulada = 0 AND f.fec_emis >= DATEADD(month, -$meses, GETDATE()) $filtroVenta
            GROUP BY a.art_des
            ORDER BY SUM(rf.total_art) DESC
        ";
    }

    $stmt = sqlsrv_query($conn, $sql, $paramsSQL);
    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            
            if ($modoReporte == 'prediccion') {
                // Lógica de Fechas
                $key = $row['anio'] . "-" . str_pad($row['mes'], 2, "0", STR_PAD_LEFT);
                if (!isset($datosCompletos[$key])) $datosCompletos[$key] = ['ventas' => 0, 'devoluciones' => 0];
                if ($row['tipo'] == 'V') {
                    $datosCompletos[$key]['ventas'] += $row['cantidad'];
                    $ventaTotalSede += $row['cantidad'];
                } else {
                    $datosCompletos[$key]['devoluciones'] += $row['cantidad'];
                    $ventaTotalSede -= $row['cantidad'];
                }
            } else {
                // Lógica de Descripciones (Compras)
                // Estructura: [Descripcion => [Tienda A => 5, Tienda B => 10]]
                $desc = trim($row['descripcion']);
                if (!isset($analisisDetallado[$desc])) $analisisDetallado[$desc] = [];
                if (!isset($analisisDetallado[$desc][$nombreSede])) $analisisDetallado[$desc][$nombreSede] = 0;
                $analisisDetallado[$desc][$nombreSede] += $row['cantidad'];
                $ventaTotalSede += $row['cantidad'];
            }
        }
    }
    $rankingTiendas[$nombreSede] = $ventaTotalSede;
    sqlsrv_close($conn);
}

// --- GENERACIÓN DE PROMPTS SEGÚN MODO ---

if ($modoReporte == 'prediccion') {
    // --- MODO 1: PREDICCIÓN NUMÉRICA (Tu lógica original) ---
    if (empty($datosCompletos)) { echo json_encode(['error' => "Sin datos."]); exit; }
    ksort($datosCompletos);
    arsort($rankingTiendas);
    $topTiendas = array_slice($rankingTiendas, 0, 5);
    
    foreach ($datosCompletos as $mes => $vals) {
        $neto = $vals['ventas'] - $vals['devoluciones'];
        $datosGrafica[$mes] = $neto > 0 ? $neto : 0;
    }

    $prompt = "
    Eres un Gerente Experto en Supply Chain. Analiza: $descFiltro.
    Fecha hoy: $hoy. Quedan $diasRestantesMes días para terminar $nombreMesActual.
    DATOS HISTÓRICOS (Mes: {Ventas, Devoluciones}): " . json_encode($datosCompletos) . "
    TOP TIENDAS: " . json_encode($topTiendas) . "
    OBJETIVOS:
    1. CIERRE DE MES ($nombreMesActual).
    2. MES SIGUIENTE ($nombreMesProximo).
    3. Evalúa calidad y tendencias geográficas.
    RESPONDE SOLO JSON:
    {
        \"prediccion_cierre\": numero_entero (Solo lo que falta para cerrar el mes),
        \"prediccion_enero\": numero_entero (Todo el mes siguiente),
        \"tendencia\": \"Texto breve sobre comportamiento y geografía\",
        \"alerta_calidad\": \"Texto breve sobre devoluciones\",
        \"accion\": \"Estrategia recomendada\"
    }";

} else {
    // --- MODO 2: ASISTENTE DE COMPRAS ---
    if (empty($analisisDetallado)) { echo json_encode(['error' => "Sin datos de ventas para analizar."]); exit; }
    
    // Limpiamos data para enviar solo lo relevante (Top 40 productos globales)
    // Esto es vital para no exceder el límite de caracteres de la IA
    $resumenEnvio = array_slice($analisisDetallado, 0, 40); 

    $prompt = "
    Eres un Comprador Experto en Retail/Moda para Venezuela.
    Estás planificando las compras para $nombreMesActual y $nombreMesProximo.
    CONTEXTO: Considera temporadas locales (Navidad, Carnaval, Clases) y la logística de 17 sucursales.
    
    DATOS DE VENTAS POR PRODUCTO Y TIENDA: " . json_encode($resumenEnvio) . "

    TU TAREA:
    1. Analiza los nombres de los productos para detectar patrones de TALLAS (S, M, L, XL, Números) y COLORES que más rotan.
    2. Identifica qué tiendas están vendiendo más (necesitan stock) y cuáles menos.
    3. Genera una lista de sugerencias de compra inteligente.

    RESPONDE SOLO EN ESTE JSON VÁLIDO:
    {
        \"analisis_tallas_colores\": \"Resumen breve de qué tallas/colores se mueven más (Ej: 'Dominio de Talla M y colores oscuros')\",
        \"tiendas_criticas\": \"Nombres de 2-3 tiendas que urgen inventario\",
        \"recomendaciones\": [
            {
                \"articulo\": \"Nombre del artículo o categoría\",
                \"cantidad_sugerida\": numero_entero_global,
                \"prioridad\": \"Alta/Media/Baja\",
                \"distribucion_sugerida\": \"Texto breve (Ej: 'Enviar 60% a Caracas, resto a Occidente')\",
                \"razon\": \"Por qué comprar esto (Ej: 'Alta rotación por temporada')\"
            }
        ]
    }
    NOTA: Genera máximo 6 recomendaciones clave.";
}

// --- CONEXIÓN IA ---
$respuestaExitosa = null;
$ultimoHttpCode = 0;

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
    $ultimoHttpCode = $httpCode;
    curl_close($ch);

    if ($httpCode == 200 && $response) {
        $jsonResponse = json_decode($response, true);
        if (isset($jsonResponse['candidates'][0]['content']['parts'][0]['text'])) {
            $respuestaExitosa = $jsonResponse['candidates'][0]['content']['parts'][0]['text'];
            break;
        }
    }
}

// --- OUTPUT FINAL ---
if ($respuestaExitosa) {
    $txt = str_replace(['```json', '```'], '', $respuestaExitosa);
    $dataIA = json_decode($txt, true);
    
    if (json_last_error() === JSON_ERROR_NONE) {
        $response = ['success' => true, 'modo' => $modoReporte, 'data' => $dataIA];
        
        // Si es predicción, agregamos extras (Gráfica y Metadatos)
        if($modoReporte == 'prediccion') {
            $keyMesActual = date("Y") . "-" . date("m");
            $acumuladoMesActual = isset($datosCompletos[$keyMesActual]) ? ($datosCompletos[$keyMesActual]['ventas'] - $datosCompletos[$keyMesActual]['devoluciones']) : 0;
            if($acumuladoMesActual < 0) $acumuladoMesActual = 0;

            $response['historia'] = $datosGrafica ?? [];
            $response['meta'] = [
                'mes_actual' => $nombreMesActual,
                'mes_proximo' => $nombreMesProximo,
                'dias_restantes' => $diasRestantesMes,
                'venta_acumulada_real' => $acumuladoMesActual
            ];
        }
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Error JSON IA']);
    }
} else {
    $errorJson = json_decode($response, true);
    $msg = isset($errorJson['error']['message']) ? $errorJson['error']['message'] : "Error desconocido";
    echo json_encode(['error' => "Fallo IA ($ultimoHttpCode): $msg"]);
}
?>