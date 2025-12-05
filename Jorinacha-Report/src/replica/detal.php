<?php
// ACTIVAR REPORTE DE ERRORES (Importante si sale en blanco)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si se enviaron datos
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    // Si entran directo sin post, redirigir al form
    header("Location: form.php");
    exit();
}

$fecha_ini = $_POST['fecha_ini'];
$fecha_fin = $_POST['fecha_fin'];

// AQUI TU CONEXION Y LOGICA PHP (Ejemplo simulado)
// $conexion = new mysqli(...);
// $query = "SELECT ... WHERE fecha BETWEEN '$fecha_ini' AND '$fecha_fin'";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados del Reporte</title>
    <style>
        /* REPETIMOS LOS ESTILOS PRINCIPALES */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1000px; /* Más ancho para la tabla */
            margin: 0 auto; /* Centrar horizontalmente */
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 15px;
            padding: 8px 15px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-back:hover { background-color: #5a6268; }

        /* ESTILOS DE LA TABLA */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff; /* Mismo azul del form */
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

    <div class="container">
        <a href="form.php" class="btn-back">← Volver a Consultar</a>
        
        <h2>Reporte de Ventas</h2>
        <p style="text-align:center; color:#666;">
            Desde: <strong><?php echo htmlspecialchars($fecha_ini); ?></strong> 
            Hasta: <strong><?php echo htmlspecialchars($fecha_fin); ?></strong>
        </p>

        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Documento</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2023-10-01</td>
                    <td>FAC-00123</td>
                    <td>Inversiones ABC</td>
                    <td>$ 1,200.00</td>
                    <td>Procesado</td>
                </tr>
                <tr>
                    <td>2023-10-02</td>
                    <td>FAC-00124</td>
                    <td>Distribuidora XYZ</td>
                    <td>$ 550.00</td>
                    <td>Pendiente</td>
                </tr>
                <?php
                // Aquí va tu código PHP que llena la tabla
                // while($row = sqlsrv_fetch_array($stmt)) { ... }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>