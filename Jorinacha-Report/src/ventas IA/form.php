<?php
require '../../includes/log.php';
include '../../includes/header.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* ... (TUS ESTILOS ANTERIORES CSS SE MANTIENEN IGUAL) ... */
    
    /* ESTILOS NUEVOS PARA EL SWITCH Y TABLA */
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
    .mode-option input { display: none; } /* Ocultar radio real */

    /* Tabla de Compras */
    .table-compras { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .table-compras th { background: rgba(255,255,255,0.1); padding: 12px; text-align: left; color: #00ff7f; }
    .table-compras td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.05); }
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
        Planner IA: Profit Plus
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
        <label>📦 Buscar Producto (Opcional en modo Compras)</label>
        <input type="text" id="input_busqueda" placeholder="Nombre del artículo..." autocomplete="off">
        <input type="hidden" id="codigo_seleccionado">
        <div id="sugerencias"></div>
    </div>

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

    <button class="btn-ia" id="btnProcesar" onclick="consultarIA()">🚀 Ejecutar Análisis</button>

    <div id="loader" style="display:none; text-align:center; margin-top:20px;">
        <svg class="gemini-logo-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 0C12.5 7 16 11 21 12C16 13 12.5 17 12 24C11.5 17 8 13 3 12C8 11 11.5 7 12 0Z" fill="url(#gradLoader)" />
             <defs><linearGradient id="gradLoader" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#4ea8de;stop-opacity:1" /><stop offset="100%" style="stop-color:#ff00cc;stop-opacity:1" /></linearGradient></defs>
        </svg>
        <br><span style="font-size: 1.1em; color: white;">Procesando datos de 17 sucursales...</span>
        <br><span style="font-size: 14px; color: #aaa;">Tiempo: <strong id="segundos_timer" style="color: white;">0</strong>s</span>
    </div>

    <div id="resultado-panel">
        <div id="titulo_reporte_dinamico" style="text-align:center; color:#fff; font-size:1.2em; margin-bottom:20px; border-bottom:1px solid #333;"></div>

        <div id="view_prediccion">
            <div id="res_cantidad"></div> <p><strong>📊 Tendencia:</strong> <span id="res_tendencia"></span></p>
            <p><strong>⚠️ Calidad:</strong> <span id="res_calidad"></span></p>
            <p><strong>💡 Estrategia:</strong> <span id="res_accion"></span></p>
            <div style="margin-top: 30px; height: 350px; position: relative; width: 100%;">
                <canvas id="graficoVentas"></canvas>
            </div>
        </div>

        <div id="view_compras" style="display:none;">
            <div style="background: rgba(255, 255, 255, 0.05); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <h4 style="color: #00d2ff; margin-top:0;">🎨 Análisis de Atributos (IA)</h4>
                <p id="res_tallas_colores" style="font-style: italic;"></p>
                <p><strong>🚨 Tiendas Críticas:</strong> <span id="res_tiendas_criticas" style="color:#ff4444; font-weight:bold;"></span></p>
            </div>

            <h4 style="color: #00ff7f;">🛒 Lista de Sugerencias de Reposición</h4>
            <div style="overflow-x: auto;">
                <table class="table-compras">
                    <thead>
                        <tr>
                            <th>Artículo</th>
                            <th>Cant. Sugerida</th>
                            <th>Prioridad</th>
                            <th>Distribución Logística</th>
                        </tr>
                    </thead>
                    <tbody id="tabla_compras_body">
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let chartInstancia = null;
    let timerProcesando = null;
    let timerCooldown = null;

    // --- CARGA Y EVENTOS (Igual que antes) ---
    document.addEventListener("DOMContentLoaded", () => {
        fetch('ajax_datos_maestros.php?accion=lineas').then(r=>r.json()).then(data=>{
            const sel=document.getElementById('select_linea');
            data.forEach(d=>{ let o=document.createElement('option'); o.value=d.codigo; o.text=d.nombre; sel.appendChild(o); });
        });
    });

    // Toggle de Modos
    function cambiarModo(radio) {
        document.querySelectorAll('.mode-option').forEach(l => l.classList.remove('active'));
        radio.parentElement.classList.add('active');
        // Resetear UI
        document.getElementById('resultado-panel').style.display = 'none';
    }
    
    // ... (Tu lógica de selectores de Línea/Sublínea y Autocomplete VA AQUÍ IGUAL QUE ANTES) ...
    // COPIA TU CÓDIGO DE EVENT LISTENERS DE "select_linea" E "input_busqueda" AQUÍ
    // (Lo omito para ahorrar espacio, pero es idéntico al anterior)

    // --- CONSULTA PRINCIPAL ---
    async function consultarIA() {
        const modo = document.querySelector('input[name="ia_mode"]:checked').value;
        const prodCod = document.getElementById('codigo_seleccionado').value;
        const linCod = document.getElementById('select_linea').value;
        const subCod = document.getElementById('select_sublinea').value;
        const meses = document.getElementById('select_meses').value;

        // Validación según modo
        if (modo === 'prediccion' && !prodCod && !linCod) { 
            alert("Para Proyección, selecciona un producto o línea."); return; 
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
                body: JSON.stringify({ modo: modo, producto: prodCod, linea: linCod, sublinea: subCod, meses: meses })
            });
            const json = await res.json();

            if (json.success) {
                document.getElementById('titulo_reporte_dinamico').innerText = (modo === 'prediccion') ? "Análisis de Demanda" : "Asistente de Abastecimiento Inteligente";
                document.getElementById('resultado-panel').style.display = 'block';

                if (modo === 'prediccion') {
                    // --- RENDERIZAR MODO 1 (Igual que antes) ---
                    document.getElementById('view_prediccion').style.display = 'block';
                    document.getElementById('view_compras').style.display = 'none';
                    
                    // (Tu lógica de pintar números grandes y gráfica VA AQUÍ)
                    // ... Pega tu código de "htmlNumeros" y "renderizarGrafico" aquí ...
                    // (Resumido para el ejemplo):
                    renderizarModoPrediccion(json); // Función auxiliar abajo

                } else {
                    // --- RENDERIZAR MODO 2: COMPRAS ---
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
            document.getElementById('btnProcesar').disabled = false; // Reactivamos (o usa tu cooldown)
        }
    }

    // --- FUNCIÓN PARA ORDENAR EL MODO PREDICCIÓN (Tu código viejo encapsulado) ---
    function renderizarModoPrediccion(json) {
        // CÁLCULO DE CIERRE TOTAL
        const ventaReal = parseInt(json.meta.venta_acumulada_real);
        const ventaFalta = parseInt(json.data.prediccion_cierre);
        const cierreTotal = ventaReal + ventaFalta;

        const htmlNumeros = `
            <div style="display: flex; justify-content: space-around; gap: 20px; margin-bottom: 20px;">
                <div style="text-align: center; background: rgba(0, 210, 255, 0.05); padding: 15px; border-radius: 10px; border: 1px solid rgba(0, 210, 255, 0.3); flex:1;">
                    <div style="font-size: 0.7em; color: #aaa; text-transform: uppercase;">RESTO DE ${json.meta.mes_actual}</div>
                    <div style="display: flex; justify-content: center; gap: 8px; font-size: 1.2em; color: white;">
                        <span>${ventaReal}</span><span style="color:#00d2ff">+</span><span style="color:#00d2ff; font-weight:bold;">${ventaFalta}</span>
                    </div>
                    <div style="font-size: 2.5em; font-weight: bold; color: #00d2ff; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 5px;">= ${cierreTotal}</div>
                    <div style="font-size: 0.8em; color: #00d2ff;">Cierre Estimado</div>
                </div>
                <div style="text-align: center; background: rgba(0, 255, 127, 0.05); padding: 15px; border-radius: 10px; border: 1px solid rgba(0, 255, 127, 0.3); flex:1;">
                    <div style="font-size: 0.7em; color: #aaa; text-transform: uppercase;">PROYECCIÓN ${json.meta.mes_proximo}</div>
                    <div style="font-size: 3em; font-weight: bold; color: #00ff7f;">${json.data.prediccion_enero}</div>
                    <div style="font-size: 0.8em; color: #00ff7f;">Predicción Ventas</div>
                </div>
            </div>`;
        
        document.getElementById('res_cantidad').innerHTML = htmlNumeros;
        document.getElementById('res_tendencia').innerText = json.data.tendencia;
        document.getElementById('res_calidad').innerText = json.data.alerta_calidad;
        document.getElementById('res_accion').innerText = json.data.accion;
        
        if (json.historia) { 
            // Graficamos (reutiliza tu función renderizarGrafico anterior)
             renderizarGrafico(json.historia, cierreTotal, json.data.prediccion_enero, json.meta.mes_actual, json.meta.mes_proximo); 
        }
    }
    
    // (AQUÍ PEGA TU FUNCIÓN renderizarGrafico() QUE YA TENÍAS)
    function renderizarGrafico(historia, cierreMesActual, prediccionMesSiguiente, nombreMesActual, nombreMesSiguiente) {
        // ... (Tu código de Chart.js) ...
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