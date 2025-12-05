<?php
require '../../includes/log.php';
include '../../includes/header.php';
// Agregamos la conexión SQL Server explícita para poder llamar al SP de limpieza
require '../../services/sqlserver.php'; 
include '../../services/adm/cob-eg-ig/import.php';

$proceso_activo = false;
$titulo = "";
$script_js = "";
$variant_js = "neo"; // Por defecto Neo
$error_msg = "";

// Aumentamos el tiempo de espera de PHP para que no se corte mientras limpia
set_time_limit(0); 

if (isset($_POST['scripts']) && isset($_POST['clave'])) {
    
    $script = $_POST['scripts'];
    $clave  = $_POST['clave'];
    
    // Capturar checkbox
    $include_old = isset($_POST['old_companies']) ? true : false;
    $isNeo = !$include_old; 
    
    $variant_js = $isNeo ? "neo" : "full";

    if ($clave !== 'N3td0s') {
        echo "<script>alert('⛔ Contraseña incorrecta.'); window.location='Import-database.php';</script>";
        exit;
    }

    $inicio_exitoso = false;
    $nombreJobDisplay = $isNeo ? "(Versión Rápida - Neo)" : "(Versión Completa)";

    if ($script == 'backups') {
        $titulo = "Generando Backups $nombreJobDisplay";
        
        // =================================================================
        // BLOQUE NUEVO: EJECUTAR LIMPIEZA PREVIA (Delete .BAK)
        // =================================================================
        // Esto pausará la carga de la página hasta que el Job DELETE termine.
        if (isset($conn) && $conn) {
            // Ejecutamos el SP que espera a que termine el borrado
            $sql_clean = "EXEC [SISTEMAS].[dbo].[sp_GestionarBackups] @EjecutarLimpieza = 1";
            $stmt_clean = sqlsrv_query($conn, $sql_clean);
            
            if ($stmt_clean === false) {
                // Opcional: Manejar error si la limpieza falla, 
                // o dejar que continue bajo riesgo.
                // die(print_r(sqlsrv_errors(), true));
            }
        }
        // =================================================================

        // Ahora que ya se borró todo, lanzamos el backup original
        // Esto mantiene tu barra de progreso funcionando
        $inicio_exitoso = triggerJob('backups', $isNeo);

    } elseif ($script == 'restore') {
        $titulo = "Restaurando Data $nombreJobDisplay";
        $inicio_exitoso = triggerJob('restore', $isNeo);
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
        
        <?php if ($proceso_activo): ?>
            <div id="loadingSection">
                <h3 style="color:#74b9ff"><?= $titulo ?></h3>
                <hr style="border-color:#555">
                <p class="small text-success">✅ Limpieza previa completada</p>
                
                <div class="percent-box" id="percentDisplay">0%</div>
                <p class="status-detail" id="statusMsg">Iniciando Backup...</p>
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
    const variantType = "<?= $variant_js ?>"; 
    
    const percentDisplay = document.getElementById('percentDisplay');
    const statusMsg = document.getElementById('statusMsg');
    const progressBar = document.getElementById('progressBar');
    const loadingSec = document.getElementById('loadingSection');
    const resultSec = document.getElementById('resultSection');
    const resultIcon = document.getElementById('resultIcon');
    const resultTitle = document.getElementById('resultTitle');
    const resultMsg = document.getElementById('resultMsg');

    function checkStatus() {
        fetch(`../../services/adm/cob-eg-ig/check_status.php?script=${scriptType}&variant=${variantType}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'ok') {
                    
                    percentDisplay.innerText = data.percent + "%";
                    statusMsg.innerText = data.msg;
                    progressBar.style.width = data.percent + "%";
                    progressBar.innerText = data.processed + " / " + data.total;

                    if (!data.running) {
                        clearInterval(polling);
                        mostrarResultado(data.run_status);
                    }
                }
            })
            .catch(err => console.error("Error conexión", err));
    }

    function mostrarResultado(status) {
        setTimeout(() => {
            loadingSec.style.display = 'none';
            resultSec.style.display = 'block';
            if (status == 1) { 
                resultIcon.innerHTML = '✅'; resultTitle.innerText = '¡Éxito!'; resultTitle.className = 'success';
                resultMsg.innerText = 'Proceso completado correctamente.';
            } else { 
                resultIcon.innerHTML = '❌'; resultTitle.innerText = 'Fallo'; resultTitle.className = 'error';
                resultMsg.innerText = 'El Job se detuvo con errores.';
            }
        }, 1000);
    }

    const polling = setInterval(checkStatus, 3000);
    checkStatus(); 
</script>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>