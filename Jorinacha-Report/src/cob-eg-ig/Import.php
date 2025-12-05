<?php
require '../../includes/log.php';
include '../../includes/header.php';
// Incluimos el archivo que tiene la lógica del Job y el Monitor
include '../../services/adm/cob-eg-ig/import.php';

$proceso_activo = false;
$titulo = "";
$script_js = "";
$error_msg = "";

if (isset($_POST['scripts']) && isset($_POST['clave'])) {
    
    $script = $_POST['scripts'];
    $clave  = $_POST['clave'];

    // --- 1. VALIDACIÓN DE SEGURIDAD ---
    if ($clave !== 'N3td0s') {
        echo "<script>
                alert('⛔ ACCESO DENEGADO: La contraseña es incorrecta.'); 
                window.location='Import-database.php';
              </script>";
        exit; // Detenemos todo aquí
    }
    // ----------------------------------

    $inicio_exitoso = false;

    if ($script == 'backups') {
        $titulo = "Generando Backups (Origen 172.16.1.39)";
        $inicio_exitoso = getImport();
    } elseif ($script == 'restore') {
        $titulo = "Restaurando Data (Destino 172.16.1.19)";
        $inicio_exitoso = getRestore();
    }

    if ($inicio_exitoso) {
        $proceso_activo = true;
        $script_js = $script; 
    } else {
        $error_msg = "No se pudo iniciar el Job en SQL Server. Verifique la conexión.";
    }

} else {
    header('Location: Import-database.php');
    exit;
}
?>

<style>
    body { background-color: #1a1a1a; color: white; font-family: sans-serif; }
    .status-card {
        background-color: #2d3436;
        border-radius: 15px;
        padding: 40px;
        max-width: 600px;
        margin: 50px auto;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }
    .spinner {
        width: 60px; height: 60px;
        border: 6px solid #444;
        border-top: 6px solid #0984e3;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px auto;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    
    .timer { font-size: 3rem; font-weight: bold; font-family: monospace; color: #dfe6e9; margin: 20px 0; }
    .status-text { font-size: 1.2rem; color: #b2bec3; }
    
    .progress-container { width: 100%; background-color: #636e72; border-radius: 5px; height: 10px; margin-top: 20px; overflow: hidden; }
    .progress-bar { height: 100%; background-color: #0984e3; width: 0%; transition: width 0.5s; }
    
    .result-icon { font-size: 4rem; margin-bottom: 20px; }
    .success { color: #00b894; }
    .error { color: #d63031; }
</style>

<div class="container">
    <div class="status-card">
        
        <?php if ($proceso_activo): ?>
            <div id="loadingSection">
                <div class="spinner"></div>
                <h2><?= $titulo ?></h2>
                <p class="status-text">El servidor está procesando la solicitud...</p>
                
                <div class="timer" id="cronometro">00:00:00</div>
                
                <div class="progress-container">
                    <div id="progressBar" class="progress-bar" style="width: 5%"></div>
                </div>
                <p class="small mt-3 text-muted">No cierre esta ventana hasta finalizar.</p>
            </div>

            <div id="resultSection" style="display: none;">
                <div id="resultIcon" class="result-icon"></div>
                <h2 id="resultTitle"></h2>
                <p id="resultMsg" class="lead"></p>
                <br>
                <a href="Import-database.php" class="btn btn-primary btn-lg">Volver al Menú</a>
            </div>

        <?php else: ?>
            <h2 class="text-danger">Error de Ejecución</h2>
            <p><?= $error_msg ?></p>
            <a href="Import-database.php" class="btn btn-light">Volver</a>
        <?php endif; ?>

    </div>
</div>

<?php if ($proceso_activo): ?>
<script>
    const scriptType = "<?= $script_js ?>";
    const timerDisplay = document.getElementById('cronometro');
    const loadingSec = document.getElementById('loadingSection');
    const resultSec = document.getElementById('resultSection');
    const resultIcon = document.getElementById('resultIcon');
    const resultTitle = document.getElementById('resultTitle');
    const resultMsg = document.getElementById('resultMsg');
    const progressBar = document.getElementById('progressBar');

    function checkStatus() {
        fetch(`../../services/adm/cob-eg-ig/check_status.php?script=${scriptType}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'ok') {
                    if (data.running) {
                        timerDisplay.innerText = data.time_elapsed;
                        // Simular movimiento en la barra para que se vea vivo
                        let currentWidth = parseFloat(progressBar.style.width) || 5;
                        if(currentWidth < 90) progressBar.style.width = (currentWidth + 1) + "%";
                    } else {
                        clearInterval(polling);
                        mostrarResultado(data.run_status);
                    }
                }
            })
            .catch(err => console.error("Error red", err));
    }

    function mostrarResultado(status) {
        loadingSec.style.display = 'none';
        resultSec.style.display = 'block';

        if (status == 1) { 
            resultIcon.innerHTML = '✅';
            resultIcon.className = 'result-icon success';
            resultTitle.innerText = '¡Proceso Finalizado!';
            resultTitle.className = 'success';
            resultMsg.innerText = 'La operación se completó exitosamente.';
        } else { 
            resultIcon.innerHTML = '❌';
            resultIcon.className = 'result-icon error';
            resultTitle.innerText = 'Error en el Proceso';
            resultTitle.className = 'error';
            resultMsg.innerText = 'El Job falló o fue cancelado. Revise el servidor SQL.';
        }
    }

    const polling = setInterval(checkStatus, 3000);
    checkStatus(); 
</script>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>