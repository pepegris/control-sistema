<?php
// Configuración para que no se corte si tarda mucho
ini_set('max_execution_time', 300); 

// Credenciales ADMIN que me diste
$usuario_admin = "mezcla";
$clave_admin   = "Zeus33$";

// Array extraído de tu foto (Servidor Remoto -> Base de Datos)
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
    <style>
        body { background: #222; color: #fff; font-family: monospace; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #444; padding: 8px; }
        .ok { color: #0f0; font-weight: bold; }
        .fail { color: #f00; font-weight: bold; }
    </style>
</head>
<body>
    <h2>📡 Test de Conexión (Usuario: <?= $usuario_admin ?>)</h2>
    <table>
        <thead>
            <tr>
                <th>Tienda</th>
                <th>Servidor Objetivo</th>
                <th>Base de Datos</th>
                <th>Resultado</th>
                <th>Mensaje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista_replicas as $nombre => $datos): ?>
            <tr>
                <td><?= $nombre ?></td>
                <td><?= $datos['servidor'] ?></td>
                <td><?= $datos['db'] ?></td>
                
                <?php
                // Intentar conexión directa
                $connectionInfo = array(
                    "Database" => $datos['db'], 
                    "UID" => $usuario_admin, 
                    "PWD" => $clave_admin,
                    "LoginTimeout" => 5 // 5 segundos maximo de espera por tienda
                );
                
                $conn = sqlsrv_connect($datos['servidor'], $connectionInfo);
                
                if ($conn) {
                    // Contamos articulos para asegurar lectura
                    $sql = "SELECT COUNT(*) as c FROM art";
                    $stmt = sqlsrv_query($conn, $sql);
                    $qty = 0;
                    if ($stmt && $row = sqlsrv_fetch_array($stmt)) {
                        $qty = $row['c'];
                    }
                    echo "<td class='ok'>CONECTADO</td>";
                    echo "<td>Acceso Correcto. (Arts: $qty)</td>";
                    sqlsrv_close($conn);
                } else {
                    echo "<td class='fail'>FALLÓ</td>";
                    // Capturar error
                    $err = "";
                    if (($errors = sqlsrv_errors()) != null) {
                        foreach ($errors as $error) {
                            $err .= "SQLSTATE: ".$error['SQLSTATE']." - ".$error['message']." ";
                        }
                    }
                    echo "<td style='font-size:10px; color:#aaa;'>".substr($err, 0, 100)."...</td>";
                }
                ?>
            </tr>
            <?php 
                flush(); // Forzar a pintar la tabla fila por fila
            ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>