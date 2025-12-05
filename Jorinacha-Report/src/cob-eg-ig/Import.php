<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/adm/cob-eg-ig/import.php';

// 1. Evitar que PHP corte la conexión si tarda mucho
set_time_limit(0); 

$proceso_activo = false;
$titulo = "";
$script_js = "";
$monitorParam = ""; 
$monitorURL = "";   

if (isset($_POST['scripts']) && isset($_POST['clave'])) {
    
    $script = $_POST['scripts'];
    $clave  = $_POST['clave'];
    $include_old = isset($_POST['old_companies']) ? true : false;
    $isNeo = !$include_old; 
    
    if ($clave !== 'N3td0s') { 
        echo "<script>alert('⛔ Contraseña incorrecta.'); window.location='Import-database.php';</script>";
        exit;
    }

    // =================================================================
    // PREPARAR EL MONITOR (Lógica Híbrida: ID vs HORA)
    // =================================================================
    
    if ($script == 'backups') {
        // --- CASO 1: BACKUPS (Servidor 172.16.1.39) ---
        // Estrategia: Usar ID (backup_set_id). Es perfecto para creaciones.
        
        $serverIP = "172.16.1.39";
        $monitorURL = "../../services/adm/cob-eg-ig/check_files.php";
        
        // Conectamos para buscar el último ID
        $connectionInfo = array("Database" => "msdb", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $connMonitor = sqlsrv_connect($serverIP, $connectionInfo);
        
        $lastID = 0;
        if ($connMonitor) {
            $sql = "SELECT ISNULL(MAX(backup_set_id), 0) as dato FROM msdb.dbo.backupset";
            $stmt = sqlsrv_query($connMonitor, $sql);
            if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $lastID = $row['dato'];
            }
            sqlsrv_close($connMonitor);
        }
        
        // Parámetro para JS: ?last_id=5000
        $monitorParam = "last_id=" . $lastID; 

    } elseif ($script == 'restore') {
        // --- CASO 2: RESTORE (Servidor 172.16.1.19 o el de Integración) ---
        // Estrategia: Usar HORA (restore_date).
        // NOTA: Si tu integración está en la .39, cambia la IP aquí abajo.
        
        $serverIP = "172.16.1.19"; // <--- IP DE DONDE SE RESTAURA
        $monitorURL = "../../services/adm/cob-eg-ig/check_restore.php";
        
        // Conectamos para buscar la HORA exacta del servidor SQL
        $connectionInfo = array("Database" => "msdb", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $connMonitor = sqlsrv_connect($serverIP, $connectionInfo);
        
        $serverTime = date('Y-m-d H:i:s'); // Hora web por defecto
        if ($connMonitor) {
            $sql = "SELECT CONVERT(varchar, GETDATE(), 120) as dato";
            $stmt = sqlsrv_query($connMonitor, $sql);
            if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $serverTime = $row['dato'];
            }
            sqlsrv_close($connMonitor);
        }
        
        // Parámetro para JS: ?since=2025-12-05 15:30:00
        $monitorParam = "since=" . urlencode($serverTime);
    }

    // =================================================================

    $inicio_exitoso = false;
    $nombreJobDisplay = $isNeo ? "(Versión Rápida - Neo)" : "(Versión Completa)";

    // Ejecutar el Job Real
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

<!-- ======================================================= -->
<!-- ESTILOS CSS RECUPERADOS -->
<!-- ======================================================= -->
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
    
    .percent-box { 
        font-size: 4rem; 
        font-weight: bold; 
        color: #00ff99; 
        margin: 10px 0; 
    }
    
    .status-detail { 
        font-size: 1.2rem; 
        color: #b2bec3; 
        margin-bottom: 20px; 
    }
    
    .progress-container { 
        width: 100%; 
        background-color: #444; 
        border-radius: 10px; 
        height: 25px; 
        overflow: hidden; 
    }
    
    .progress-bar { 
        height: 100%; 
        background-color: #0984e3; 
        width: 0%; 
        transition: width 0.5s; 
        font-size: 0.9rem; 
        line-height: 25px; 
        color: white; 
        font-weight: bold;
    }
    
    .result-icon { 
        font-size: 4rem; 
        margin-bottom: 20px; 
    }
    
    .success { color: #00b894; } 
    .error { color: #d63031; }
    
    .btn-primary {
        background-color: #0984e3;
        border: none;
        padding: 10px 20px;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 1.1em;
    }
    .btn-primary:hover { background-color: #74b9ff; }
</style>

<div class="container">
    <div class="status-card">
        
        <!-- PANTALLA DE CARGA -->
        <div id="loadingSection">
            <h3 style="color:#74b9ff"><?= $titulo ?></h3>
            <hr style="border-color:#555">
            
            <div class="percent-box" id="percentDisplay">0%</div>
            <p class="status-detail" id="statusMsg">Conectando...</p>
            
            <div class="progress-container">
                <div id="progressBar" class="progress-bar"></div>
            </div>
            
            <p class="small mt-4 text-muted" style="color:#aaa;">
                <i class="fa fa-sync fa-spin"></i> Tiempo Real (SQL Monitor)
            </p>
        </div>

        <!-- PANTALLA DE RESULTADO -->
        <div id="resultSection" style="display: none;">
            <div id="resultIcon" class="result-icon"></div>
            <h2 id="resultTitle"></h2>
            <p id="resultMsg" class="lead"></p>
            <br><br>
            <a href="Import-database.php" class="btn btn-primary">Volver al Menú</a>
        </div>

    </div>
</div>

<!-- ======================================================= -->
<!-- JAVASCRIPT DINÁMICO -->
<!-- ======================================================= -->
<script>
    // Inyectamos las variables de PHP
    const monitorURL = "<?= $monitorURL ?>"; 
    const monitorParams = "<?= $monitorParam ?>"; // Será "last_id=..." o "since=..."

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

        // La URL se genera sola dependiendo de si es backup o restore
        fetch(`${monitorURL}?${monitorParams}`)
            .then(response => response.json())
            .then(data => {
                console.log("Intento " + intentos, data);

                if (data.status === 'ok') {
                    // Actualizar Barra y Textos
                    percentDisplay.innerText = data.percent + "%";
                    statusMsg.innerText = data.msg;
                    progressBar.style.width = data.percent + "%";
                    progressBar.innerText = data.processed + " / " + data.total;

                    // Finalizar si llegamos al total esperado (18)
                    // O si SQL dice explícitamente que terminó y ya tenemos todo
                    if (data.processed >= data.total) {
                        clearInterval(polling);
                        mostrarResultado(1);
                    }
                }
            })
            .catch(err => {
                console.error("Error conexión", err);
                statusMsg.innerText = "Procesando...";
            });
    }

    function mostrarResultado(status) {
        setTimeout(() => {
            loadingSec.style.display = 'none';
            resultSec.style.display = 'block';
            
            resultIcon.innerHTML = '✅'; 
            resultTitle.innerText = '¡Proceso Finalizado!'; 
            resultTitle.className = 'success';
            resultMsg.innerText = 'La operación se completó exitosamente.';
        }, 1000);
    }

    // Consultar cada 3 segundos
    const polling = setInterval(checkStatus, 3000);
    checkStatus(); 
</script>

<?php include '../../includes/footer.php'; ?>