<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';
?>

<style>
  /* 1. TU ESTILO ORIGINAL */
  .form-check {
    display: none;
    display: flexbox;
  }

  /* 2. ESTILOS AGREGADOS SOLO PARA LA PANTALLA DE CARGA (No afecta tu diseño base) */
  #loadingOverlay {
      display: none; 
      position: fixed; 
      top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0,0,0,0.85); /* Fondo oscuro transparente */
      z-index: 9999; 
      text-align: center; 
      padding-top: 15%;
      font-family: sans-serif;
  }
  .spinner {
      width: 60px; height: 60px; 
      border: 6px solid #f3f3f3; 
      border-top: 6px solid #28a745; /* Verde del botón success */
      border-radius: 50%; 
      animation: spin 1s linear infinite; 
      margin: 0 auto 20px auto;
  }
  @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
  .loading-text { color: white; }
</style>

<div id="loadingOverlay">
    <div class="spinner"></div>
    <h2 class="loading-text">Procesando Base de Datos...</h2>
    <p class="loading-text">Por favor espere, no cierre esta ventana.</p>
</div>

<center>
  <h1>Backups para Integración</h1>
</center>

<div id="body">

  <form action="Import.php" method="POST" id="backupForm">

    <div class="fieldset">

      <center>
        <legend>Importar Información</legend>
      </center>
      
      <div class="form-group">
        <label for="scripts" class="form-label">Script</label>
        <select name="scripts" id="scripts">
          <option value="" selected disabled>-- Seleccione --</option>
          <option value="backups">📤 1. Realizar Backups de Profit Administrativo</option>
          <option value="restore"> 📤 2. Importar Backups a Profit de Integración</option>
        </select>
      </div>

      <div class="form-group">
        <label for="clave" class="form-label">Contraseña</label>
        <input type="password" name="clave" id="clave" placeholder="Ingrese clave de seguridad" style="width:100%">
      </div>
      
      <br>
      
      <center>
          <button type="button" onclick="confirmarEjecucion()" class="btn btn-success">Ejecutar</button>
      </center>
      
      <br>
    </div>
  </form>
</div>

<script>
    function confirmarEjecucion() {
        var script = document.getElementById('scripts').value;
        var clave = document.getElementById('clave').value;

        // 1. Validaciones
        if (script == "") {
            alert("Debe seleccionar una opción.");
            return;
        }
        if (clave == "") {
            alert("Debe ingresar la contraseña.");
            return;
        }

        // 2. Mensajes
        var mensaje = "";
        if (script == 'backups') {
            mensaje = "¿Está seguro de generar los BACKUPS?";
        } else if (script == 'restore') {
            mensaje = "⚠️ ADVERTENCIA: Se borrará la base de datos destino (172.16.1.19) para importar la nueva data. ¿Continuar?";
        }

        // 3. Confirmar y Enviar
        if (confirm(mensaje)) {
            // Mostrar pantalla de carga
            document.getElementById('loadingOverlay').style.display = 'block';
            
            // Enviar formulario (Salta a Import.php)
            setTimeout(function() {
                document.getElementById('backupForm').submit();
            }, 500);
        }
    }
</script>

<?php include '../../includes/footer.php'; ?>