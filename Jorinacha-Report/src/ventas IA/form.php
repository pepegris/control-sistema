<?php
require '../../includes/log.php';
include '../../includes/header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body {
        background-color: #242943 !important;
        color: white;
        font-family: 'Segoe UI', sans-serif;
    }

    .ia-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 30px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .form-group { margin-bottom: 20px; }

    label {
        display: block; margin-bottom: 8px; font-weight: bold; color: #a0a0a0;
    }

    input, select {
        width: 100%; padding: 12px; background: #1a1d2e; border: 1px solid #3b3f5c;
        color: white; border-radius: 5px; font-size: 16px;
    }

    input:focus, select:focus { outline: none; border-color: #5c6ac4; }

    /* Sugerencias */
    #sugerencias {
        background: #1a1d2e; border: 1px solid #3b3f5c; max-height: 150px;
        overflow-y: auto; position: absolute; width: 90%; z-index: 1000; display: none;
    }
    .sugerencia-item { padding: 10px; cursor: pointer; border-bottom: 1px solid #2d324a; }
    .sugerencia-item:hover { background: #5c6ac4; }

    /* Botón con estados */
    .btn-ia {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none; color: white; padding: 15px 30px; font-size: 18px;
        border-radius: 50px; cursor: pointer; width: 100%; font-weight: bold; margin-top: 20px;
        transition: all 0.3s;
    }
    .btn-ia:hover { transform: scale(1.02); }
    
    /* Estado deshabilitado (Cooldown) */
    .btn-ia:disabled {
        background: #3b3f5c; /* Gris oscuro */
        cursor: not-allowed;
        transform: none;
        color: #a0a0a0;
    }

    /* Resultados */
    #resultado-panel {
        display: none; margin-top: 30px; background: rgba(0, 255, 127, 0.05);
        border: 1px solid #00ff7f; padding: 20px; border-radius: 8px;
    }
    .big-number { font-size: 3em; font-weight: bold; color: #00ff7f; }

    /* ANIMACIÓN DEL LOGO GEMINI */
    .gemini-logo-spin {
        width: 40px;
        height: 40px;
        animation: spin-pulse 3s infinite linear;
        vertical-align: middle;
        margin-right: 10px;
    }

    @keyframes spin-pulse {
        0% { transform: rotate(0deg) scale(1); filter: drop-shadow(0 0 5px #4ea8de); }
        50% { transform: rotate(180deg) scale(1.1); filter: drop-shadow(0 0 15px #6930c3); }
        100% { transform: rotate(360deg) scale(1); filter: drop-shadow(0 0 5px #4ea8de); }
    }
</style>

<div class="ia-container">
    <h2 style="text-align: center; margin-bottom: 30px;">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C12.5 7 16 11 21 12C16 13 12.5 17 12 22C11.5 17 8 13 3 12C8 11 11.5 7 12 2Z" fill="url(#grad1)"/>
            <defs><linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#4ea8de;stop-opacity:1" /><stop offset="100%" style="stop-color:#6930c3;stop-opacity:1" /></linearGradient></defs>
        </svg>
        Planner IA Gemini: Profit Plus
    </h2>

    <div class="form-group">
        <label>📅 Rango de Análisis Histórico</label>
        <select id="select_meses">
            <option value="3">Últimos 3 Meses (Corto Plazo)</option>
            <option value="6">Últimos 6 Meses (Semestral)</option>
            <option value="9">Últimos 9 Meses</option>
            <option value="12" selected>Últimos 12 Meses (Anual)</option>
        </select>
    </div>

    <div class="form-group" style="position: relative;">
        <label>📦 Buscar Producto</label>
        <input type="text" id="input_busqueda" placeholder="Escribe el nombre del artículo..." autocomplete="off">
        <input type="hidden" id="codigo_seleccionado">
        <div id="sugerencias"></div>
    </div>

    <div style="text-align: center; color: #666; margin: 15px 0;">--- O ANALIZAR POR CATEGORÍA ---</div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label>Línea</label>
            <select id="select_linea"><option value="">-- Seleccionar --</option></select>
        </div>
        <div class="col-md-6 form-group">
            <label>Sub-Línea</label>
            <select id="select_sublinea" disabled><option value="">-- Seleccionar --</option></select>
        </div>
    </div>

    <button class="btn-ia" id="btnProcesar" onclick="consultarIA()">🚀 Analizar Demanda</button>
    
    <div id="loader" style="display:none; text-align:center; margin-top:20px;">
        <svg class="gemini-logo-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 0C12.5 7 16 11 21 12C16 13 12.5 17 12 24C11.5 17 8 13 3 12C8 11 11.5 7 12 0Z" fill="url(#gradLoader)"/>
            <defs>
                <linearGradient id="gradLoader" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#4ea8de;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#ff00cc;stop-opacity:1" />
                </linearGradient>
            </defs>
        </svg>
        <br>
        <span style="font-size: 1.1em; color: white;">Analizando 17 sucursales y conectando con Gemini IA...</span>
        <br>
        <span style="font-size: 14px; color: #aaa;">Tiempo procesando: <strong id="segundos_timer" style="color: white;">0</strong>s</span>
    </div>

    <div id="resultado-panel">
        <h3>Proyección de Demanda</h3>
        <div class="big-number" id="res_cantidad">0</div>
        <p><strong>📊 Tendencia:</strong> <span id="res_tendencia"></span></p>
        <p style="color: #ff4444;"><strong>⚠️ Calidad/Devoluciones:</strong> <span id="res_calidad"></span></p>
        <p><strong>💡 Estrategia:</strong> <span id="res_accion"></span></p>
        <div style="margin-top: 30px; height: 350px; position: relative; width: 100%;">
            <canvas id="graficoVentas"></canvas>
        </div>
    </div>
</div>

<script>
    let chartInstancia = null;
    let timerProcesando = null; // Timer de "cuánto tarda la consulta"
    let timerCooldown = null;   // Timer de "espera 60s"

    // --- CARGA INICIAL ---
    document.addEventListener("DOMContentLoaded", () => {
        fetch('ajax_datos_maestros.php?accion=lineas')
            .then(r => r.json())
            .then(data => {
                const sel = document.getElementById('select_linea');
                data.forEach(d => {
                    let opt = document.createElement('option');
                    opt.value = d.codigo; opt.innerText = d.nombre; sel.appendChild(opt);
                });
            });
    });

    // --- LÓGICA SELECTORES ---
    document.getElementById('select_linea').addEventListener('change', function() {
        const linea = this.value;
        const subSel = document.getElementById('select_sublinea');
        document.getElementById('input_busqueda').value = '';
        document.getElementById('codigo_seleccionado').value = '';
        subSel.innerHTML = '<option value="">-- Todas --</option>';

        if (linea) {
            subSel.disabled = false;
            fetch(`ajax_datos_maestros.php?accion=sublineas&linea=${linea}`)
                .then(r => r.json()).then(data => {
                    data.forEach(d => {
                        let opt = document.createElement('option');
                        opt.value = d.codigo; opt.innerText = d.nombre; subSel.appendChild(opt);
                    });
                });
        } else { subSel.disabled = true; }
    });

    // --- AUTOCOMPLETE ---
    const inputBusqueda = document.getElementById('input_busqueda');
    inputBusqueda.addEventListener('input', function() {
        const q = this.value;
        const div = document.getElementById('sugerencias');
        if (q.length < 3) { div.style.display = 'none'; return; }
        document.getElementById('select_linea').value = "";
        document.getElementById('select_sublinea').disabled = true;

        fetch(`ajax_datos_maestros.php?accion=buscar_art&q=${q}`)
            .then(r => r.json()).then(data => {
                div.innerHTML = '';
                if (data.length > 0) {
                    div.style.display = 'block';
                    data.forEach(item => {
                        let d = document.createElement('div');
                        d.className = 'sugerencia-item';
                        d.innerHTML = `<span style="color:#aaa; font-size:0.8em">${item.codigo}</span> ${item.descripcion}`;
                        d.onclick = () => {
                            inputBusqueda.value = item.descripcion;
                            document.getElementById('codigo_seleccionado').value = item.codigo;
                            div.style.display = 'none';
                        };
                        div.appendChild(d);
                    });
                }
            });
    });

    // --- CONSULTA PRINCIPAL ---
    async function consultarIA() {
        const prod = document.getElementById('codigo_seleccionado').value;
        const lin = document.getElementById('select_linea').value;
        const sub = document.getElementById('select_sublinea').value;
        const meses = document.getElementById('select_meses').value;

        if (!prod && !lin) { alert("Selecciona un producto o línea."); return; }

        // 1. UI Loading
        document.getElementById('btnProcesar').disabled = true;
        document.getElementById('loader').style.display = 'block';
        document.getElementById('resultado-panel').style.display = 'none';

        // 2. Iniciar Timer de Proceso (0, 1, 2...)
        let segundos = 0;
        document.getElementById('segundos_timer').innerText = "0";
        if(timerProcesando) clearInterval(timerProcesando);
        timerProcesando = setInterval(() => { segundos++; document.getElementById('segundos_timer').innerText = segundos; }, 1000);

        try {
            const res = await fetch('backend_prediccion.php', {
                method: 'POST',
                body: JSON.stringify({ producto: prod, linea: lin, sublinea: sub, meses: meses })
            });
            const json = await res.json();

            if (json.success) {
                // Renderizar datos
                document.getElementById('res_cantidad').innerHTML = json.data.prediccion + " <span style='font-size:20px; color:white'>Unds Netas</span>";
                document.getElementById('res_tendencia').innerText = json.data.tendencia;
                
                const elemCalidad = document.getElementById('res_calidad');
                elemCalidad.innerText = json.data.alerta_calidad;
                elemCalidad.style.color = (json.data.alerta_calidad.match(/Estable|Baja|Normal/)) ? "#00ff7f" : "#ff4444";

                document.getElementById('res_accion').innerText = json.data.accion;
                document.getElementById('resultado-panel').style.display = 'block';

                if (json.historia) { renderizarGrafico(json.historia, json.data.prediccion); }
            } else {
                alert("Error: " + (json.error || "Desconocido"));
            }
        } catch (e) {
            console.error(e); alert("Error de conexión");
        } finally {
            // Detener timer de proceso y ocultar loader
            clearInterval(timerProcesando);
            document.getElementById('loader').style.display = 'none';
            
            // --- INICIAR COOLDOWN DE 60 SEGUNDOS ---
            activarCooldown(60); 
        }
    }

    // --- FUNCIÓN DE CUENTA REGRESIVA (BLOQUEO) ---
    function activarCooldown(segundosRestantes) {
        const btn = document.getElementById('btnProcesar');
        btn.disabled = true; // Asegurar bloqueado

        if (timerCooldown) clearInterval(timerCooldown);

        timerCooldown = setInterval(() => {
            btn.innerHTML = `⏳ Espera <strong>${segundosRestantes}s</strong> para consultar...`;
            segundosRestantes--;

            if (segundosRestantes < 0) {
                clearInterval(timerCooldown);
                btn.disabled = false;
                btn.innerHTML = "🚀 Analizar Demanda";
            }
        }, 1000);
    }

    function renderizarGrafico(historia, prediccionFutura) {
        const ctx = document.getElementById('graficoVentas');
        if (!ctx) return;
        let etiquetas = Object.keys(historia);
        let datos = Object.values(historia);
        etiquetas.push("Proyección 🤖");
        let datosPrediccion = new Array(datos.length).fill(null);
        datosPrediccion[datos.length - 1] = datos[datos.length - 1];
        datosPrediccion.push(prediccionFutura);

        if (chartInstancia) { chartInstancia.destroy(); }

        chartInstancia = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: etiquetas,
                datasets: [{
                    label: 'Ventas Netas', data: datos, borderColor: '#00d2ff', backgroundColor: 'rgba(0, 210, 255, 0.1)', borderWidth: 2, tension: 0.3, pointRadius: 4, fill: true
                }, {
                    label: 'Pronóstico IA', data: datosPrediccion, borderColor: '#00ff7f', borderDash: [5, 5], borderWidth: 3, pointRadius: 6, pointBackgroundColor: '#00ff7f'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { labels: { color: 'white' } } },
                scales: {
                    y: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#a0a0a0' } },
                    x: { grid: { display: false }, ticks: { color: '#a0a0a0' } }
                }
            }
        });
    }
</script>