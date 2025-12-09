<?php
// Aumentamos tiempo (puede tardar si son muchos artículos)
ini_set('max_execution_time', 600); 

require '../../includes/log.php';

// 1. INCLUIR TU CONEXIÓN CENTRALIZADA
require '../../services/db_connection.php'; 

// Carga de configuración de las 16 tiendas (IPs y Nombres DB)
$ruta_config = '../../services/adm/replica/config_replicas.php';
if (!file_exists($ruta_config)) $ruta_config = 'config_replicas.php';
include $ruta_config;

// Credenciales para conexión directa por VPN (Si son distintas a las locales)
// Si son iguales a las de db_connection.php, podríamos reusarlas, pero las definimos aquí por seguridad.
$usr_remoto = "mezcla";
$pwd_remoto = "Zeus33$";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesando Artículos</title>
    <link rel="stylesheet" href="assets/css/replica_panel.css">
    <link rel="stylesheet" href="assets/css/replica_procesar.css">
    <style>
        .st-warning { 
            background: rgba(255, 215, 0, 0.15); 
            color: var(--accent-yellow); 
            border: 1px solid var(--accent-yellow); 
        }
    </style>
</head>
<body>

<div class="main-container report-container">
    <div class="header-panel">
        <h1>Proceso de <b>Creación</b></h1>
    </div>

    <?php
    if (!isset($_POST['fecha_inicio'])) {
        die("<div class='warning-card'><h3>Error: Sin fecha</h3></div><a href='panel_crear_articulos.php' class='back-btn'>Volver</a>");
    }

    $fecha_raw = $_POST['fecha_inicio']; 
    // Formato YYYYMMDD para comparar con Profit (fe_us_in)
    $fecha_profit = date("Ymd", strtotime($fecha_raw)); 
    
    echo "<div class='log-card' style='border-color:var(--accent-green);'>";
    echo "<h3 style='color:var(--accent-green)'>🔍 Buscando en PREVIA_A data desde: $fecha_raw</h3>";
    
    // ---------------------------------------------------------
    // 1. OBTENER DATA ORIGEN (PREVIA_A - LOCAL)
    // ---------------------------------------------------------
    
    // USAMOS TU FUNCIÓN NUEVA: ConectarSQLServer
    $conn_local = ConectarSQLServer('PREVIA_A'); 
    
    if (!$conn_local) {
        die("❌ Error Crítico: No se pudo conectar a la base de datos local <b>PREVIA_A</b> en 172.16.1.39.");
    }

    // A) COLORES
    $sql_col = "SELECT LTRIM(RTRIM(co_col)) as co_col, LTRIM(RTRIM(des_col)) as des_col FROM colores WHERE fe_us_in >= '$fecha_profit'";
    $res_col = sqlsrv_query($conn_local, $sql_col);
    $data_colores = [];
    if($res_col) while($row = sqlsrv_fetch_array($res_col, SQLSRV_FETCH_ASSOC)) $data_colores[] = $row;

    // B) LÍNEAS
    $sql_lin = "SELECT LTRIM(RTRIM(co_lin)) as co_lin, LTRIM(RTRIM(lin_des)) as lin_des FROM lin_art WHERE fe_us_in >= '$fecha_profit'";
    $res_lin = sqlsrv_query($conn_local, $sql_lin);
    $data_lineas = [];
    if($res_lin) while($row = sqlsrv_fetch_array($res_lin, SQLSRV_FETCH_ASSOC)) $data_lineas[] = $row;

    // C) SUBLÍNEAS
    $sql_sub = "SELECT LTRIM(RTRIM(co_subl)) as co_subl, LTRIM(RTRIM(subl_des)) as subl_des, LTRIM(RTRIM(co_lin)) as co_lin FROM sub_lin WHERE fe_us_in >= '$fecha_profit'";
    $res_sub = sqlsrv_query($conn_local, $sql_sub);
    $data_sublineas = [];
    if($res_sub) while($row = sqlsrv_fetch_array($res_sub, SQLSRV_FETCH_ASSOC)) $data_sublineas[] = $row;

    // D) CATEGORÍAS
    $sql_cat = "SELECT LTRIM(RTRIM(co_cat)) as co_cat, LTRIM(RTRIM(cat_des)) as cat_des FROM cat_art WHERE fe_us_in >= '$fecha_profit'";
    $res_cat = sqlsrv_query($conn_local, $sql_cat);
    $data_cat = [];
    if($res_cat) while($row = sqlsrv_fetch_array($res_cat, SQLSRV_FETCH_ASSOC)) $data_cat[] = $row;

    // E) ARTÍCULOS
    $sql_art = "SELECT 
                    LTRIM(RTRIM(co_art)) as co_art, 
                    LTRIM(RTRIM(art_des)) as art_des, 
                    LTRIM(RTRIM(co_lin)) as co_lin, 
                    LTRIM(RTRIM(co_subl)) as co_subl, 
                    LTRIM(RTRIM(co_cat)) as co_cat, 
                    LTRIM(RTRIM(co_color)) as co_color, 
                    prec_vta4, 
                    prec_vta5 
                FROM art WHERE fe_us_in >= '$fecha_profit'";
    $res_art = sqlsrv_query($conn_local, $sql_art);
    $data_articulos = [];
    if($res_art) while($row = sqlsrv_fetch_array($res_art, SQLSRV_FETCH_ASSOC)) $data_articulos[] = $row;

    // Resumen visual
    echo "<ul style='color:#ccc; font-family:monospace;'>";
    echo "<li>Colores: " . count($data_colores) . " | Líneas: " . count($data_lineas) . " | SubLíneas: " . count($data_sublineas) . "</li>";
    echo "<li>Categorías: " . count($data_cat) . " | Artículos: <b>" . count($data_articulos) . "</b></li>";
    echo "</ul></div>";

    if (count($data_articulos) == 0 && count($data_lineas) == 0 && count($data_colores) == 0) {
        echo "<div class='warning-card'><h3>Nada nuevo</h3><p>No hay registros creados desde esa fecha.</p></div>";
        echo "<a href='panel_crear_articulos.php' class='btn-return'>Volver</a>";
        exit;
    }

    // ---------------------------------------------------------
    // 2. RECORRER TIENDAS
    // ---------------------------------------------------------

    foreach ($lista_replicas as $tienda => $config) {
        
        $log_tienda = "";
        $modo_offline = false; 
        
        // --- INTENTO 1: CONEXIÓN REMOTA (VPN DIRECTA) ---
        $connInfo = array("Database"=>$config['db'], "UID"=>$usr_remoto, "PWD"=>$pwd_remoto, "LoginTimeout"=>4);
        
        // Usamos @ para suprimir error visual
        $conn_destino = @sqlsrv_connect($config['ip'], $connInfo);

        // --- INTENTO 2: FALLBACK A LOCAL (Usando tu función db_connection.php) ---
        if (!$conn_destino) {
            $modo_offline = true;
            // ConectarSQLServer conecta a 172.16.1.39 automáticamente
            $conn_destino = ConectarSQLServer($config['db']);
        }

        // Si fallan ambos
        if (!$conn_destino) {
            echo_card($tienda, "FAIL", "❌ Error Crítico: No conecta ni por VPN ({$config['ip']}) ni Localmente ({$config['db']}).", true);
            continue;
        }

        // --- EJECUTAR INSERCIONES ---
        sqlsrv_begin_transaction($conn_destino);
        $errores_sql = "";

        try {
            // COLORES
            foreach ($data_colores as $c) {
                $sql = "IF NOT EXISTS (SELECT co_col FROM colores WHERE co_col = '{$c['co_col']}')
                        BEGIN INSERT INTO colores (co_col, des_col, co_us_in, co_sucu) VALUES ('{$c['co_col']}', '{$c['des_col']}', '003', 'PPAL') END";
                if(!sqlsrv_query($conn_destino, $sql)) throw new Exception("Error Color: {$c['co_col']}");
            }

            // CATEGORÍAS
            foreach ($data_cat as $c) {
                $sql = "IF NOT EXISTS (SELECT co_cat FROM cat_art WHERE co_cat = '{$c['co_cat']}')
                        BEGIN INSERT INTO cat_art (co_cat, cat_des, co_us_in, co_sucu, movil) VALUES ('{$c['co_cat']}', '{$c['cat_des']}', '003', 'PPAL', 0) END";
                if(!sqlsrv_query($conn_destino, $sql)) throw new Exception("Error Cat: {$c['co_cat']}");
            }

            // LÍNEAS
            foreach ($data_lineas as $l) {
                $sql = "IF NOT EXISTS (SELECT co_lin FROM lin_art WHERE co_lin = '{$l['co_lin']}')
                        BEGIN INSERT INTO lin_art (co_lin, lin_des, co_us_in, co_sucu) VALUES ('{$l['co_lin']}', '{$l['lin_des']}', '003', 'PPAL') END";
                if(!sqlsrv_query($conn_destino, $sql)) throw new Exception("Error Lin: {$l['co_lin']}");
            }

            // SUBLÍNEAS
            foreach ($data_sublineas as $s) {
                $sql = "IF NOT EXISTS (SELECT co_subl FROM sub_lin WHERE co_subl = '{$s['co_subl']}' AND co_lin = '{$s['co_lin']}')
                        BEGIN INSERT INTO sub_lin (co_subl, subl_des, co_lin, co_us_in, co_sucu, movil) VALUES ('{$s['co_subl']}', '{$s['subl_des']}', '{$s['co_lin']}', '003', 'PPAL', 0) END";
                if(!sqlsrv_query($conn_destino, $sql)) throw new Exception("Error SubL: {$s['co_subl']}");
            }

            // ARTÍCULOS
            $arts_insertados = 0;
            foreach ($data_articulos as $a) {
                $p4 = number_format((float)$a['prec_vta4'], 2, '.', '');
                $p5 = number_format((float)$a['prec_vta5'], 2, '.', '');
                $desc = str_replace("'", "''", $a['art_des']); 

                $sql = "IF NOT EXISTS (SELECT co_art FROM art WHERE co_art = '{$a['co_art']}')
                        BEGIN
                            INSERT INTO art(
                                tipo, co_art, art_des, co_lin, co_subl, co_cat, co_color, prec_vta4, prec_vta5, 
                                procedenci, co_prov, ubicacion, uni_venta, suni_venta, tipo_imp, co_sucu, tuni_venta, uni_emp
                            ) VALUES (
                                'V', '{$a['co_art']}', '$desc', '{$a['co_lin']}', '{$a['co_subl']}', '{$a['co_cat']}', '{$a['co_color']}', $p4, $p5, 
                                '001', '001', '', 'PAR', 'BULTO', 1, 'PPAL', 'PAR', 'BULTO'
                            )
                        END";
                
                if(!sqlsrv_query($conn_destino, $sql)) throw new Exception("Error Art: {$a['co_art']}");
                $arts_insertados++;
            }
            
            if($arts_insertados > 0) $log_tienda .= "✔ <b>$arts_insertados</b> Artículos procesados.";
            else $log_tienda .= "Datos verificados (Ya existían).";

            sqlsrv_commit($conn_destino);

            // Reportar Estado
            if ($modo_offline) {
                // Amarillo: Guardado en Local (172.16.1.39)
                echo_card($tienda, "WARNING", $log_tienda . "<br>⚠️ Sin VPN. <b>Guardado en SQL2K8 Local.</b> Replicará automáticamente.", false);
            } else {
                // Verde: Guardado Remoto directo
                echo_card($tienda, "OK", $log_tienda, false);
            }

        } catch (Exception $e) {
            sqlsrv_rollback($conn_destino);
            if (($errors = sqlsrv_errors()) != null) foreach ($errors as $error) $errores_sql .= $error['message'] . " ";
            echo_card($tienda, "FAIL", "Error: " . $e->getMessage() . "<br><small>$errores_sql</small>", true);
        }

        sqlsrv_close($conn_destino);
        flush(); ob_flush(); 
    }

    // --- FUNCIÓN PARA MOSTRAR TARJETAS ---
    function echo_card($tienda, $status, $log, $is_error) {
        if ($status == "OK") {
            $badge_class = "st-ok"; $badge_text = "ENVIADO A TIENDA"; $icon = "✅";
        } elseif ($status == "WARNING") {
            $badge_class = "st-warning"; $badge_text = "GUARDADO LOCAL"; $icon = "🟠";
        } else {
            $badge_class = "st-fail"; $badge_text = "ERROR"; $icon = "❌";
        }
        
        echo "<div class='log-card'>";
        echo "  <div class='log-header'>";
        echo "      <h3>$icon $tienda</h3>";
        echo "      <span class='status-badge $badge_class'>$badge_text</span>";
        echo "  </div>";
        echo "  <div class='console-output'>$log</div>";
        echo "</div>";
    }
    ?>
    
    <a href="panel_crear_articulos.php" class="btn-return">← Volver al Formulario</a>
</div>

</body>
</html>