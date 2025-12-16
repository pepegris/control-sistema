<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predicción IA - Profit Plus</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); width: 100%; max-width: 450px; text-align: center; }
        input { padding: 12px; width: 80%; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 15px; font-size: 16px; }
        button { background: #2563EB; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 16px; transition: 0.3s; }
        button:hover { background: #1d4ed8; }
        button:disabled { background: #93c5fd; cursor: wait; }
        
        #resultado { margin-top: 20px; display: none; text-align: left; background: #eff6ff; padding: 15px; border-radius: 8px; border-left: 5px solid #2563EB; }
        .loader { display: none; margin: 20px auto; border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .stat-num { font-size: 2em; font-weight: bold; color: #1e3a8a; }
        .label { font-size: 0.85em; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body>

<div class="card">
    <h2>🔮 Predicción de Demanda</h2>
    <p>Ingresa el código del artículo en Profit Plus</p>
    
    <input type="text" id="codigo" placeholder="Ej: SERV001" autocomplete="off">
    <br>
    <button onclick="predecirDemanda()" id="btnPredecir">Analizar con IA</button>
    
    <div class="loader" id="loader"></div>

    <div id="resultado">
        <div class="label">Predicción Próximo Mes</div>
        <div class="stat-num" id="prediccion_val">--</div>
        <hr style="border: 0; border-top: 1px solid #dbeafe; margin: 10px 0;">
        <p><strong>📈 Tendencia:</strong> <span id="tendencia_val"></span></p>
        <p><strong>💡 Sugerencia:</strong> <span id="accion_val"></span></p>
        <small style="color: #999; font-size: 10px;" id="debug_info"></small>
    </div>
</div>

<script>
async function predecirDemanda() {
    const codigo = document.getElementById('codigo').value;
    const btn = document.getElementById('btnPredecir');
    const loader = document.getElementById('loader');
    const resultadoDiv = document.getElementById('resultado');

    if(!codigo) return alert("Escribe un código de artículo");

    // UI Loading state
    btn.disabled = true;
    btn.innerText = "Consultando 17 tiendas...";
    loader.style.display = 'block';
    resultadoDiv.style.display = 'none';

    try {
        const response = await fetch('backend_prediccion.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ codigo: codigo })
        });

        const json = await response.json();

        if(json.success) {
            document.getElementById('prediccion_val').innerText = json.data.prediccion + " unds";
            document.getElementById('tendencia_val').innerText = json.data.tendencia;
            document.getElementById('accion_val').innerText = json.data.accion;
            document.getElementById('debug_info').innerText = json.debug;
            resultadoDiv.style.display = 'block';
        } else {
            alert("Error: " + (json.error || "Desconocido"));
        }

    } catch (e) {
        console.error(e);
        alert("Error de conexión con el servidor.");
    } finally {
        btn.disabled = false;
        btn.innerText = "Analizar con IA";
        loader.style.display = 'none';
    }
}
</script>

</body>
</html>