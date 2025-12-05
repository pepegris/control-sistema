<?php
// ACTIVAR REPORTE DE ERRORES (Solo para depurar, quita esto cuando esté listo)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Administrativa</title>
    <style>
        /* ESTILOS ANTERIORES (DISEÑO LIMPIO) */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px; /* Ancho para el formulario */
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-weight: bold;
        }

        input[type="date"],
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* Para que el padding no rompa el ancho */
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff; /* Azul administrativo */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
        
        /* Estilos para alertas o mensajes */
        .alert {
            padding: 10px;
            background-color: #ffeeba;
            color: #856404;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Consulta de Ventas</h2>
        
        <form action="detal.php" method="POST">
            <div class="form-group">
                <label for="fecha_ini">Fecha Inicio:</label>
                <input type="date" name="fecha_ini" id="fecha_ini" required>
            </div>

            <div class="form-group">
                <label for="fecha_fin">Fecha Fin:</label>
                <input type="date" name="fecha_fin" id="fecha_fin" required>
            </div>

            <div class="form-group">
                <button type="submit">Consultar Reporte</button>
            </div>
        </form>
    </div>

</body>
</html>