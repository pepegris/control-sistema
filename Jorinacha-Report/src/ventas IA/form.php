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
        max-width: 900px;
        margin: 40px auto;
        padding: 30px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .form-group { margin-bottom: 20px; }

    label { display: block; margin-bottom: 8px; font-weight: bold; color: #a0a0a0; }

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

    /* Botón Acción */
    .btn-ia {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none; color: white; padding: 15px 30px; font-size: 18px;
        border-radius: 50px; cursor: pointer; width: 100%; font-weight: bold; margin-top: 20px;
        transition: all 0.3s;
    }
    .btn-ia:hover { transform: scale(1.02); }
    .btn-ia:disabled { background: #3b3f5c; cursor: not-allowed; transform: none; color: #a0a0a0; }

    /* Resultados */
    #resultado-panel {
        display: none; margin-top: 30px; background: rgba(0, 255, 127, 0.05);
        border: 1px solid #00ff7f; padding: 20px; border-radius: 8px;
    }

    /* Animación Gemini */
    .gemini-logo-spin { width: 40px; height: 40px; animation: spin-pulse 3s infinite linear; vertical-align: middle; margin-right: 10px; }
    @keyframes spin-pulse {
        0% { transform: rotate(0deg) scale(1); filter: drop-shadow(0 0 5px #4ea8de); }
        50% { transform: rotate(180deg) scale(1.1); filter: drop-shadow(0 0 15px #6930c3); }
        100% { transform: rotate(360deg) scale(1); filter: drop-shadow(0 0 5px #4ea8de); }
    }

    /* NUEVOS ESTILOS: SWITCH Y TABLA */
    .mode-switch {
        display: flex; justify-content: center; gap: 20px; margin-bottom: 30px;
        background: rgba(0,0,0,0.2); padding: 10px; border-radius: 50px;
    }
    .mode-option {
        cursor: pointer; padding: 10px 20px; border-radius: 30px; border: 1px solid transparent; transition: all 0.3s;
    }
    .mode-option.active {
        background: #00ff7f; color: #1a1d2e; font-weight: bold; box-shadow: 0 0 10px rgba(0,255,127,0.5);
    }
    .mode-option input { display: none; } 

    .table-compras { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 0.9em; }
    .table-compras th { background: rgba(255,255,255,0.1); padding: 12px; text-align: left; color: #00ff7f; }
    .table-compras td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: top;}
    .priority-Alta { color: #ff4444; font-weight: bold; }
    .priority-Media { color: #ffbb33; }
    .priority-Baja { color: #00d2ff; }
</style>

<div class="ia-container">
    <h2 style="text-align: center; margin-bottom: 20px;">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
             <path d="M12 2C12.5 7 16 11 21 12C16 13 12.5 17 12 22C11.5 17 8 13 3 12C8 11 11.5 7 12 2Z" fill="url(#grad1)"/>
             <defs><linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#4ea8de;stop-opacity:1" /><stop offset="100%" style="stop-color:#6930c3;stop-opacity:1" /></linearGradient></defs>
        </svg>
        Planner: Profit Plus
    </h2>

    <div class="mode-switch">
        <label class="mode-option active" id="lblMode1">
            <input type="radio" name="ia_mode" value="prediccion" checked onchange="cambiarModo(this)">
            🔮 Proyección de Ventas
        </label>
        <label class="mode-option" id="lblMode2">
            <input type="radio" name="ia_mode" value="compras" onchange="cambiarModo(this)">
            📦 Asistente de Compras
        </label>
    </div>

    <div class="form-group">
        <label>📅 Rango de Análisis</label>
        <select id="select_meses">
            <option value="3">Últimos 3 Meses</option>
            <option value="6">Últimos 6 Meses</option>
            <option value="12" selected>Últimos 12 Meses</option>
        </select>
    </div>

    <div class="form-group" style="position: relative;">
        <label>📦 Buscar Producto (Dejar vacío para análisis Global)</label>
        <input type="text" id="input_busqueda" placeholder="Nombre del artículo..." autocomplete="off">
        <input type="hidden" id="codigo_seleccionado">
        <div id="sugerencias"></div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label>Línea</label>
            <select id="select_linea"><option value="">-- Todas (Global) --</option></select>
        </div>
        <div class="col-md-6 form-group">
            <label>Sub-Línea</label>
            <select id="select_sublinea" disabled><option value="">-- Todas --</option></select>
        </div>
    </div>

    <button class="btn-ia" id="btnProcesar" onclick="consultarIA()">🚀 Ejecutar Análisis</button>

    <div id="loader" style="display:none; text-align:center; margin-top:20px;">
        <svg class="gemini-logo-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 0C12.5 7 16 11 21 12C16 13 12.5 17 12 24C11.5 17 8 13 3 12C8 11 11.5 7 12 0Z" fill="url(#gradLoader)" />
             <defs><linearGradient id="gradLoader" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#4ea8de;stop-opacity:1" /><stop offset="100%" style="stop-color:#ff00cc;stop-opacity:1" /></linearGradient></defs>
        </svg>
        <br><span style="font-size: 1.1em; color: white;">Analizando todas las sucursales y conectando con Gemini...</span>
        <br><span style="font-size: 14px; color: #aaa;">Esto puede tomar unos segundos... <strong id="segundos_timer" style="color: white;">0</strong>s</span>
    </div>

    <div id="resultado-panel">
        <div id="titulo_reporte_dinamico" style="text-align:center; color:#fff; font-size:1.2em; margin-bottom:20px; border-bottom:1px solid #333; padding-bottom:10px;"></div>

        <div id="view_prediccion">
            <div id="res_cantidad"></div>
            <p><strong>📊 Tendencia:</strong> <span id="res_tendencia"></span></p>
            <p><strong>⚠️ Calidad:</strong> <span id="res_calidad"></span></p>
            <p><strong>💡 Estrategia:</strong> <span id="res_accion"></span></p>
            <div style="margin-top: 30px; height: 350px; position: relative; width: 100%;">
                <canvas id="graficoVentas"></canvas>
            </div>
        </div>

        <div id="view_compras" style="display:none;">
            <div style="background: rgba(255, 255, 255, 0.05); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <h4 style="color: #00d2ff; margin-top:0;">🎨 Análisis de Atributos (Tallas/Colores)</h4>
                <p id="res_tallas_colores" style="font-style: italic;"></p>
                <p><strong>🚨 Tiendas Críticas:</strong> <span id="res_tiendas_criticas" style="color:#ff4444; font-weight:bold;"></span></p>
            </div>

            <h4 style="color: #00ff7f;">🛒 Sugerencias de Reposición (Top x Línea)</h4>
            <div style="overflow-x: auto;">
                <table class="table-compras">
                    <thead>
                        <tr>
                            <th>Artículo / Categoría</th>
                            <th>Cant.</th>
                            <th>Prioridad</th>
                            <th>Distribución</th>
                        </tr>
                    </thead>
                    <tbody id="tabla_compras_body"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let chartInstancia = null;
    let timerProcesando = null;
    let timerCooldown = null;

    // --- CARGA Y SELECTORES ---
    document.addEventListener("DOMContentLoaded", () => {
        fetch('ajax_datos_maestros.php?accion=lineas').then(r=>r.json()).then(data=>{
            const sel=document.getElementById('select_linea');
            data.forEach(d=>{ let o=document.createElement('option'); o.value=d.codigo; o.text=d.nombre; sel.appendChild(o); });
        });
    });

    function cambiarModo(radio) {
        document.querySelectorAll('.mode-option').forEach(l => l.classList.remove('active'));
        radio.parentElement.classList.add('active');
        document.getElementById('resultado-panel').style.display = 'none';
    }

    document.getElementById('select_linea').addEventListener('change', function() {
        const linea = this.value;
        const subSel = document.getElementById('select_sublinea');
        document.getElementById('input_busqueda').value = ''; document.getElementById('codigo_seleccionado').value = '';
        subSel.innerHTML = '<option value="">-- Todas --</option>';
        if (linea) {
            subSel.disabled = false;
            fetch(`ajax_datos_maestros.php?accion=sublineas&linea=${linea}`).then(r=>r.json()).then(data=>{
                data.forEach(d=>{ let o=document.createElement('option'); o.value=d.codigo; o.text=d.nombre; subSel.appendChild(o); });
            });
        } else { subSel.disabled = true; }
    });

    const inputBusqueda = document.getElementById('input_busqueda');
    inputBusqueda.addEventListener('input', function() {
        const q = this.value;
        const div = document.getElementById('sugerencias');
        if (q.length < 3) { div.style.display = 'none'; return; }
        document.getElementById('select_linea').value = ""; document.getElementById('select_sublinea').disabled = true;
        fetch(`ajax_datos_maestros.php?accion=buscar_art&q=${q}`).then(r=>r.json()).then(data=>{
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
        const modo = document.querySelector('input[name="ia_mode"]:checked').value;
        const prodCod = document.getElementById('codigo_seleccionado').value;
        const linCod = document.getElementById('select_linea').value;
        const subCod = document.getElementById('select_sublinea').value;
        const meses = document.getElementById('select_meses').value;

        // NOTA: ELIMINADA LA VALIDACIÓN QUE OBLIGABA A SELECCIONAR.
        // Ahora permite enviar todo vacío para análisis global.

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
                body: JSON.stringify({ modo: modo, producto: prodCod, linea: linCod, sublinea: subCod, meses: meses })
            });
            const json = await res.json();

            if (json.success) {
                // Título dinámico
                let titulo = (modo === 'prediccion') ? "Análisis de Demanda" : "Asistente de Compras";
                if(!linCod && !prodCod) titulo += " (GLOBAL - Toda la Empresa)";
                else if(linCod) titulo += " (Por Categoría)";
                else titulo += " (Por Producto Específico)";
                
                document.getElementById('titulo_reporte_dinamico').innerText = titulo;
                document.getElementById('resultado-panel').style.display = 'block';

                if (modo === 'prediccion') {
                    document.getElementById('view_prediccion').style.display = 'block';
                    document.getElementById('view_compras').style.display = 'none';
                    renderizarModoPrediccion(json); 
                } else {
                    document.getElementById('view_prediccion').style.display = 'none';
                    document.getElementById('view_compras').style.display = 'block';

                    document.getElementById('res_tallas_colores').innerText = json.data.analisis_tallas_colores;
                    document.getElementById('res_tiendas_criticas').innerText = json.data.tiendas_criticas;

                    const tbody = document.getElementById('tabla_compras_body');
                    tbody.innerHTML = '';
                    json.data.recomendaciones.forEach(item => {
                        let row = `<tr>
                            <td><strong>${item.articulo}</strong><br><small style="color:#aaa">${item.razon}</small></td>
                            <td style="font-size:1.2em; text-align:center;">${item.cantidad_sugerida}</td>
                            <td class="priority-${item.prioridad}">${item.prioridad}</td>
                            <td>${item.distribucion_sugerida}</td>
                        </tr>`;
                        tbody.innerHTML += row;
                    });
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

    function renderizarModoPrediccion(json) {
        const ventaReal = parseInt(json.meta.venta_acumulada_real);
        const ventaFalta = parseInt(json.data.prediccion_cierre);
        const cierreTotal = ventaReal + ventaFalta;

        const htmlNumeros = `
            <div style="display: flex; justify-content: space-around; gap: 20px; margin-bottom: 20px;">
                <div style="text-align: center; background: rgba(0, 210, 255, 0.05); padding: 15px; border-radius: 10px; border: 1px solid rgba(0, 210, 255, 0.3); flex:1;">
                    <div style="font-size: 0.7em; color: #aaa; text-transform: uppercase; margin-bottom: 10px;">RESTO DE ${json.meta.mes_actual}</div>
                    <div style="display: flex; justify-content: center; gap: 8px; font-size: 1.2em; color: white;">
                        <span>${ventaReal}</span><span style="color:#00d2ff">+</span><span style="color:#00d2ff; font-weight:bold;">${ventaFalta}</span>
                    </div>
                    <div style="font-size: 2.5em; font-weight: bold; color: #00d2ff; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 5px;">= ${cierreTotal}</div>
                    <div style="font-size: 0.8em; color: #00d2ff; margin-top:5px;">Cierre Estimado</div>
                </div>
                <div style="text-align: center; background: rgba(0, 255, 127, 0.05); padding: 15px; border-radius: 10px; border: 1px solid rgba(0, 255, 127, 0.3); flex:1;">
                    <div style="font-size: 0.7em; color: #aaa; text-transform: uppercase; margin-bottom: 35px;">PROYECCIÓN ${json.meta.mes_proximo}</div>
                    <div style="font-size: 3em; font-weight: bold; color: #00ff7f;">${json.data.prediccion_enero}</div>
                    <div style="font-size: 0.8em; color: #00ff7f; margin-top:5px;">Predicción Ventas</div>
                </div>
            </div>
            <div style="text-align:center; font-size:0.8em; color:#666; margin-bottom:15px;">* Cifras en Unidades Netas (Ventas - Devoluciones)</div>`;
        
        document.getElementById('res_cantidad').innerHTML = htmlNumeros;
        document.getElementById('res_tendencia').innerText = json.data.tendencia;
        const elemCalidad = document.getElementById('res_calidad');
        elemCalidad.innerText = json.data.alerta_calidad;
        elemCalidad.style.color = (json.data.alerta_calidad.match(/Estable|Baja|Normal|Buena/)) ? "#00ff7f" : "#ff4444";
        document.getElementById('res_accion').innerText = json.data.accion;
        
        if (json.historia) { 
             renderizarGrafico(json.historia, cierreTotal, json.data.prediccion_enero, json.meta.mes_actual, json.meta.mes_proximo); 
        }
    }

    function activarCooldown(segundosRestantes) {
        const btn = document.getElementById('btnProcesar');
        btn.disabled = true;
        if (timerCooldown) clearInterval(timerCooldown);
        timerCooldown = setInterval(() => {
            btn.innerHTML = `⏳ Espera <strong>${segundosRestantes}s</strong>...`;
            segundosRestantes--;
            if (segundosRestantes < 0) {
                clearInterval(timerCooldown);
                btn.disabled = false; btn.innerHTML = "🚀 Ejecutar Análisis";
            }
        }, 1000);
    }

    function renderizarGrafico(historia, cierreMesActual, prediccionMesSiguiente, nombreMesActual, nombreMesSiguiente) {
        const ctx = document.getElementById('graficoVentas');
        if (!ctx) return;
        let etiquetas = Object.keys(historia);
        let datos = Object.values(historia);
        datos.pop(); etiquetas.pop();
        etiquetas.push(nombreMesActual + " (Cierre)");
        datos.push(cierreMesActual);
        etiquetas.push(nombreMesSiguiente + " (IA)");
        let datosPrediccion = new Array(datos.length).fill(null);
        datosPrediccion[datos.length - 1] = cierreMesActual;
        datosPrediccion.push(prediccionMesSiguiente);

        if (chartInstancia) { chartInstancia.destroy(); }
        chartInstancia = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: etiquetas,
                datasets: [
                    { label: 'Histórico + Cierre', data: datos, borderColor: '#00d2ff', backgroundColor: 'rgba(0, 210, 255, 0.1)', borderWidth: 2, tension: 0.3, pointRadius: 4, fill: true },
                    { label: 'Proyección Futura', data: datosPrediccion, borderColor: '#00ff7f', borderDash: [5, 5], borderWidth: 3, pointRadius: 6, pointBackgroundColor: '#00ff7f' }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { labels: { color: 'white' } } },
                scales: { y: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#a0a0a0' } }, x: { grid: { display: false }, ticks: { color: '#a0a0a0' } } }
            }
        });
    }
</script>