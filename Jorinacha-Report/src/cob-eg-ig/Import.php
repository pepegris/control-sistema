<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/adm/cob-eg-ig/import.php';

// 1. Evitar timeouts de PHP si el servidor tarda en responder
set_time_limit(0); 

$proceso_activo = false;
$titulo = "";
$script_js = "";
$variant_js = "neo"; 
$error_msg = "";

if (isset($_POST['scripts']) && isset($_POST['clave'])) {
    
    $script = $_POST['scripts'];
    $clave  = $_POST['clave'];
    
    // Capturar checkbox
    $include_old = isset($_POST['old_companies']) ? true : false;
    $isNeo = !$include_old; 
    
    $variant_js = $isNeo ? "neo" : "full";

    if ($clave !== 'N3td0s') { // <--- TU CLAVE
        echo "<script>alert('⛔ Contraseña incorrecta.'); window.location='Import-database.php';</script>";
        exit;
    }

    $inicio_exitoso = false;
    $nombreJobDisplay = $isNeo ? "(Versión Rápida - Neo)" : "(Versión Completa)";

    if ($script == 'backups') {
        $titulo = "Generando Backups $nombreJobDisplay";
        $inicio_exitoso = triggerJob('backups', $isNeo);
    } elseif ($script == 'restore') {
        $titulo = "Restaurando Data $nombreJobDisplay";
        $inicio_exitoso = triggerJob('restore', $isNeo);
    }

    // --- CORRECCIÓN IMPORTANTE ---
    // A veces triggerJob devuelve false porque el Job YA está corriendo (error 22022 de SQL).
    // En lugar de detenernos y mostrar error, forzamos la pantalla de carga 
    // y dejamos que el JavaScript (check_status) decida si está corriendo o no.
    
    $proceso_activo = true; // SIEMPRE mostramos la pantalla de carga
    $script_js = $script; 

    // Solo si quieres guardar un log interno de que "falló el inicio" puedes usar $inicio_exitoso,
    // pero para el usuario, le mostramos el monitor.

} else {
    header('Location: Import-database.php');
    exit;
}
?>

<style>
    body { background-color: #1a1a1a; color: white; font-family: sans-serif; }
    .status-card {
        background-color: #2d3436;
        border-radius: 15px; padding: 40px; max-width: 600px;
        margin: 50px auto; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }
    .percent-box { font-size: 4rem; font-weight: bold; color: #00ff99; margin: 10px 0; }
    .status-detail { font-size: 1.2rem; color: #b2bec3; margin-bottom: 20px; }
    .progress-container { width: 100%; background-color: #444; border-radius: 10px; height: 25px; overflow: hidden; }
    .progress-bar { height: 100%; background-color: #0984e3; width: 0%; transition: width 0.5s; font-size: 0.9rem; line-height: 25px; color: white; font-weight: bold;}
    .result-icon { font-size: 4rem; margin-bottom: 20px; }
    .success { color: #00b894; } .error { color: #d63031; }
</style>

<div class="container">
    <div class="status-card">
        
        <div id="loadingSection">
            <h3 style="color:#74b9ff"><?= $titulo ?></h3>
            <hr style="border-color:#555">
            <div class="percent-box" id="percentDisplay">0%</div>
            <p class="status-detail" id="statusMsg">Verificando estado del servidor...</p>
            <div class="progress-container">
                <div id="progressBar" class="progress-bar"></div>
            </div>
            <p class="small mt-4 text-muted"><i class="fa fa-sync fa-spin"></i> Tiempo Real</p>
        </div>

        <div id="resultSection" style="display: none;">
            <div id="resultIcon" class="result-icon"></div>
            <h2 id="resultTitle"></h2>
            <p id="resultMsg" class="lead"></p>
            <br><a href="Import-database.php" class="btn btn-primary btn-lg">Volver</a>
        </div>

    </div>
</div>

<script>
    // Variables PHP pasadas a JS
    const scriptType = "<?= $script_js ?>";
    const variantType = "<?= $variant_js ?>"; 
    
    const percentDisplay = document.getElementById('percentDisplay');
    const statusMsg = document.getElementById('statusMsg');
    const progressBar = document.getElementById('progressBar');
    const loadingSec = document.getElementById('loadingSection');
    const resultSec = document.getElementById('resultSection');
    const resultIcon = document.getElementById('resultIcon');
    const resultTitle = document.getElementById('resultTitle');
    const resultMsg = document.getElementById('resultMsg');

    // Contador para detectar timeouts si check_status falla
    let errorCount = 0; 

    function checkStatus() {
        fetch(`../../services/adm/cob-eg-ig/check_status.php?script=${scriptType}&variant=${variantType}`)
            .then(response => response.json())
            .then(data => {
                // Reset de errores si responde bien
                errorCount = 0;

                if (data.status === 'ok') {
                    // Actualizar UI
                    percentDisplay.innerText = data.percent + "%";
                    statusMsg.innerText = data.msg;
                    progressBar.style.width = data.percent + "%";
                    progressBar.innerText = data.processed + " / " + data.total;

                    // Si terminó (running = false), mostrar resultado final
                    if (!data.running) {
                        clearInterval(polling);
                        mostrarResultado(data.run_status);
                    }
                } else {
                    // Si el JSON dice status != ok (ej: job no encontrado)
                    // Podríamos mostrar error, pero esperamos unos intentos
                    statusMsg.innerText = "Esperando respuesta del Job...";
                }
            })
            .catch(err => {
                console.error("Error conexión", err);
                errorCount++;
                if(errorCount > 5) { // Si falla 5 veces seguidas
                    statusMsg.innerText = "Perdida conexión con el monitor.";
                    statusMsg.style.color = "orange";
                }
            });
    }

    function mostrarResultado(status) {
        setTimeout(() => {
            loadingSec.style.display = 'none';
            resultSec.style.display = 'block';
            if (status == 1) { 
                resultIcon.innerHTML = '✅'; 
                resultTitle.innerText = '¡Éxito!'; 
                resultTitle.className = 'success';
                resultMsg.innerText = 'Proceso completado correctamente.';
            } else { 
                resultIcon.innerHTML = '❌'; 
                resultTitle.innerText = 'Finalizado'; 
                resultTitle.className = 'error';
                resultMsg.innerText = 'El proceso terminó (Revise logs si hubo error).';
            }
        }, 1000);
    }

    // Iniciar el polling
    const polling = setInterval(checkStatus, 3000);
    checkStatus(); 
</script>

<?php include '../../includes/footer.php'; ?>