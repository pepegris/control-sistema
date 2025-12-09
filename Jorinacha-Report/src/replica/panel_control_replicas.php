<?php
// Configuración para que no se corte si tarda mucho
ini_set('max_execution_time', 300); 

// Credenciales ADMIN que me diste
$usuario_admin = "mezcla";
$clave_admin   = "Zeus33$";

// Array extraído de tu foto (Servidor Remoto -> Base de Datos)
$lista_replicas = [
    'ACARIGUA'     => ['servidor' => 'SRVPREV',              'db' => 'ACARIGUA'],
    'APURE'        => ['servidor' => 'SRVPREV',              'db' => 'APURA'],
    'CAGUA'        => ['servidor' => 'KAGU-PREV\KAGUPREV',   'db' => 'CAGUA'],
    'CARACAS 1'    => ['servidor' => 'SRVPREV',              'db' => 'CARACAS1'],
    'CORO 1'       => ['servidor' => 'SRVPREV',              'db' => 'CORO1'],
    'CORO 2'       => ['servidor' => 'SRVPREV',              'db' => 'CORO2'],
    'CORO 3'       => ['servidor' => 'SRVPREV\CORINA2',      'db' => 'CORO3'],
    'GUIGUE'       => ['servidor' => 'SRVPREV\CATICA2',      'db' => 'GUIGE_A'],
    'HIGUEROTE'    => ['servidor' => 'SRVPREV',              'db' => 'HIGUE'],
    'MATURIN'      => ['servidor' => 'SRVPREV',              'db' => 'MATURIN'],
    'OJEDA'        => ['servidor' => 'SRVPREV\OJENA',        'db' => 'OJEDA'],
    'PUNTO FIJO 1' => ['servidor' => 'SRVPREV\PUFIJO',       'db' => 'PTOFIJO1'],
    'PUNTO FIJO 2' => ['servidor' => 'NACHARIPREV\NACHARI',  'db' => 'PTOFIJO2'],
    'PUERTO'       => ['servidor' => 'SRVPREV',              'db' => 'PUERTOX'],
    'VALENCIA'     => ['servidor' => 'SRVPREV\VALENA',       'db' => 'VALE_A21'],
    'VALLE PASCUA' => ['servidor' => 'SRVPREV\VALLE',        'db' => 'VAPASCUA']
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