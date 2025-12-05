<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/adm/cob-eg-ig/import.php';

// 1. Evitar timeouts de PHP
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

    // --- FORZAR PANTALLA DE CARGA ---
    // Incluso si triggerJob devuelve false (porque ya corre), mostramos la carga
    // y dejamos que check_files.php nos diga el progreso real.
    $proceso_activo = true; 
    $script_js = $script; 

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
            <p class="status-detail" id="statusMsg">Escaneando archivos en disco...</p>
            <div class="progress-container">
                <div id="progressBar" class="progress-bar"></div>
            </div>
            <p class="small mt-4 text-muted"><i class="fa fa-sync fa-spin"></i> Tiempo Real (Archivos .BAK)</p>
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
    
    const percentDisplay = document.getElementById('percentDisplay');
    const statusMsg = document.getElementById('statusMsg');
    const progressBar = document.getElementById('progressBar');
    const loadingSec = document.getElementById('loadingSection');
    const resultSec = document.getElementById('resultSection');
    const resultIcon = document.getElementById('resultIcon');
    const resultTitle = document.getElementById('resultTitle');
    const resultMsg = document.getElementById('resultMsg');

    let intentos = 0;

    function checkStatus() {
        intentos++;

        // AQUI ESTÁ EL CAMBIO: Usamos check_files.php
        fetch(`../../services/adm/cob-eg-ig/check_files.php`)
            .then(response => response.json())
            .then(data => {
                console.log("Intento " + intentos, data);

                if (data.status === 'error') {
                    // Si check_files da error (ej. no encuentra Z:), lo mostramos
                    statusMsg.innerText = "Error: " + data.msg;
                    statusMsg.style.color = "orange";
                    return;
                }

                if (data.status === 'ok') {
                    // Actualizar UI con datos de archivos
                    percentDisplay.innerText = data.percent + "%";
                    statusMsg.innerText = data.msg;
                    progressBar.style.width = data.percent + "%";
                    progressBar.innerText = data.processed + " / " + data.total;

                    // Si terminó (100% o running false)
                    if (!data.running || data.percent >= 100) {
                        clearInterval(polling);
                        mostrarResultado(1); // Éxito
                    }
                }
            })
            .catch(err => {
                console.error("Error conexión", err);
                statusMsg.innerText = "Error conectando con el monitor de archivos.";
                statusMsg.style.color = "red";
            });
    }

    function mostrarResultado(status) {
        setTimeout(() => {
            loadingSec.style.display = 'none';
            resultSec.style.display = 'block';
            
            // Asumimos éxito si check_files terminó
            resultIcon.innerHTML = '✅'; 
            resultTitle.innerText = '¡Backups Listos!'; 
            resultTitle.className = 'success';
            resultMsg.innerText = 'Se han generado todos los archivos .BAK correctamente.';
        }, 1000);
    }

    // Iniciar el polling cada 2 segundos
    const polling = setInterval(checkStatus, 2000);
    checkStatus(); 
</script>

<?php include '../../includes/footer.php'; ?>