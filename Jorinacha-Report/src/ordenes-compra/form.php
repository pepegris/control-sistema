<?php
// form.php
require '../../includes/log.php';
include '../../includes/header2.php';
include '../../services/mysql.php';
// Aseguramos incluir empresas.php para tener acceso a $sedes_ar o $lista_replicas
require_once "../../services/empresas.php"; 
?>

<style>
  .form-container { max-width: 500px; margin: 0 auto; padding: 20px; background: #f9f9f9; border-radius: 8px; }
  select, input { width: 100%; padding: 8px; margin-bottom: 15px; }
</style>

<center>
  <h1>Crear Ordenes de Compras</h1>
</center>

<div id="body">
  <form action="routes.php" method="POST">
    <div class="form-container">
      <br>
      <center><legend>Reporte de Importación</legend></center>

      <label for="tienda" class="form-label">Tienda Destino</label>
      <select name="tienda" id="tienda" required>
        <option value="">Seleccione una tienda...</option>
        <?php
        // Usamos $lista_replicas si quieres las del array nuevo, 
        // o $sedes_ar si prefieres la lista vieja.
        // Aquí uso el nuevo array $lista_replicas para asegurar consistencia
        foreach ($lista_replicas as $nombreTienda => $datos) {
            echo "<option value='$nombreTienda'>$nombreTienda</option>";
        }
        ?>
      </select>

      <div class="form-group">
        <label for="fecha1" class="form-label">Fecha de Emisión</label>
        <input type="date" name="fecha1" id="fecha1" required>
      </div>
      
      <label for="corregir" class="form-label">Modo</label>
      <select name="corregir" id="corregir">
        <option value="IMPORTADO">Normal (Procesar Pendientes)</option>
        <option value="">Reprocesar (Corregir Fallos)</option>
      </select> 
      
      <br><br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>

<?php include '../../includes/footer.php'; ?>