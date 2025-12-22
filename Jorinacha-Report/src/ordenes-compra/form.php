<?php
require '../../includes/log.php';
include '../../includes/header2.php';
include '../../services/mysql.php';
// Agregamos la configuración nueva
require_once "../../services/empresas.php"; 
?>

<style>
  /* Mantenemos el estilo original */
  .form-check {
    display: none;
    display: flexbox;
  }
  
  /* Aseguramos que el fieldset tenga el estilo que esperas 
     (asumiendo que viene de tu hoja de estilos principal, 
     ya que en el código viejo solo estaba la clase) */
</style>

<center>
  <h1>Crear Ordenes de Compras</h1>
</center>

<div id="body">

  <form action="routes.php" method="POST">

    <div class="fieldset">
      <br>
      <center>
        <legend>Reporte</legend>
      </center>

      <label for="tienda" class="form-label ">Tienda</label>
      <select name="tienda" id="" required>
        <option value="">Seleccione...</option>
        <?php
        // Usamos el nuevo array $lista_replicas para obtener las IPs correctas
        // pero mantienendo el select simple del diseño anterior
        foreach ($lista_replicas as $nombreTienda => $datos) {
            echo "<option value='$nombreTienda'>$nombreTienda</option>";
        }
        ?>
      </select> 

      <div class="form-group">
        <label for="fecha1" class="form-label " require>Fecha</label>
        <input type="date" name="fecha1" id="" required>
      </div>
      
      <label for="corregir" class="form-label ">Corregir Orden de Compra</label>
      <select name="corregir" id="">
        <option value="IMPORTADO">No</option>
        <option value="">Si</option>
      </select> 
      
      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>

      <br>
    </div>
  </form>
</div>

<?php include '../../includes/footer.php'; ?>