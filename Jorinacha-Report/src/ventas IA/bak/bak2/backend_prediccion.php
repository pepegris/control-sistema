<?php
// backend_prediccion.php
// AGREGA ESTAS DOS LINEAS PRIMERO:
error_reporting(0); 
ini_set('display_errors', 0);

// ... aquí sigue el resto de tu código (header, require, etc)...
header('Content-Type: application/json');
set_time_limit(300);


require_once "../../services/empresas.php";
require_once "../../services/db_connection.php";

$input = json_decode(file_get_contents('php://input'), true);

// ⚠️ PEGA TU API KEY AQUÍ
$apiKey = "AIzaSyALbM4NjO_9vNTIJ48A2bye92ETXZOozEM"; 

$modelosDisponibles = ["gemini-1.5-flash", "gemini-flash-latest", "gemini-2.5-flash", ];

// DATOS
$modoReporte = $input['modo'] ?? 'prediccion';
$producto   = $input['producto'] ?? '';
$linea      = $input['linea'] ?? '';
$sublinea   = $input['sublinea'] ?? '';
$meses      = intval($input['meses'] ?? 12);
if (!in_array($meses, [3, 6, 9, 12])) { $meses = 12; }

// FECHAS
$hoy = date("d-m-Y");
$diasRestantesMes = date("t") - date("j");
$nombreMesActual = date("F");
$nombreMesProximo = date("F Y", strtotime("+1 month"));

// FILTROS
$descFiltro = "";
$filtroVenta = ""; $filtroDevol = ""; $paramsSQL = [];
$esGlobal = false;

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
    // MODO GLOBAL (Todo en blanco)
    $descFiltro = "ANÁLISIS GLOBAL DE TODA LA EMPRESA (Todas las Líneas)";
    $esGlobal = true;
    // No agregamos WHEREs adicionales
}

// --- ESTRUCTURAS DE DATOS ---
$datosCompletos = []; // Para el gráfico (Modo Predicción)
$analisisDetallado = []; // Para la tabla (Modo Compras)
$datosGrafica = [];
$ventaTotalEmpresa = 0;

// Estructura temporal para agrupación inteligente en modo compras global
// [Linea][Sublinea][Articulo] => cantidad
$agrupacionCompras = []; 

foreach ($lista_replicas as $nombreSede => $datos) {
    $conn = ConectarSQLServer_local_vpn($datos['db'], $datos['ip']);
    if (!$conn) $conn = ConectarSQLServer_local_vpn($datos['db_local'], '172.16.1.39');
    if (!$conn) continue;

    // SQL ADAPTATIVO
    if ($modoReporte == 'prediccion') {
        // SQL 1: Agrupado por FECHA (Para Gráficas y Números Globales o Específicos)
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
        // SQL 2: MODO COMPRAS
        // Si es global, necesitamos saber Linea y Sublinea para agrupar
        // Si es filtro especifico, seguimos como antes
        
        if ($esGlobal) {
            // Traemos mas datos para agrupar luego en PHP
            $sql = "
                SELECT TOP 300 a.co_lin, a.co_sub, a.art_des as descripcion, SUM(rf.total_art) as cantidad
                FROM factura f INNER JOIN reng_fac rf ON f.fact_num = rf.fact_num INNER JOIN art a ON rf.co_art = a.co_art
                WHERE f.anulada = 0 AND f.fec_emis >= DATEADD(month, -$meses, GETDATE())
                GROUP BY a.co_lin, a.co_sub, a.art_des
                ORDER BY SUM(rf.total_art) DESC
            ";
        } else {
            $sql = "
                SELECT TOP 60 a.art_des as descripcion, SUM(rf.total_art) as cantidad
                FROM factura f INNER JOIN reng_fac rf ON f.fact_num = rf.fact_num INNER JOIN art a ON rf.co_art = a.co_art
                WHERE f.anulada = 0 AND f.fec_emis >= DATEADD(month, -$meses, GETDATE()) $filtroVenta
                GROUP BY a.art_des
                ORDER BY SUM(rf.total_art) DESC
            ";
        }
    }

    $stmt = sqlsrv_query($conn, $sql, $paramsSQL);
    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            
            if ($modoReporte == 'prediccion') {
                // Lógica Predicción (Igual que antes pero ahora suma todo si es global)
                $key = $row['anio'] . "-" . str_pad($row['mes'], 2, "0", STR_PAD_LEFT);
                if (!isset($datosCompletos[$key])) $datosCompletos[$key] = ['ventas' => 0, 'devoluciones' => 0];
                
                $cant = floatval($row['cantidad']);
                if ($row['tipo'] == 'V') {
                    $datosCompletos[$key]['ventas'] += $cant;
                } else {
                    $datosCompletos[$key]['devoluciones'] += $cant;
                }

            } else {
                // Lógica Compras
                $cant = floatval($row['cantidad']);
                $desc = trim($row['descripcion']);

                if ($esGlobal) {
                    // Agrupamos: [Linea][SubLinea] -> Lista de Articulos
                    $lin = trim($row['co_lin']);
                    $sub = trim($row['co_sub']);
                    
                    if (!isset($agrupacionCompras[$lin])) $agrupacionCompras[$lin] = [];
                    if (!isset($agrupacionCompras[$lin][$sub])) $agrupacionCompras[$lin][$sub] = [];
                    
                    // Sumamos si el articulo ya existe en esa linea/sublinea (porque viene de otra tienda)
                    $encontrado = false;
                    foreach ($agrupacionCompras[$lin][$sub] as &$item) {
                        if ($item['art'] === $desc) {
                            $item['qty'] += $cant;
                            $encontrado = true;
                            break;
                        }
                    }
                    if (!$encontrado) {
                        $agrupacionCompras[$lin][$sub][] = ['art' => $desc, 'qty' => $cant];
                    }

                } else {
                    // Comportamiento normal (un solo listado)
                    if (!isset($analisisDetallado[$desc])) $analisisDetallado[$desc] = 0;
                    $analisisDetallado[$desc] += $cant;
                }
            }
        }
    }
    sqlsrv_close($conn);
}

