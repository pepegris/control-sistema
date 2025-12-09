<?php
// Configuración
ini_set('max_execution_time', 300); 

// Credenciales ADMIN
$usuario_admin = "mezcla";
$clave_admin   = "Zeus33$";

// Array MAESTRO: IP de VPN + Nombre de Instancia (si aplica)
// Extraído de tus capturas de pantalla
$lista_replicas = [
    'ACARIGUA'     => ['ip' => '26.35.57.24',           'db' => 'ACARIGUA'],
    'APURE'        => ['ip' => '26.77.57.145',          'db' => 'APURA'],
    
    // Cagua usa instancia \KAGUPREV
    'CAGUA'        => ['ip' => '26.179.47.19\KAGUPREV', 'db' => 'CAGUA'],
    
    'CARACAS 1'    => ['ip' => '26.163.49.171',         'db' => 'CARACAS1'],
    'CORO 1'       => ['ip' => '26.40.138.208',         'db' => 'CORO1'],
    'CORO 2'       => ['ip' => '26.10.12.209',          'db' => 'CORO2'],
    
    // Coro 3 usa instancia \CORINA2
    'CORO 3'       => ['ip' => '26.20.32.25\CORINA2',   'db' => 'CORO3'],
    
    // Guigue usa instancia \CATICA2
    'GUIGUE'       => ['ip' => '26.160.180.159\CATICA2','db' => 'GUIGE_A'],
    
    // "HGUE" en la VPN parece ser Higuerote
    'HIGUEROTE'    => ['ip' => '26.185.48.247',         'db' => 'HIGUE'],
    
    'MATURIN'      => ['ip' => '26.25.243.193',         'db' => 'MATURIN'],
    
    // Ojeda usa instancia \OJENA
    'OJEDA'        => ['ip' => '26.173.204.113\OJENA',  'db' => 'OJEDA'],
    
    // Pto Fijo 1 usa instancia \PUFIJO (VPN dice "PUFIJO")
    'PUNTO FIJO 1' => ['ip' => '26.248.191.230\PUFIJO', 'db' => 'PTOFIJO1'],
    
    // Pto Fijo 2 usa instancia \NACHARI (VPN dice "PTOFIJO2")
    'PUNTO FIJO 2' => ['ip' => '26.229.9.68\NACHARI',   'db' => 'PTOFIJO2'],
    
    // Puerto La Cruz (VPN dice "PUECRUZ")
    'PUERTO'       => ['ip' => '26.80.3.26',            'db' => 'PUERTOX'],
    
    // Valencia usa instancia \VALENA
    'VALENCIA'     => ['ip' => '26.42.16.172\VALENA',   'db' => 'VALE_A21'],
    
    // Valle de la Pascua usa instancia \VALLE
    'VALLE PASCUA' => ['ip' => '26.214.82.1\VALLE',     'db' => 'VAPASCUA']
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Test Conexión VPN IPs</title>
    <style>
        body { background: #121212; color: #e0e0e0; font-family: 'Segoe UI', sans-serif; padding: 20px; }
        h2 { border-bottom: 2px solid #00ff99; padding-bottom: 10px; display: inline-block; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #1e1e1e; }
        th, td { border: 1px solid #333; padding: 12px 15px; text-align: left; }
        th { background-color: #2c2c2c; color: #00ff99; }
        tr:hover { background-color: #252525; }
        .ok { color: #00ff99; font-weight: bold; background: rgba(0, 255, 153, 0.1); }
        .fail { color: #ff5555; font-weight: bold; background: rgba(255, 85, 85, 0.1); }
        .ip-tag { font-family: monospace; background: #333; padding: 2px 6px; border-radius: 4px; color: #fff; }
    </style>
</head>
<body>

    <h2>📡 Test de Conexión Directa por VPN</h2>
    <p>Intentando conectar usando usuario <code><?= $usuario_admin ?></code> directo a las IPs.</p>

    <table>
        <thead>
            <tr>
                <th>Tienda</th>
                <th>IP / Instancia Objetivo</th>
                <th>Base de Datos</th>
                <th>Estado</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista_replicas as $nombre => $datos): ?>
            <tr>
                <td><?= $nombre ?></td>
                <td><span class="ip-tag"><?= $datos['ip'] ?></span></td>
                <td><?= $datos['db'] ?></td>
                
                <?php
                // Configuración de conexión
                $connectionInfo = array(
                    "Database" => $datos['db'], 
                    "UID" => $usuario_admin, 
                    "PWD" => $clave_admin,
                    "LoginTimeout" => 4 // 4 segundos es suficiente para IPs directas
                );
                
                // Intento de conexión
                $conn = sqlsrv_connect($datos['ip'], $connectionInfo);
                
                if ($conn) {
                    // Prueba de fuego: Leer algo
                    $sql = "SELECT COUNT(*) as c FROM art";
                    $stmt = sqlsrv_query($conn, $sql);
                    
                    if ($stmt && $row = sqlsrv_fetch_array($stmt)) {
                         echo "<td class='ok'>✅ CONECTADO</td>";
                         echo "<td>Lectura OK. Arts: <b>" . number_format($row['c']) . "</b></td>";
                    } else {
                         echo "<td class='ok'>⚠️ CONECTA PERO...</td>";
                         echo "<td>No se pudo leer la tabla 'art'. Posible error de permisos.</td>";
                    }
                    sqlsrv_close($conn);
                } else {
                    echo "<td class='fail'>❌ SIN CONEXIÓN</td>";
                    
                    $err = "Desconocido";
                    if (($errors = sqlsrv_errors()) != null) {
                        // Tomamos solo el mensaje principal del error
                        $err = $errors[0]['message'];
                        
                        // Simplificamos errores comunes de red
                        if (strpos($err, 'TCP Provider') !== false) $err = "TimeOut / No llega a la IP";
                        if (strpos($err, 'Login failed') !== false) $err = "Clave/Usuario Incorrecto";
                    }
                    echo "<td style='font-size: 0.85em; color: #aaa;'>$err</td>";
                }
                ?>
            </tr>
            <?php 
                flush(); // Actualiza la pantalla paso a paso
            ?>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>