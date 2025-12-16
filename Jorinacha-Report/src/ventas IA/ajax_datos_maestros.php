<?php
// ajax_datos_maestros.php
header('Content-Type: application/json');
require_once "../../services/db_connection.php";

// Conectamos a la BD Local (Central)
$conn = ConectarSQLServer_local_vpn('PREVIA_A', '172.16.1.39'); 
if (!$conn) { echo json_encode([]); exit; }

$accion = $_GET['accion'] ?? '';
$data = [];

// 1. OBTENER LÍNEAS
if ($accion == 'lineas') {
    // Usamos RTRIM para limpiar los espacios al enviar al frontend
    $sql = "SELECT RTRIM(co_lin) as co_lin, lin_des FROM lin_art ORDER BY lin_des";
    $stmt = sqlsrv_query($conn, $sql);
    
    if ($stmt) {
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            // codificamos utf8_encode por si hay acentos en Profit
            $nombre = mb_convert_encoding($row['lin_des'], "UTF-8", "ISO-8859-1"); 
            $data[] = [
                'codigo' => $row['co_lin'], // Ya viene sin espacios por el RTRIM en SQL
                'nombre' => trim($nombre)
            ];
        }
    }
}

// 2. OBTENER SUBLÍNEAS (Aquí estaba el problema probable)
if ($accion == 'sublineas') {
    $linea = $_GET['linea'] ?? '';
    
    // IMPORTANTE: Usamos RTRIM(co_lin) = ? para que coincida aunque tenga espacios en la BD
    $sql = "SELECT RTRIM(co_subl) as co_sub, subl_des 
            FROM sub_lin 
            WHERE RTRIM(co_lin) = ? 
            ORDER BY subl_des";
            
    $params = array($linea);
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt) {
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $nombre = mb_convert_encoding($row['subl_des'], "UTF-8", "ISO-8859-1");
            $data[] = [
                'codigo' => $row['co_sub'],
                'nombre' => trim($nombre)
            ];
        }
    }
}

// 3. BUSCAR ARTÍCULOS
if ($accion == 'buscar_art') {
    $q = $_GET['q'] ?? '';
    $sql = "SELECT TOP 10 RTRIM(co_art) as co_art, art_des FROM art WHERE art_des LIKE ? OR co_art LIKE ?";
    $params = array("%$q%", "%$q%");
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt) {
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
             $desc = mb_convert_encoding($row['art_des'], "UTF-8", "ISO-8859-1");
            $data[] = ['codigo' => $row['co_art'], 'descripcion' => trim($desc)];
        }
    }
}

sqlsrv_close($conn);
echo json_encode($data);
?>