// --- PREPARACIÓN DE PROMPTS ---

if ($modoReporte == 'prediccion') {
    if (empty($datosCompletos)) { echo json_encode(['error' => "Sin datos."]); exit; }
    ksort($datosCompletos);
    
    foreach ($datosCompletos as $mes => $vals) {
        $neto = $vals['ventas'] - $vals['devoluciones'];
        $datosGrafica[$mes] = $neto > 0 ? $neto : 0;
    }

    $prompt = "
    Eres un Gerente Experto en Supply Chain. Analiza: $descFiltro.
    Fecha hoy: $hoy. Quedan $diasRestantesMes días para terminar $nombreMesActual.
    DATOS HISTÓRICOS (Mes: {Ventas, Devoluciones}): " . json_encode($datosCompletos) . "
    OBJETIVOS:
    1. Calcula cierre de $nombreMesActual y proyección $nombreMesProximo.
    2. Evalúa la tendencia general y la calidad (devoluciones).
    RESPONDE SOLO JSON:
    {
        \"prediccion_cierre\": numero_entero,
        \"prediccion_enero\": numero_entero,
        \"tendencia\": \"Texto breve\",
        \"alerta_calidad\": \"Texto breve\",
        \"accion\": \"Estrategia recomendada\"
    }";

} else {
    // --- MODO COMPRAS (LOGICA TOP 15) ---
    $datosAEnviar = [];

    if ($esGlobal) {
        // PROCESAMIENTO PHP: Extraer Top 15 de cada Linea/Sublinea
        foreach ($agrupacionCompras as $nombreLinea => $subs) {
            foreach ($subs as $nombreSub => $articulos) {
                // Ordenar por cantidad descendente
                usort($articulos, function($a, $b) { return $b['qty'] <=> $a['qty']; });
                // Tomar Top 15
                $top15 = array_slice($articulos, 0, 15);
                
                // Formatear para IA
                $listaTop = [];
                foreach($top15 as $t) { $listaTop[] = $t['art'] . "(" . $t['qty'] . ")"; }
                
                $datosAEnviar[] = "LINEA: $nombreLinea - SUB: $nombreSub -> TOP: " . implode(", ", $listaTop);
            }
        }
        // Limitamos para no explotar el prompt
        $datosAEnviar = array_slice($datosAEnviar, 0, 30); // Analizamos las 30 líneas/subs más relevantes
    } else {
        arsort($analisisDetallado);
        $resumenEnvio = array_slice($analisisDetallado, 0, 40); 
        $datosAEnviar = $resumenEnvio;
    }

    if (empty($datosAEnviar)) { echo json_encode(['error' => "Sin datos de ventas."]); exit; }

    $prompt = "
    Eres un Comprador Experto. Planifica compras para $nombreMesActual y $nombreMesProximo.
    
    DATOS (Top productos por Linea/Sublinea o General): " . json_encode($datosAEnviar) . "

    TU TAREA:
    1. Detecta patrones de TALLAS y COLORES en los nombres.
    2. Genera recomendaciones de compra para las lineas más importantes.
    3. Si recibiste datos de varias líneas, elige las más críticas para recomendar.

    RESPONDE SOLO JSON:
    {
        \"analisis_tallas_colores\": \"Resumen breve de atributos ganadores\",
        \"tiendas_criticas\": \"Analisis general basado en volumen\",
        \"recomendaciones\": [
            {
                \"articulo\": \"Nombre Articulo o Categoria Linea/Sublinea\",
                \"cantidad_sugerida\": numero_entero,
                \"prioridad\": \"Alta/Media\",
                \"distribucion_sugerida\": \"Texto breve\",
                \"razon\": \"Por qué comprar\"
            }
        ]
    }
    NOTA: Máximo 8 recomendaciones.";
}

