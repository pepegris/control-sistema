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
    }

    .btn-ia:hover {
        transform: scale(1.02);
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
</style>

<div class="ia-container">
    <h2 style="text-align: center; margin-bottom: 30px;">🔮 Planner IA: Profit Plus</h2>

    <div class="form-group">
        <label>📅 Rango de Análisis Histórico</label>
        <select id="select_meses">
            <option value="3">Últimos 3 Meses (Corto Plazo)</option>
            <option value="6">Últimos 6 Meses (Semestral)</option>
            <option value="9">Últimos 9 Meses</option>
            <option value="12" selected>Últimos 12 Meses (Anual - Predeterminado)</option>
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
    <div id="loader" style="display:none; text-align:center; margin-top:20px;">🤖 Analizando tendencias en 17 sucursales y Conectado Gemini IA...</div>

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

    // Cargar Líneas
    document.addEventListener("DOMContentLoaded", () => {
        fetch('ajax_datos_maestros.php?accion=lineas')
            .then(r => r.json())
            .then(data => {
                const sel = document.getElementById('select_linea');
                data.forEach(d => {
                    let opt = document.createElement('option');
                    opt.value = d.codigo;
                    opt.innerText = d.nombre;
                    sel.appendChild(opt);
                });
            });
    });

    // Lógica Selectores (Línea/Sublínea)
    document.getElementById('select_linea').addEventListener('change', function() {
        const linea = this.value;
        const subSel = document.getElementById('select_sublinea');
        document.getElementById('input_busqueda').value = '';
        document.getElementById('codigo_seleccionado').value = '';
        subSel.innerHTML = '<option value="">-- Todas --</option>';

        if (linea) {
            subSel.disabled = false;
            fetch(`ajax_datos_maestros.php?accion=sublineas&linea=${linea}`)
                .then(r => r.json())
                .then(data => {
                    data.forEach(d => {
                        let opt = document.createElement('option');
                        opt.value = d.codigo;
                        opt.innerText = d.nombre;
                        subSel.appendChild(opt);
                    });
                });
        } else {
            subSel.disabled = true;
        }
    });

    // Autocomplete
    const inputBusqueda = document.getElementById('input_busqueda');
    inputBusqueda.addEventListener('input', function() {
        const q = this.value;
        const div = document.getElementById('sugerencias');
        if (q.length < 3) {
            div.style.display = 'none';
            return;
        }
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

    // CONSULTA PRINCIPAL
    async function consultarIA() {
        const prod = document.getElementById('codigo_seleccionado').value;
        const lin = document.getElementById('select_linea').value;
        const sub = document.getElementById('select_sublinea').value;
        const meses = document.getElementById('select_meses').value; // Nuevo dato

        if (!prod && !lin) {
            alert("Selecciona un producto o línea.");
            return;
        }

        document.getElementById('btnProcesar').disabled = true;
        document.getElementById('loader').style.display = 'block';
        document.getElementById('resultado-panel').style.display = 'none';

        try {
            const res = await fetch('backend_prediccion.php', {
                method: 'POST',
                body: JSON.stringify({
                    producto: prod,
                    linea: lin,
                    sublinea: sub,
                    meses: meses
                })
            });
            const json = await res.json();

    if (json.success) {
                // 1. Cantidad (Cambiamos a "Unds Netas" para ser precisos)
                document.getElementById('res_cantidad').innerHTML = json.data.prediccion + " <span style='font-size:20px; color:white'>Unds Netas</span>";
                
                // 2. Tendencia
                document.getElementById('res_tendencia').innerText = json.data.tendencia;
                
                // 3. NUEVO: Alerta de Calidad / Devoluciones
                // Asumimos que agregaste <span id="res_calidad"></span> en el HTML como vimos antes
                const elemCalidad = document.getElementById('res_calidad');
                elemCalidad.innerText = json.data.alerta_calidad;

                // Lógica de colores semafórica
                // Si la IA dice "Estable", "Baja" o "Normal", lo ponemos verde. Si no, Rojo.
                if (json.data.alerta_calidad.includes("Estable") || json.data.alerta_calidad.includes("Baja") || json.data.alerta_calidad.includes("Normal")) {
                     elemCalidad.style.color = "#00ff7f"; // Verde Neón (Todo OK)
                } else {
                     elemCalidad.style.color = "#ff4444"; // Rojo (Alerta de devoluciones altas)
                }

                // 4. Acción Recomendada
                document.getElementById('res_accion').innerText = json.data.accion;
                
                // 5. Mostrar Panel
                document.getElementById('resultado-panel').style.display = 'block';

                // 6. Gráfica (Datos Netos)
                if (json.historia) {
                    renderizarGrafico(json.historia, json.data.prediccion);
                } else {
                    console.error("El backend no envió datos históricos para graficar.");
                }
            } else {
                alert("Error: " + (json.error || "Desconocido"));
            }
        } catch (e) {
            console.error(e);
            alert("Error de conexión");
        } finally {
            document.getElementById('btnProcesar').disabled = false;
            document.getElementById('loader').style.display = 'none';
        }
    }

    function renderizarGrafico(historia, prediccionFutura) {
        const ctx = document.getElementById('graficoVentas');
        if (!ctx) return; // Seguridad

        let etiquetas = Object.keys(historia);
        let datos = Object.values(historia);

        // Agregar futuro
        etiquetas.push("Proyección 🤖");
        let datosPrediccion = new Array(datos.length).fill(null);
        datosPrediccion[datos.length - 1] = datos[datos.length - 1]; // Conectar
        datosPrediccion.push(prediccionFutura);

        if (chartInstancia) {
            chartInstancia.destroy();
        }

        chartInstancia = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: etiquetas,
                datasets: [{
                        label: 'Ventas Reales',
                        data: datos,
                        borderColor: '#00d2ff',
                        backgroundColor: 'rgba(0, 210, 255, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 4,
                        fill: true
                    },
                    {
                        label: 'Pronóstico IA',
                        data: datosPrediccion,
                        borderColor: '#00ff7f',
                        borderDash: [5, 5],
                        borderWidth: 3,
                        pointRadius: 6,
                        pointBackgroundColor: '#00ff7f'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: 'white'
                        }
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: 'rgba(255,255,255,0.1)'
                        },
                        ticks: {
                            color: '#a0a0a0'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#a0a0a0'
                        }
                    }
                }
            }
        });
    }
</script>