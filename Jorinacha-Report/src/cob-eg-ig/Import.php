<?php
require '../../includes/log.php';
include '../../includes/header.php';
require '../../services/sqlserver.php'; // Necesario para obtener la hora y conexión
include '../../services/adm/cob-eg-ig/import.php';

// 1. Evitar timeouts
set_time_limit(0); 

$proceso_activo = false;
$titulo = "";
$script_js = "";
$variant_js = "neo"; 
$error_msg = "";
$startTimestamp = time(); // Valor por defecto (hora web) por si falla SQL

if (isset($_POST['scripts']) && isset($_POST['clave'])) {
    
    $script = $_POST['scripts'];
    $clave  = $_POST['clave'];
    $include_old = isset($_POST['old_companies']) ? true : false;
    $isNeo = !$include_old; 
    $variant_js = $isNeo ? "neo" : "full";

    if ($clave !== 'N3td0s') { 
        echo "<script>alert('⛔ Contraseña incorrecta.'); window.location='Import-database.php';</script>";
        exit;
    }

    // =================================================================
    // PASO CRÍTICO: CAPTURAR HORA DEL SERVIDOR SQL (.39)
    // =================================================================
    // Consultamos la hora exacta de SQL Server ANTES de iniciar el proceso.
    // Esto será nuestro "Punto Cero".
    if (isset($conn) && $conn) {
        $sqlTime = "SELECT GETDATE() as server_time";
        $stmtTime = sqlsrv_query($conn, $sqlTime);
        if ($stmtTime && $rowTime = sqlsrv_fetch_array($stmtTime, SQLSRV_FETCH_ASSOC)) {
            // Convertimos el objeto DateTime de SQL a Timestamp Unix (segundos)
            // Le restamos 5 segundos por seguridad (latencia de red)
            $startTimestamp = $rowTime['server_time']->getTimestamp() - 5;
        }
    }
    // =================================================================

    $inicio_exitoso = false;
    $nombreJobDisplay = $isNeo ? "(Versión Rápida - Neo)" : "(Versión Completa)";

    if ($script == 'backups') {
        $titulo = "Generando Backups $nombreJobDisplay";
        $inicio_exitoso = triggerJob('backups', $isNeo);
    } elseif ($script == 'restore') {
        $titulo = "Restaurando Data $nombreJobDisplay";
        $inicio_exitoso = triggerJob('restore', $isNeo);
    }

    // Forzamos la pantalla de carga siempre
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
            <p class="status-detail" id="statusMsg">Sincronizando con SQL Server...</p>
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
    const scriptType = "<?= $script_js ?>";
    
    // AQUI USAMOS LA HORA EXACTA QUE NOS DIO EL SERVIDOR SQL (.39)
    const startTime = "<?= $startTimestamp ?>"; 

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

        // Enviamos la hora del servidor SQL (?since=...)
        fetch(`../../services/adm/cob-eg-ig/check_files.php?since=${startTime}`)
            .then(response => response.json())
            .then(data => {
                console.log("Intento " + intentos, data);

                if (data.status === 'ok') {
                    percentDisplay.innerText = data.percent + "%";
                    statusMsg.innerText = data.msg;
                    progressBar.style.width = data.percent + "%";
                    progressBar.innerText = data.processed + " / " + data.total;

                    // Finalizar si llega al 100%
                    if (data.processed >= data.total) {
                        clearInterval(polling);
                        mostrarResultado(1);
                    }
                }
            })
            .catch(err => console.error("Error conexión", err));
    }

    function mostrarResultado(status) {
        setTimeout(() => {
            loadingSec.style.display = 'none';
            resultSec.style.display = 'block';
            resultIcon.innerHTML = '✅'; 
            resultTitle.innerText = '¡Backups Listos!'; 
            resultTitle.className = 'success';
            resultMsg.innerText = 'El proceso ha finalizado correctamente.';
        }, 1000);
    }

    const polling = setInterval(checkStatus, 3000);
    checkStatus(); 
</script>

<?php include '../../includes/footer.php'; ?>