// --- CONEXIÓN IA ---
$respuestaExitosa = null;
$ultimoError = "";
$ultimoHttpCode = 0;

// Usamos solo el modelo más estable para evitar errores de nombres
$modelosDisponibles = ["gemini-1.5-flash"]; 

foreach ($modelosDisponibles as $modeloActual) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/$modeloActual:generateContent?key=" . $apiKey;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    // Agregamos JSON_UNESCAPED_UNICODE para evitar errores con tildes/ñ en el JSON
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["contents" => [["parts" => [["text" => $prompt]]]]], JSON_UNESCAPED_UNICODE));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorCurl = curl_error($ch);
    curl_close($ch);

    $ultimoHttpCode = $httpCode;

    if ($httpCode == 200 && $response) {
        $jsonResponse = json_decode($response, true);
        if (isset($jsonResponse['candidates'][0]['content']['parts'][0]['text'])) {
            $respuestaExitosa = $jsonResponse['candidates'][0]['content']['parts'][0]['text'];
            break;
        }
    } else {
        // Guardamos el error para mostrartelo si falla
        $ultimoError = "HTTP $httpCode. Resp: " . $response . ". CurlErr: " . $errorCurl;
    }
}

// --- OUTPUT FINAL ---
if ($respuestaExitosa) {
    // Limpieza de Markdown (```json)
    $txt = preg_replace('/^```json\s*|\s*```$/', '', $respuestaExitosa);
    $dataIA = json_decode($txt, true);
    
    if (json_last_error() === JSON_ERROR_NONE) {
        $response = ['success' => true, 'modo' => $modoReporte, 'data' => $dataIA];
        
        if($modoReporte == 'prediccion') {
            $keyMesActual = date("Y") . "-" . date("m");
            $acumuladoMesActual = isset($datosCompletos[$keyMesActual]) ? ($datosCompletos[$keyMesActual]['ventas'] - $datosCompletos[$keyMesActual]['devoluciones']) : 0;
            if($acumuladoMesActual < 0) $acumuladoMesActual = 0;

            $response['historia'] = $datosGrafica ?? [];
            $response['meta'] = [
                'mes_actual' => $nombreMesActual,
                'mes_proximo' => $nombreMesProximo,
                'venta_acumulada_real' => $acumuladoMesActual
            ];
        }
        echo json_encode($response);
    } else {
        // Error al parsear el JSON que devolvió la IA
        echo json_encode(['error' => 'La IA respondió pero no en formato JSON válido. Texto recibido: ' . substr($txt, 0, 100) . '...']);
    }
} else {
    // AQUÍ ESTÁ EL CAMBIO IMPORTANTE: TE MUESTRA EL ERROR REAL
    echo json_encode(['error' => "Fallo Conexión IA: " . $ultimoError]);
}
?>
// --- OUTPUT FINAL ---
if ($respuestaExitosa) {
    $txt = str_replace(['```json', '```'], '', $respuestaExitosa);
    $dataIA = json_decode($txt, true);
    
    if (json_last_error() === JSON_ERROR_NONE) {
        $response = ['success' => true, 'modo' => $modoReporte, 'data' => $dataIA];
        
        if($modoReporte == 'prediccion') {
            $keyMesActual = date("Y") . "-" . date("m");
            $acumuladoMesActual = isset($datosCompletos[$keyMesActual]) ? ($datosCompletos[$keyMesActual]['ventas'] - $datosCompletos[$keyMesActual]['devoluciones']) : 0;
            if($acumuladoMesActual < 0) $acumuladoMesActual = 0;

            $response['historia'] = $datosGrafica ?? [];
            $response['meta'] = [
                'mes_actual' => $nombreMesActual,
                'mes_proximo' => $nombreMesProximo,
                'venta_acumulada_real' => $acumuladoMesActual
            ];
        }
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Error JSON IA']);
    }
} else {
    echo json_encode(['error' => "Fallo IA. Intenta de nuevo."]);
}
?>