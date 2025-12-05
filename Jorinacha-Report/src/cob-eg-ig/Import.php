<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/adm/cob-eg-ig/import.php';

set_time_limit(0); 

$proceso_activo = false;
$titulo = "";
$script_js = "";
$variant_js = "neo"; 
// Iniciamos en 0 por si acaso
$lastBackupID = 0; 

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
    // PASO 1: OBTENER EL ÚLTIMO ID DE BACKUP (LA MARCA EXACTA)
    // =================================================================
    $serverName = "172.16.1.39";
    $connectionInfo = array("Database" => "msdb", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
    $connID = sqlsrv_connect($serverName, $connectionInfo);

    if ($connID) {
        // Pedimos el ID más alto que existe AHORA.
        // Todo lo que se cree después tendrá un ID mayor.
        $sqlID = "SELECT ISNULL(MAX(backup_set_id), 0) as max_id FROM msdb.dbo.backupset";
        $stmtID = sqlsrv_query($connID, $sqlID);
        if ($stmtID && $rowID = sqlsrv_fetch_array($stmtID, SQLSRV_FETCH_ASSOC)) {
            $lastBackupID = $rowID['max_id'];
        }
        sqlsrv_close($connID);
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

    $proceso_activo = true; 
    $script_js = $script; 

} else {
    header('Location: Import-database.php');
    exit;
}
?>

<div class="container">
    <div class="status-card">
        <div id="loadingSection">
            <h3 style="color:#74b9ff"><?= $titulo ?></h3>
            <hr style="border-color:#555">
            <div class="percent-box" id="percentDisplay">0%</div>
            <p class="status-detail" id="statusMsg">Inicializando...</p>
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
    
    // PASAMOS EL ID AL JAVASCRIPT
    const startID = "<?= $lastBackupID ?>"; 

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

        // Enviamos el ID (?last_id=...)
        fetch(`../../services/adm/cob-eg-ig/check_files.php?last_id=${startID}`)
            .then(response => response.json())
            .then(data => {
                console.log("Intento " + intentos, data);

                if (data.status === 'ok') {
                    percentDisplay.innerText = data.percent + "%";
                    statusMsg.innerText = data.msg;
                    progressBar.style.width = data.percent + "%";
                    progressBar.innerText = data.processed + " / " + data.total;

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
            resultMsg.innerText = 'Proceso finalizado correctamente.';
        }, 1000);
    }

    const polling = setInterval(checkStatus, 3000);
    checkStatus(); 
</script>

<?php include '../../includes/footer.php'; ?>