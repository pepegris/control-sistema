<?php
require '../../services/db_connection.php'; 

$resultados = [];
$busqueda = "";

if (isset($_GET['q'])) {
    $busqueda = $_GET['q'];
    // Conexión a la BD
    $conn = ConectarSQLServer('PREVIA_A'); 
    
    if ($conn) {
        // Buscamos coincidencia en Código O Descripción
        $sql = "SELECT TOP 20 co_art, art_des, prec_vta5 
                FROM art 
                WHERE (co_art LIKE ? OR art_des LIKE ?) AND anulado = 0";
        $params = array("%$busqueda%", "%$busqueda%");
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $resultados[] = $row;
            }
        }
        sqlsrv_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Imprimir Etiquetas</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    
    <style>
        /* --- ESTILOS DE PANTALLA (UI) --- */
        body { font-family: 'Segoe UI', sans-serif; background-color: #222; color: #eee; padding: 20px; }
        .container { max-width: 950px; margin: 0 auto; display: flex; gap: 20px; }
        .panel { background: #333; padding: 20px; border-radius: 8px; flex: 1; border: 1px solid #444; }
        h2 { color: #00ff99; margin-top: 0; font-size: 1.2rem;}
        
        input[type="text"], input[type="number"] { padding: 10px; border-radius: 4px; border: 1px solid #555; background: #444; color: white; width: 100%; box-sizing: border-box; }
        button { cursor: pointer; padding: 10px; border: none; border-radius: 4px; font-weight: bold; }
        .btn-search { background: #00ff99; color: #000; width: 100%; margin-top: 10px; }
        .btn-add { background: #0d6efd; color: white; margin-top: 5px; width: 100%; }
        .btn-print { background: #ffd700; color: #000; width: 100%; font-size: 1.2em; margin-top: 20px; }
        .btn-delete { background: #ff4444; color: white; padding: 5px 10px; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border-bottom: 1px solid #555; padding: 8px; text-align: left; font-size: 0.9em; }
        tr:hover { background: #444; }

        /* SELECTOR DE MEDIDA */
        .size-selector { background: #222; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #555; }
        .radio-group { display: flex; gap: 15px; align-items: center; }
        .radio-group label { cursor: pointer; display: flex; align-items: center; gap: 5px; color: #fff; }
        input[type="radio"] { accent-color: #ffd700; width: 18px; height: 18px; }

        /* --- ESTILOS DE IMPRESIÓN --- */
        #areaImpresion { display: none; } /* Oculto en pantalla normal */

        @media print {
            /* Ocultamos TODO lo que no sea la etiqueta de forma agresiva */
            .container, h1, h2, form, input, button, .panel, .size-selector { 
                display: none !important; 
            }
            
            body { 
                margin: 0; 
                padding: 0; 
                background: white; 
            }

            /* Mostramos el área de impresión */
            #areaImpresion { 
                display: block !important; 
                position: absolute; 
                top: 0; 
                left: 0; 
                width: 100%;
                margin: 0;
            }

            .etiqueta {
                width: 2.25in;
                /* La altura (height) se inyecta por JS */
                page-break-after: always; /* Corte de página */
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
                overflow: hidden;
                margin: 0;
                padding: 0;
            }

            .eti-desc { font-weight: bold; text-transform: uppercase; font-family: Arial, sans-serif; color: black; line-height: 1; text-align: center; white-space: nowrap; overflow: hidden; width: 95%; }
            .eti-code { width: 95% !important; }
            .eti-precio { font-weight: bold; font-family: Arial, sans-serif; color: black; margin-top: -2px; }
        }
    </style>
    
    <style id="dynamicPageSize"></style>

</head>
<body>

<div class="container">
    
    <div class="panel">
        <h2>1. Buscar Artículo</h2>
        <form method="GET">
            <input type="text" name="q" placeholder="Código o descripción..." value="<?= htmlspecialchars($busqueda) ?>" autofocus>
            <button type="submit" class="btn-search">🔍 Buscar</button>
        </form>

        <?php if (!empty($resultados)): ?>
            <div style="max-height: 400px; overflow-y: auto;">
                <table>
                    <thead><tr><th>Artículo</th><th>+</th></tr></thead>
                    <tbody>
                        <?php foreach ($resultados as $art): ?>
                            <tr>
                                <td>
                                    <b style="color:#00ff99"><?= $art['co_art'] ?></b><br>
                                    <small><?= substr($art['art_des'], 0, 28) ?></small>
                                </td>
                                <td><button class="btn-add" onclick='agregarCola(<?= json_encode($art) ?>)'>➕</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="panel">
        <h2>2. Configuración</h2>
        
        <div class="size-selector">
            <div style="margin-bottom:5px; font-weight:bold; color:#aaa; font-size:0.9em;">SELECCIONA TAMAÑO:</div>
            <div class="radio-group">
                <label>
                    <input type="radio" name="medida" value="grande" checked> 
                    2.25" x 1.25" <br><small style="color:#888">(Grande)</small>
                </label>
                <label>
                    <input type="radio" name="medida" value="peque"> 
                    2.25" x 0.75" <br><small style="color:#888">(Pequeña)</small>
                </label>
            </div>
        </div>

        <h2>3. Cola de Impresión</h2>
        <div style="max-height: 300px; overflow-y: auto; border:1px solid #444;">
            <table id="tablaCola">
                <thead><tr><th>Desc.</th><th width="50">Cant.</th><th>X</th></tr></thead>
                <tbody id="listaCola"></tbody>
            </table>
        </div>

        <button onclick="generarImpresion()" class="btn-print">🖨️ IMPRIMIR</button>
        <button onclick="limpiarCola()" style="width:100%; margin-top:10px; background:#555; color:#fff; padding:8px;">Limpiar Todo</button>
    </div>

</div>

<div id="areaImpresion"></div>

<script>
    let colaImpresion = [];

    function agregarCola(articulo) {
        // Evitar duplicados visuales, solo sumar cantidad
        let existe = colaImpresion.find(i => i.co_art === articulo.co_art);
        if (existe) {
            existe.cantidad++;
        } else {
            articulo.cantidad = 1;
            colaImpresion.push(articulo);
        }
        renderizarCola();
    }

    function renderizarCola() {
        let html = '';
        colaImpresion.forEach((item, index) => {
            html += `
                <tr>
                    <td><small style="color:#ffd700">${item.co_art}</small><br>${item.art_des.substring(0,12)}...</td>
                    <td><input type="number" value="${item.cantidad}" min="1" onchange="actualizarCant(${index}, this.value)" style="width:50px; text-align:center;"></td>
                    <td><button class="btn-delete" onclick="eliminar(${index})">X</button></td>
                </tr>
            `;
        });
        document.getElementById('listaCola').innerHTML = html;
    }

    function actualizarCant(index, valor) { colaImpresion[index].cantidad = parseInt(valor); }
    function eliminar(index) { colaImpresion.splice(index, 1); renderizarCola(); }
    function limpiarCola() { colaImpresion = []; renderizarCola(); }

    function generarImpresion() {
        if (colaImpresion.length === 0) { alert("Agrega artículos primero"); return; }

        const contenedor = document.getElementById('areaImpresion');
        contenedor.innerHTML = ''; 

        // 1. OBTENER CONFIGURACIÓN SEGÚN MEDIDA SELECCIONADA
        const medida = document.querySelector('input[name="medida"]:checked').value;
        
        let config = {};
        
        if (medida === 'grande') {
            // Configuración 2.25 x 1.25 (Pulgadas)
            config = {
                heightCss: '1.25in',
                barcodeHeight: 45, // Altura barras
                fontSize: 11,
                descSize: '10px',
                showPrice: true
            };
            // Inyectar CSS exacto para la impresora
            document.getElementById('dynamicPageSize').innerHTML = '@media print { @page { size: 2.25in 1.25in; margin: 0; } }';
        } else {
            // Configuración 2.25 x 0.75 (Pulgadas)
            config = {
                heightCss: '0.75in',
                barcodeHeight: 25, // Barras más bajitas
                fontSize: 10,
                descSize: '9px',
                showPrice: false // Ocultar precio por espacio
            };
            // Inyectar CSS exacto para la impresora
            document.getElementById('dynamicPageSize').innerHTML = '@media print { @page { size: 2.25in 0.75in; margin: 0; } }';
        }

        // 2. GENERAR HTML DE ETIQUETAS
        colaImpresion.forEach(item => {
            for (let i = 0; i < item.cantidad; i++) {
                let div = document.createElement('div');
                div.className = 'etiqueta';
                div.style.height = config.heightCss; // Altura dinámica aplicada al DIV

                // Descripción recortada
                let desc = item.art_des.substring(0, 25);
                
                // HTML interno
                let htmlContent = `<div class="eti-desc" style="font-size:${config.descSize}">${desc}</div>
                    <svg class="barcode"
                        jsbarcode-format="CODE128"
                        jsbarcode-value="${item.co_art}"
                        jsbarcode-textmargin="0"
                        jsbarcode-fontoptions="bold"
                        jsbarcode-height="${config.barcodeHeight}" 
                        jsbarcode-width="1.6"
                        jsbarcode-displayValue="true"
                        jsbarcode-fontSize="${config.fontSize}">
                    </svg>`;
                
                // Precio (solo para la etiqueta grande)
                if (config.showPrice) {
                    let precio = parseFloat(item.prec_vta5).toFixed(2);
                    // Descomentar si quieres mostrar precio:
                    // htmlContent += `<div class="eti-precio" style="font-size:12px">Ref: $${precio}</div>`;
                }

                div.innerHTML = htmlContent;
                contenedor.appendChild(div);
            }
        });

        // 3. RENDERIZAR CÓDIGOS DE BARRA (Librería)
        try {
            JsBarcode(".barcode").init();
        } catch (e) {
            console.error("Error generando código de barras:", e);
        }

        // 4. LANZAR IMPRESIÓN
        // setTimeout da tiempo al navegador para renderizar los SVG antes de abrir el diálogo
        setTimeout(() => { window.print(); }, 500);
    }
</script>

</body>
</html>