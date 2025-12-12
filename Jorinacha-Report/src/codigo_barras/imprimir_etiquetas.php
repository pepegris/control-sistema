<?php
require '../../services/db_connection.php'; 

$resultados = [];
$busqueda = "";

if (isset($_GET['q'])) {
    $busqueda = $_GET['q'];
    $conn = ConectarSQLServer('PREVIA_A'); 
    
    if ($conn) {
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
        /* ESTILOS DE UI (PANTALLA) */
        body { font-family: 'Segoe UI', sans-serif; background-color: #222; color: #eee; padding: 20px; }
        .container { max-width: 950px; margin: 0 auto; display: flex; gap: 20px; }
        .panel { background: #333; padding: 20px; border-radius: 8px; flex: 1; border: 1px solid #444; }
        h2 { color: #00ff99; margin-top: 0; font-size: 1.2rem;}
        input, button { padding: 10px; margin-top:5px; width: 100%; box-sizing: border-box; }
        .btn-print { background: #ffd700; color: #000; font-weight: bold; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border-bottom: 1px solid #555; padding: 8px; text-align: left; font-size: 0.9em; }
        .size-selector { background: #222; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #555; }
        .radio-group { display: flex; gap: 15px; align-items: center; }
        .radio-group label { cursor: pointer; display: flex; align-items: center; gap: 5px; color: #fff; }

        /* --- ESTILOS DE IMPRESIÓN (SOLIDEZ) --- */
        #areaImpresion { display: none; }

        @media print {
            .container, h1, h2, form, input, button, .panel, .size-selector { display: none !important; }
            body { margin: 0; padding: 0; background: white; }
            
            #areaImpresion { 
                display: block !important; 
                position: absolute; 
                top: 0; 
                left: 0; 
                width: 100%; 
            }

            .etiqueta {
                width: 2.25in; 
                page-break-after: always;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                margin: 0;
                padding: 0 4px; /* Pequeño padding lateral físico */
                box-sizing: border-box;
                overflow: hidden;
            }

            .eti-desc {
                font-family: Arial, sans-serif;
                font-weight: 900;
                color: black;
                text-align: center;
                line-height: 1;
                white-space: nowrap;
                overflow: hidden;
                width: 100%;
                margin-bottom: 2px;
            }

            /* TRUCO DE ESCALADO: 
               La imagen generada es GRANDE (barras anchas), 
               aquí la forzamos a caber en la etiqueta sin perder calidad */
            .barcode-img {
                width: 95%;       /* Ocupa casi todo el ancho disponible */
                height: auto;     /* Altura proporcional */
                max-height: 75%;  /* Que no se salga por abajo */
                display: block;
                
                /* Algoritmo de renderizado para evitar bordes borrosos */
                image-rendering: pixelated; 
            }
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
            <button type="submit" class="btn-search" style="background:#00ff99;">🔍 Buscar</button>
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
                                <td><button class="btn-add" style="background:#0d6efd; color:white;" onclick='agregarCola(<?= json_encode($art) ?>)'>➕</button></td>
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
                <label><input type="radio" name="medida" value="grande" checked> 2.25" x 1.25"</label>
                <label><input type="radio" name="medida" value="peque"> 2.25" x 0.75"</label>
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
        <button onclick="limpiarCola()" style="width:100%; margin-top:10px; background:#555; color:#fff;">Limpiar Todo</button>
    </div>
</div>

<div id="areaImpresion"></div>

<script>
    let colaImpresion = [];

    function agregarCola(articulo) {
        let existe = colaImpresion.find(i => i.co_art === articulo.co_art);
        if (existe) { existe.cantidad++; } else { articulo.cantidad = 1; colaImpresion.push(articulo); }
        renderizarCola();
    }

    function renderizarCola() {
        let html = '';
        colaImpresion.forEach((item, index) => {
            html += `<tr>
                <td><small style="color:#ffd700">${item.co_art}</small><br>${item.art_des.substring(0,12)}...</td>
                <td><input type="number" value="${item.cantidad}" min="1" onchange="actualizarCant(${index}, this.value)" style="width:50px; text-align:center;"></td>
                <td><button onclick="eliminar(${index})" style="background:#ff4444; color:white;">X</button></td>
            </tr>`;
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

        const medida = document.querySelector('input[name="medida"]:checked').value;
        let config = {};
        
        // --- ESTRATEGIA: GENERAR GRANDE, ENCOGER CON CSS ---
        if (medida === 'grande') {
            config = {
                heightCss: '1.25in',
                barcodeHeight: 80, // Generamos alto en memoria
                descSize: '11px',
                charLimit: 30
            };
            document.getElementById('dynamicPageSize').innerHTML = '@media print { @page { size: 2.25in 1.25in; margin: 0; } }';
        } else {
            // PEQUEÑA (2.25 x 0.75)
            config = {
                heightCss: '0.75in',
                barcodeHeight: 60, // Generamos alto en memoria
                descSize: '9px',   
                charLimit: 40      
            };
            document.getElementById('dynamicPageSize').innerHTML = '@media print { @page { size: 2.25in 0.75in; margin: 0; } }';
        }

        colaImpresion.forEach(item => {
            for (let i = 0; i < item.cantidad; i++) {
                
                let div = document.createElement('div');
                div.className = 'etiqueta';
                div.style.height = config.heightCss;

                // Descripción
                let desc = document.createElement('div');
                desc.className = 'eti-desc';
                desc.style.fontSize = config.descSize;
                desc.innerText = item.art_des.substring(0, config.charLimit);
                div.appendChild(desc);

                // --- GENERACIÓN DEL CÓDIGO (SOLIDEZ) ---
                let canvas = document.createElement('canvas');
                try {
                    JsBarcode(canvas, item.co_art, {
                        format: "CODE128",
                        
                        // CLAVE: Ancho 2.0 (Barras muy gruesas y sólidas)
                        // Luego el CSS (.barcode-img) se encarga de que quepa en el papel
                        width: 2, 
                        
                        height: config.barcodeHeight,
                        displayValue: false, // Sin números (estorban)
                        margin: 20,          // Margen INTERNO blanco obligatorio
                        background: "#ffffff",
                        lineColor: "#000000"
                    });
                } catch (e) { console.error(e); }

                // Convertir a Imagen PNG
                let img = document.createElement('img');
                img.src = canvas.toDataURL("image/png");
                img.className = 'barcode-img'; 
                
                div.appendChild(img);
                
                // Código Texto (Opcional, para que el humano lea)
                /*
                let codeTxt = document.createElement('div');
                codeTxt.style.fontSize = "9px";
                codeTxt.style.fontFamily = "Arial";
                codeTxt.style.fontWeight = "bold";
                codeTxt.innerText = item.co_art;
                div.appendChild(codeTxt);
                */

                contenedor.appendChild(div);
            }
        });

        setTimeout(() => { window.print(); }, 800); // Un poco más de tiempo para procesar imágenes
    }
</script>

</body>
</html>