<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';
?>

<style>
    /* Estilos Dark Mode */
    body { background-color: #1a1a1a; color: white; font-family: 'Segoe UI', sans-serif; }

    .card-custom {
        background-color: #2c3e50;
        color: white;
        border: 1px solid #444;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        max-width: 500px;
        margin: 40px auto;
        padding: 30px;
    }

    .form-group { margin-bottom: 20px; text-align: left; }
    label { font-weight: bold; margin-bottom: 8px; display: block; color: #ecf0f1; }
    
    select, input[type="password"] {
        width: 100%; padding: 12px; border-radius: 5px; border: 1px solid #555;
        background-color: #34495e; color: white; font-size: 1rem;
    }
    
    .btn-action {
        width: 100%; padding: 12px; font-size: 1.1rem; border-radius: 5px; cursor: pointer;
        background-color: #27ae60; color: white; border: none; transition: background 0.3s;
    }
    .btn-action:hover { background-color: #219150; }

    /* Checkbox personalizado */
    .checkbox-container {
        display: flex;
        align-items: center;
        background-color: #34495e;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #555;
        margin-top: 5px;
    }
    .checkbox-container input { width: 20px; height: 20px; margin-right: 10px; cursor: pointer; }
    .checkbox-container label { margin: 0; cursor: pointer; color: #aaa; font-weight: normal; }
    .checkbox-container input:checked + label { color: #fff; font-weight: bold; }

    /* PANTALLA DE CARGA */
    #loadingOverlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.9); z-index: 9999; text-align: center; padding-top: 15%;
    }
    .spinner {
        width: 60px; height: 60px; border: 6px solid #333; border-top: 6px solid #00ff99;
        border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px auto;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

<div id="loadingOverlay">
    <div class="spinner"></div>
    <h2 style="color:#00ff99;">Iniciando Job SQL...</h2>
    <p style="color:#fff; font-weight:bold;">Por favor espere, redirigiendo...</p>
</div>

<div class="container">
    <center>
        <h1 style="margin-top: 30px;">Panel de Integración</h1>
        <p class="text-muted">Gestión de Backups y Restauración (SQL Server)</p>
    </center>

    <div class="card-custom">
        <form action="Import.php" method="POST" id="backupForm">
            
            <legend class="text-center" style="border-bottom: 1px solid #555; padding-bottom: 15px; margin-bottom: 25px;">
                Seleccione Operación
            </legend>

            <div class="form-group">
                <label for="scripts"><i class="fa fa-cogs"></i> Tipo de Proceso:</label>
                <select name="scripts" id="scripts" required>
                    <option value="" selected disabled>-- Seleccione una opción --</option>
                    <option value="backups">📤 1. Generar Backups (Origen)</option>
                    <option value="restore">📥 2. Restaurar Data (Destino)</option>
                </select>
            </div>

            <!-- CHECKBOX DE EMPRESAS VIEJAS -->
            <div class="form-group">
                <div class="checkbox-container">
                    <input type="checkbox" name="old_companies" id="old_companies" value="1">
                    <label for="old_companies">¿Incluir Empresas Viejas/Inactivas?</label>
                </div>
                <small class="text-muted" style="font-size:0.8em; margin-left: 5px;">
                    <i class="fa fa-info-circle"></i> Si no marca esto, se ejecutará el proceso <b>"Neo"</b> (Más rápido, solo ~28 tiendas).
                </small>
            </div>

            <div class="form-group">
                <label for="clave"><i class="fa fa-lock"></i> Contraseña de Admin:</label>
                <input type="password" name="clave" id="clave" placeholder="Ingrese clave para autorizar" required>
            </div>

            <br>
            <button type="button" onclick="confirmarEjecucion()" class="btn-action">
                <i class="fa fa-play-circle"></i> Ejecutar Proceso
            </button>

        </form>
    </div>
</div>

<script>
    function confirmarEjecucion() {
        var script = document.getElementById('scripts').value;
        var clave = document.getElementById('clave').value;
        var old = document.getElementById('old_companies').checked;

        if (script == "") {
            alert("⚠️ Error: Debe seleccionar qué proceso desea realizar.");
            return;
        }
        if (clave == "") {
            alert("⚠️ Error: Debe ingresar la contraseña de seguridad.");
            return;
        }

        var tipoProceso = old ? "COMPLETO (Lento)" : "NEO (Rápido)";
        var mensaje = "";

        if (script == 'backups') {
            mensaje = "CONFIRMACIÓN DE BACKUP:\n\n" +
                      "Modo: " + tipoProceso + "\n" +
                      "¿Está seguro de generar los respaldos en el servidor 172.16.1.39?";
        } else if (script == 'restore') {
            mensaje = "⛔ ADVERTENCIA DE SEGURIDAD ⛔\n\n" +
                      "Modo: " + tipoProceso + "\n" +
                      "¿Está seguro de RESTAURAR la base de datos en 172.16.1.19?\nEsto borrará la data actual.";
        }

        if (confirm(mensaje)) {
            document.getElementById('loadingOverlay').style.display = 'block';
            setTimeout(function() {
                document.getElementById('backupForm').submit(); 
            }, 500);
        }
    }
</script>

<?php include '../../includes/footer.php'; ?>