<?php
require '../../includes/log.php';
include '../../includes/header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Fondo solicitado */
    body {
        background-color: #242943 !important;
        color: white;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Contenedor principal */
    .ia-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 30px;
        background: rgba(255, 255, 255, 0.05); /* Efecto cristal sutil */
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .form-group { margin-bottom: 20px; }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #a0a0a0;
    }

    input, select {
        width: 100%;
        padding: 12px;
        background: #1a1d2e;
        border: 1px solid #3b3f5c;
        color: white;
        border-radius: 5px;
        font-size: 16px;
    }

    input:focus, select:focus {
        outline: none;
        border-color: #5c6ac4;
    }

    /* Autocomplete sugerencias */
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
    .sugerencia-item:hover { background: #5c6ac4; }

    .code-span {
        color: #aaa;
        font-size: 0.8em;
        margin-right: 10px;
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
        transition: transform 0.2s;
        font-weight: bold;
    }
    .btn-ia:hover { transform: scale(1.02); }

    /* Resultado */
    #resultado-panel {
        display: none;
        margin-top: 30px;
        background: rgba(0, 255, 127, 0.05); /* Verde muy suave */
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
    <h2 style="text-align: center; margin-bottom: 30px;">🔮 Predicción de Demanda IA</h2>

    <div class="form-group" style="position: relative;">
        <label>Buscar Producto (Escribe para autocompletar)</label>
        <input type="text" id="input_busqueda" placeholder="Ej: Tornillo, Aceite, Filtro..." autocomplete="off">
        <input type="hidden" id="codigo_seleccionado">
        <div id="sugerencias"></div>
    </div>

    <div style="text-align: center; color: #666; margin: 15px 0;">--- O BUSCAR POR GRUPOS ---</div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label>Línea</label>
            <select id="select_linea">
                <option value="">-- Seleccionar Línea --</option>
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label>Sub-Línea</label>
            <select id="select_sublinea" disabled>
                <option value="">-- Seleccione Línea primero --</option>
            </select>
        </div>
    </div>

    <button class="btn-ia" id="btnProcesar" onclick="consultarIA()">✨ Generar Predicción</button>
    
    <div id="loader" style="display:none; text-align:center; margin-top:20px;">
        Conectando a 17 Sucursales y a Gemini IA...
    </div>

    <div id="resultado-panel">
        <h3>Resultado del Análisis</h3>
        
        <div class="big-number" id="res_cantidad">0</div>
        
        <p><strong>Tendencia:</strong> <span id="res_tendencia"></span></p>
        <p><strong>Acción Recomendada:</strong> <span id="res_accion"></span></p>
        
        <div style="margin-top: 30px; height: 300px; position: relative;">
            <canvas id="graficoVentas"></canvas>
        </div>
    </div>
</div>

<script>
    let chartInstancia = null; // Variable para el gráfico

    // 1. CARGA INICIAL DE LÍNEAS
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

    // 2. CAMBIO EN LÍNEA -> CARGAR SUBLÍNEAS
    document.getElementById('select_linea').addEventListener('change', function() {
        const linea = this.value;
        const subSel = document.getElementById('select_sublinea');

        // Limpiar formulario de producto si se usa línea
        document.getElementById('input_busqueda').value = '';
        document.getElementById('codigo_seleccionado').value = '';

        subSel.innerHTML = '<option value="">-- Todas las Sub-líneas --</option>';

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

    // 3. AUTOCOMPLETADO DE PRODUCTOS
    const inputBusqueda = document.getElementById('input_busqueda');
    const sugerenciasDiv = document.getElementById('sugerencias');

    inputBusqueda.addEventListener('input', function() {
        const q = this.value;
        if (q.length < 3) {
            sugerenciasDiv.style.display = 'none';
            return;
        }

        // Limpiar selectores si escribe producto
        document.getElementById('select_linea').value = "";
        document.getElementById('select_sublinea').value = "";
        document.getElementById('select_sublinea').disabled = true;

        fetch(`ajax_datos_maestros.php?accion=buscar_art&q=${q}`)
            .then(r => r.json())
            .then(data => {
                sugerenciasDiv.innerHTML = '';
                if (data.length > 0) {
                    sugerenciasDiv.style.display = 'block';
                    data.forEach(item => {
                        let div = document.createElement('div');
                        div.className = 'sugerencia-item';
                        div.innerHTML = `<span class="code-span">${item.codigo}</span> ${item.descripcion}`;
                        div.onclick = () => {
                            inputBusqueda.value = item.descripcion; 
                            document.getElementById('codigo_seleccionado').value = item.codigo; 
                            sugerenciasDiv.style.display = 'none';
                        };
                        sugerenciasDiv.appendChild(div);
                    });
                }
            });
    });

    // 4. CONSULTA FINAL AL BACKEND
    async function consultarIA() {
        const prod = document.getElementById('codigo_seleccionado').value;
        const lin = document.getElementById('select_linea').value;
        const sub = document.getElementById('select_sublinea').value;

        if (!prod && !lin) {
            alert("Por favor selecciona un Producto O una Línea para analizar.");
            return;
        }

        // UI Loading
        document.getElementById('btnProcesar').disabled = true;
        document.getElementById('loader').style.display = 'block';
        document.getElementById('resultado-panel').style.display = 'none';

        try {
            const res = await fetch('backend_prediccion.php', {
                method: 'POST',
                body: JSON.stringify({ producto: prod, linea: lin, sublinea: sub })
            });
            const json = await res.json();

            if (json.success) {
                // MOSTRAR DATOS
                document.getElementById('res_cantidad').innerHTML = json.data.prediccion + " <span style='font-size:20px; color:white'>Unidades</span>";
                document.getElementById('res_tendencia').innerText = json.data.tendencia;
                document.getElementById('res_accion').innerText = json.data.accion;
                document.getElementById('resultado-panel').style.display = 'block';
                
                // DIBUJAR GRÁFICO (Si el backend mandó historia)
                if(json.historia) {
                    renderizarGrafico(json.historia, json.data.prediccion);
                }

            } else {
                alert("Error: " + json.error);
            }
        } catch (e) {
            console.error(e);
            alert("Error de conexión");
        } finally {
            document.getElementById('btnProcesar').disabled = false;
            document.getElementById('loader').style.display = 'none';
        }
    }

    // 5. FUNCIÓN PARA PINTAR EL GRÁFICO
    function renderizarGrafico(historia, prediccionFutura) {
        const ctx = document.getElementById('graficoVentas').getContext('2d');
        
        let etiquetas = Object.keys(historia);
        let datos = Object.values(historia);

        // Agregamos el futuro
        etiquetas.push("Próximo Mes 🤖");
        
        // Creamos array para la línea de predicción (null en el pasado)
        let datosPrediccion = new Array(datos.length).fill(null);
        // Conectamos el último punto real con el futuro
        datosPrediccion[datos.length - 1] = datos[datos.length - 1]; 
        datosPrediccion.push(prediccionFutura);

        if (chartInstancia) { chartInstancia.destroy(); }

        chartInstancia = new Chart(ctx, {
            type: 'line',
            data: {
                labels: etiquetas,
                datasets: [
                    {
                        label: 'Ventas Históricas',
                        data: datos,
                        borderColor: '#00d2ff',
                        backgroundColor: 'rgba(0, 210, 255, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 4
                    },
                    {
                        label: 'Predicción IA',
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
                    legend: { labels: { color: 'white' } },
                },
                scales: {
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#a0a0a0' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#a0a0a0' }
                    }
                }
            }
        });
    }
</script>