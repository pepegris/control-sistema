<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/adm/cob-eg-ig/import.php';

$proceso_activo = false;
$titulo = "";
$script_js = "";
$error_msg = "";

// Validación inicial (igual que antes)
if (isset($_POST['scripts']) && isset($_POST['clave'])) {
    $script = $_POST['scripts'];
    $clave  = $_POST['clave'];

    if ($clave !== 'N3td0s') {
        echo "<script>alert('⛔ Contraseña incorrecta.'); window.location='Import-database.php';</script>";
        exit;
    }

    $inicio_exitoso = false;
    if ($script == 'backups') {
        $titulo = "Generando Backups (.BAK en Z:\\)";
        $inicio_exitoso = getImport();
    } elseif ($script == 'restore') {
        $titulo = "Restaurando Data (Servidor .19)";
        $inicio_exitoso = getRestore();
    }

    if ($inicio_exitoso) {
        $proceso_activo = true;
        $script_js = $script; 
    } else {
        $error_msg = "No se pudo iniciar el Job en SQL Server.";
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
    
    /* Porcentaje Grande */
    .percent-box { font-size: 4rem; font-weight: bold; color: #00ff99; margin: 10px 0; }
    .status-detail { font-size: 1.2rem; color: #b2bec3; margin-bottom: 20px; }
    
    /* Barra */
    .progress-container { width: 100%; background-color: #444; border-radius: 10px; height: 25px; overflow: hidden; }
    .progress-bar { height: 100%; background-color: #0984e3; width: 0%; transition: width 0.5s; font-size: 0.9rem; line-height: 25px; color: white; font-weight: bold;}
    
    .result-icon { font-size: 4rem; margin-bottom: 20px; }
</style>

<div class="container">
    <div class="status-card">
        
        <?php if ($proceso_activo): ?>
            <div id="loadingSection">
                <h3 style="color:#74b9ff"><?= $titulo ?></h3>
                <hr style="border-color:#555">
                
                <div class="percent-box" id="percentDisplay">0%</div>
                
                <p class="status-detail" id="statusMsg">Conectando con el servidor...</p>
                
                <div class="progress-container">
                    <div id="progressBar" class="progress-bar"></div>
                </div>
                
                <p class="small mt-4 text-muted">
                    <i class="fa fa-sync fa-spin"></i> Monitoreando en tiempo real...
                </p>
            </div>

            <div id="resultSection" style="display: none;">
                <div id="resultIcon" class="result-icon"></div>
                <h2 id="resultTitle"></h2>
                <p id="resultMsg" class="lead"></p>
                <br>
                <a href="Import-database.php" class="btn btn-primary btn-lg">Volver al Menú</a>
            </div>

        <?php else: ?>
            <h2 style="color:red">Error</h2>
            <p><?= $error_msg ?></p>
            <a href="Import-database.php" class="btn btn-light">Volver</a>
        <?php endif; ?>

    </div>
</div>

<?php if ($proceso_activo): ?>
<script>
    const scriptType = "<?= $script_js ?>";
    const percentDisplay = document.getElementById('percentDisplay');
    const statusMsg = document.getElementById('statusMsg');
    const progressBar = document.getElementById('progressBar');
    
    const loadingSec = document.getElementById('loadingSection');
    const resultSec = document.getElementById('resultSection');
    const resultIcon = document.getElementById('resultIcon');
    const resultTitle = document.getElementById('resultTitle');
    const resultMsg = document.getElementById('resultMsg');

    function checkStatus() {
        fetch(`../../services/adm/cob-eg-ig/check_status.php?script=${scriptType}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'ok') {
                    
                    // ACTUALIZAR BARRA Y TEXTOS EN TIEMPO REAL
                    let percent = data.percent;
                    percentDisplay.innerText = percent + "%";
                    statusMsg.innerText = data.msg;
                    progressBar.style.width = percent + "%";
                    progressBar.innerText = data.processed + " / " + data.total;

                    if (!data.running) {
                        // EL JOB TERMINÓ
                        clearInterval(polling);
                        mostrarResultado(data.run_status);
                    }
                }
            })
            .catch(err => console.error("Error conexión", err));
    }

    function mostrarResultado(status) {
        setTimeout(() => { // Pequeña pausa para ver el 100%
            loadingSec.style.display = 'none';
            resultSec.style.display = 'block';

            if (status == 1) { 
                resultIcon.innerHTML = '✅';
                resultTitle.innerText = '¡Operación Exitosa!';
                resultTitle.style.color = '#00ff99';
                resultMsg.innerText = 'Todos las bases de datos fueron procesadas correctamente.';
            } else { 
                resultIcon.innerHTML = '❌';
                resultTitle.innerText = 'Proceso con Errores';
                resultTitle.style.color = '#ff4444';
                resultMsg.innerText = 'El Job de SQL se detuvo inesperadamente o falló.';
            }
        }, 1000);
    }

    // Consultar cada 3 segundos
    const polling = setInterval(checkStatus, 3000);
    checkStatus(); 
</script>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>