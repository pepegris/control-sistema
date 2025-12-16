<?php
// ajax_datos_maestros.php
header('Content-Type: application/json');
require_once "../../services/db_connection.php";

// Conectamos a la BD Local (Central) para sacar el catálogo rápido
$conn = ConectarSQLServer_local_vpn('ACARIGUA', '172.16.1.39'); // Usamos una DB de referencia
if (!$conn) { echo json_encode([]); exit; }

$accion = $_GET['accion'] ?? '';

$data = [];

if ($accion == 'lineas') {
    $sql = "SELECT co_lin, lin_des FROM lin_art ORDER BY lin_des";
    $stmt = sqlsrv_query($conn, $sql);
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = ['codigo' => trim($row['co_lin']), 'nombre' => trim($row['lin_des'])];
    }
}

if ($accion == 'sublineas') {
    $linea = $_GET['linea'] ?? '';
    $sql = "SELECT co_sub, sub_des FROM sub_lin WHERE co_lin = ? ORDER BY sub_des";
    $stmt = sqlsrv_query($conn, $sql, array($linea));
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = ['codigo' => trim($row['co_sub']), 'nombre' => trim($row['sub_des'])];
    }
}

if ($accion == 'buscar_art') {
    $q = $_GET['q'] ?? '';
    // Busca por coincidencia en descripción
    $sql = "SELECT TOP 10 co_art, art_des FROM art WHERE art_des LIKE ? OR co_art LIKE ?";
    $params = array("%$q%", "%$q%");
    $stmt = sqlsrv_query($conn, $sql, $params);
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = ['codigo' => trim($row['co_art']), 'descripcion' => trim($row['art_des'])];
    }
}

sqlsrv_close($conn);
echo json_encode($data);
?>