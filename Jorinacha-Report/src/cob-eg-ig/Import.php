<?php
require '../../includes/log.php';
include '../../includes/header.php';
// Nota: No incluyo sqlserver.php general para evitar conflictos de conexión, 
// import.php maneja sus propias conexiones a MSDB.
include '../../services/adm/cob-eg-ig/import.php';

$proceso_activo = false;
$titulo = "";
$script_js = "";

if (isset($_POST['scripts'])) {
    
    $script = $_POST['scripts'];
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
        $script_js = $script; // Pasamos 'backups' o 'restore' al JS
    } else {
        echo "<script>alert('Error al intentar iniciar el Job en SQL Server.'); window.location='Import-database.php';</script>";
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
    
    /* Barra de progreso animada */
    .progress-container { width: 100%; background-color: #636e72; border-radius: 5px; height: 10px; margin-top: 20px; overflow: hidden; }
    .progress-bar { height: 100%; background-color: #0984e3; width: 0%; transition: width 0.5s; }
    .progress-striped {
        background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);
        background-size: 1rem 1rem;
        animation: progress-bar-stripes 1s linear infinite;
    }
    @keyframes progress-bar-stripes { 0% { background-position: 1rem 0; } 100% { background-position: 0 0; } }

    .result-icon { font-size: 4rem; margin-bottom: 20px; }
    .success { color: #00b894; }
    .error { color: #d63031; }
</style>

<div class="container">
    <div class="status-card">
        
        <div id="loadingSection">
            <div class="spinner"></div>
            <h2><?= $titulo ?></h2>
            <p class="status-text">El servidor está procesando la solicitud...</p>
            
            <div class="timer" id="cronometro">00:00:00</div>
            
            <div class="progress-container">
                <div id="progressBar" class="progress-bar progress-striped" style="width: 100%"></div>
            </div>
            <p class="small mt-3 text-muted">No cierre esta ventana.</p>
        </div>

        <div id="resultSection" style="display: none;">
            <div id="resultIcon" class="result-icon"></div>
            <h2 id="resultTitle"></h2>
            <p id="resultMsg" class="lead"></p>
            <br>
            <a href="Import-database.php" class="btn btn-primary btn-lg">Volver al Menú</a>
        </div>

    </div>
</div>

<?php if ($proceso_activo): ?>
<script>
    const scriptType = "<?= $script_js ?>"; // 'backups' o 'restore'
    const timerDisplay = document.getElementById('cronometro');
    const loadingSec = document.getElementById('loadingSection');
    const resultSec = document.getElementById('resultSection');
    const resultIcon = document.getElementById('resultIcon');
    const resultTitle = document.getElementById('resultTitle');
    const resultMsg = document.getElementById('resultMsg');

    // Función que pregunta al servidor el estado
    function checkStatus() {
        fetch(`../../services/adm/cob-eg-ig/check_status.php?script=${scriptType}`)
            .then(response => response.json())
            .then(data => {
                
                if (data.status === 'ok') {
                    
                    if (data.running) {
                        // SI SIGUE CORRIENDO: Actualizamos el reloj
                        timerDisplay.innerText = data.time_elapsed;
                    } else {
                        // SI TERMINÓ: Detenemos el intervalo y mostramos resultado
                        clearInterval(polling);
                        mostrarResultado(data.run_status);
                    }
                } else {
                    console.error("Error API:", data.message);
                }
            })
            .catch(err => console.error("Fallo de red", err));
    }

    function mostrarResultado(status) {
        loadingSec.style.display = 'none';
        resultSec.style.display = 'block';

        if (status == 1) { // 1 = Éxito en SQL Agent
            resultIcon.innerHTML = '✅';
            resultIcon.className = 'result-icon success';
            resultTitle.innerText = '¡Proceso Finalizado!';
            resultTitle.className = 'success';
            resultMsg.innerText = 'La base de datos se procesó correctamente.';
        } else { // 0 = Fallo, 3 = Cancelado
            resultIcon.innerHTML = '❌';
            resultIcon.className = 'result-icon error';
            resultTitle.innerText = 'Ocurrió un Error';
            resultTitle.className = 'error';
            resultMsg.innerText = 'El Job de SQL Server reportó un fallo. Revise los logs del servidor.';
        }
    }

    // Ejecutar checkStatus cada 3 segundos
    const polling = setInterval(checkStatus, 3000);
    checkStatus(); // Primera llamada inmediata
</script>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>