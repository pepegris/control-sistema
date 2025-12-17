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

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #a0a0a0;
    }

    input,
    select {
        width: 100%;
        padding: 12px;
        background: #1a1d2e;
        border: 1px solid #3b3f5c;
        color: white;
        border-radius: 5px;
        font-size: 16px;
    }

    input:focus,
    select:focus {
        outline: none;
        border-color: #5c6ac4;
    }

    /* Sugerencias */
    #sugerencias {
        background: #1a1d2e;
        border: 1px solid #3b3f5c;
        max-height: 150px;
        overflow-y: auto;
        position: absolute;
        width: 90%;
        z-index: 1000;
        display: none;
    }

    .sugerencia-item {
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px solid #2d324a;
    }

    .sugerencia-item:hover {
        background: #5c6ac4;
    }

    /* Botón con estados */
    .btn-ia {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 15px 30px;
        font-size: 18px;
        border-radius: 50px;
        cursor: pointer;
        width: 100%;
        font-weight: bold;
        margin-top: 20px;
        transition: all 0.3s;
    }

    .btn-ia:hover {
        transform: scale(1.02);
    }

    /* Estado deshabilitado (Cooldown) */
    .btn-ia:disabled {
        background: #3b3f5c;
        /* Gris oscuro */
        cursor: not-allowed;
        transform: none;
        color: #a0a0a0;
    }

    /* Resultados */
    #resultado-panel {
        display: none;
        margin-top: 30px;
        background: rgba(0, 255, 127, 0.05);
        border: 1px solid #00ff7f;
        padding: 20px;
        border-radius: 8px;
    }

    .big-number {
        font-size: 3em;
        font-weight: bold;
        color: #00ff7f;
    }

    /* ANIMACIÓN DEL LOGO GEMINI */
    .gemini-logo-spin {
        width: 40px;
        height: 40px;
        animation: spin-pulse 3s infinite linear;
        vertical-align: middle;
        margin-right: 10px;
    }

    @keyframes spin-pulse {
        0% {
            transform: rotate(0deg) scale(1);
            filter: drop-shadow(0 0 5px #4ea8de);
        }

        50% {
            transform: rotate(180deg) scale(1.1);
            filter: drop-shadow(0 0 15px #6930c3);
        }

        100% {
            transform: rotate(360deg) scale(1);
            filter: drop-shadow(0 0 5px #4ea8de);
        }
    }
</style>

<div class="ia-container">
    <h2 style="text-align: center; margin-bottom: 30px;">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C12.5 7 16 11 21 12C16 13 12.5 17 12 22C11.5 17 8 13 3 12C8 11 11.5 7 12 2Z" fill="url(#grad1)" />
            <defs>
                <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#4ea8de;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#6930c3;stop-opacity:1" />
                </linearGradient>
            </defs>
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
            <select id="select_linea">
                <option value="">-- Seleccionar --</option>
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label>Sub-Línea</label>
            <select id="select_sublinea" disabled>
                <option value="">-- Seleccionar --</option>
            </select>
        </div>
    </div>

    <button class="btn-ia" id="btnProcesar" onclick="consultarIA()">🚀 Analizar Demanda</button>

    <div id="loader" style="display:none; text-align:center; margin-top:20px;">
        <svg class="gemini-logo-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 0C12.5 7 16 11 21 12C16 13 12.5 17 12 24C11.5 17 8 13 3 12C8 11 11.5 7 12 0Z" fill="url(#gradLoader)" />
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
    let timerProcesando = null;
    let timerCooldown = null;

    // --- CARGA INICIAL ---
    document.addEventListener("DOMContentLoaded", () => {
        fetch('ajax_datos_maestros.php?accion=lineas')
            .then(r => r.json())
            .then(data => {
                const sel = document.getElementById('select_linea');
                data.forEach(d => {
                    let opt = document.createElement('option');
                    opt.value = d.codigo; opt.text = d.nombre; // Guardamos texto
                    sel.appendChild(opt);
                });
            });
    });

    // --- LOGICA SELECTORES ---
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
                        opt.value = d.codigo; opt.text = d.nombre;
                        subSel.appendChild(opt);
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
                            inputBusqueda.value = item.descripcion; // Nombre visible
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
        const prodCod = document.getElementById('codigo_seleccionado').value;
        const prodNom = document.getElementById('input_busqueda').value;
        
        const linSel = document.getElementById('select_linea');
        const linCod = linSel.value;
        const linNom = linSel.options[linSel.selectedIndex]?.text || "";

        const subSel = document.getElementById('select_sublinea');
        const subCod = subSel.value;
        const subNom = subSel.options[subSel.selectedIndex]?.text || "";

        const meses = document.getElementById('select_meses').value;

        if (!prodCod && !linCod) { alert("Selecciona un producto o línea."); return; }

        // --- CONSTRUIR TÍTULO DEL REPORTE (NOMBRE REAL) ---
        let tituloAnalisis = "";
        if (prodCod) {
            tituloAnalisis = `📦 ${prodNom}`;
        } else {
            tituloAnalisis = `📂 Línea: ${linNom}`;
            if (subCod) tituloAnalisis += ` ➝ ${subNom}`;
        }

        // UI Loading
        document.getElementById('btnProcesar').disabled = true;
        document.getElementById('loader').style.display = 'block';
        document.getElementById('resultado-panel').style.display = 'none';

        let segundos = 0;
        document.getElementById('segundos_timer').innerText = "0";
        if(timerProcesando) clearInterval(timerProcesando);
        timerProcesando = setInterval(() => { segundos++; document.getElementById('segundos_timer').innerText = segundos; }, 1000);

        try {
            const res = await fetch('backend_prediccion.php', {
                method: 'POST',
                body: JSON.stringify({ producto: prodCod, linea: linCod, sublinea: subCod, meses: meses })
            });
            const json = await res.json();

            if (json.success) {
                // INYECTAR TÍTULO
                // Creamos un div para el título si no existe
                let divTitulo = document.getElementById('titulo_reporte_dinamico');
                if(!divTitulo) {
                    divTitulo = document.createElement('div');
                    divTitulo.id = 'titulo_reporte_dinamico';
                    divTitulo.style.cssText = "text-align:center; color:#fff; font-size:1.2em; margin-bottom:20px; padding-bottom:10px; border-bottom:1px solid #333;";
                    const panel = document.getElementById('resultado-panel');
                    panel.insertBefore(divTitulo, panel.firstChild);
                }
                divTitulo.innerText = "Analizando: " + tituloAnalisis;

                // RENDERIZAR DOS NÚMEROS (GRID)
                // Usamos Flexbox para poner Cierre Dic y Enero lado a lado
                const htmlNumeros = `
                    <div style="display: flex; justify-content: space-around; gap: 20px; margin-bottom: 20px;">
                        
                        <div style="text-align: center;">
                            <div style="font-size: 0.8em; color: #aaa; text-transform: uppercase;">
                                RESTO DE ${json.meta.mes_actual} (${json.meta.dias_restantes} Días)
                            </div>
                            <div style="font-size: 2.5em; font-weight: bold; color: #00d2ff;">
                                ${json.data.prediccion_cierre}
                            </div>
                            <div style="font-size: 0.9em; color: white;">Predicción Ventas</div>
                        </div>

                        <div style="text-align: center;">
                            <div style="font-size: 0.8em; color: #aaa; text-transform: uppercase;">
                                PROYECCIÓN ${json.meta.mes_proximo}
                            </div>
                            <div style="font-size: 2.5em; font-weight: bold; color: #00ff7f;">
                                ${json.data.prediccion_enero}
                            </div>
                            <div style="font-size: 0.9em; color: white;">Predicción Ventas</div>
                        </div>

                    </div>
                    <div style="text-align:center; font-size:0.8em; color:#666; margin-bottom:15px;">* Cifras en Unidades Netas (Ventas - Devoluciones)</div>
                `;

                // Reemplazamos el div "res_cantidad" anterior con este bloque doble
                document.getElementById('res_cantidad').innerHTML = htmlNumeros;
                document.getElementById('res_cantidad').className = ''; // Quitamos clase big-number para que no afecte el layout nuevo

                // Resto de datos
                document.getElementById('res_tendencia').innerText = json.data.tendencia;
                
                const elemCalidad = document.getElementById('res_calidad');
                elemCalidad.innerText = json.data.alerta_calidad;
                elemCalidad.style.color = (json.data.alerta_calidad.match(/Estable|Baja|Normal|Buena/)) ? "#00ff7f" : "#ff4444";

                document.getElementById('res_accion').innerText = json.data.accion;
                document.getElementById('resultado-panel').style.display = 'block';

                if (json.historia) { 
                    // Graficamos la predicción de Enero (la más importante)
                    renderizarGrafico(json.historia, json.data.prediccion_enero); 
                }
            } else {
                alert("Error: " + (json.error || "Desconocido"));
            }
        } catch (e) {
            console.error(e); alert("Error de conexión");
        } finally {
            clearInterval(timerProcesando);
            document.getElementById('loader').style.display = 'none';
            activarCooldown(60); 
        }
    }

    // Funciones auxiliares (Cooldown y Gráfico) se mantienen igual...
    function activarCooldown(segundosRestantes) {
        const btn = document.getElementById('btnProcesar');
        btn.disabled = true;
        if (timerCooldown) clearInterval(timerCooldown);
        timerCooldown = setInterval(() => {
            btn.innerHTML = `⏳ Espera <strong>${segundosRestantes}s</strong>...`;
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
        etiquetas.push("Enero 2026 🤖"); // Etiqueta fija o dinámica según quieras
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
                    label: 'Pronóstico Enero', data: datosPrediccion, borderColor: '#00ff7f', borderDash: [5, 5], borderWidth: 3, pointRadius: 6, pointBackgroundColor: '#00ff7f'
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