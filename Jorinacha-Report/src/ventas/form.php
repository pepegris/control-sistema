<?php
require '../../includes/log.php';
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';
?>

<style>
  .form-check {
    display: none; /* Oculto por defecto según tu CSS original */
    display: flexbox;
  }
</style>

<center>
  <h1>Reporte de Ventas</h1>
</center>

<div id="body">

  <form action="routes.php" method="POST">

    <div class="fieldset">
      <br>
      <center>
        <legend>Selección de Tiendas</legend>
      </center>

      <div class="form-check">
        <?php
        $res1 = getTiendas();
        // Eliminamos el contador $i manual
        while ($row1 = mysqli_fetch_array($res1)) {

          $sede = $row1['sedes_nom'];
          if ($sede == 'Sede Boleita') {
            $sede = 'Previa Shop';
          }
        ?>
          <input class="form-check-input" type="checkbox" value="<?= $sede ?>" name="sedes[]" id="chk_<?= str_replace(' ', '', $sede) ?>" checked>
          <label class="form-check-label" for="chk_<?= str_replace(' ', '', $sede) ?>">
            <?= $sede ?>
          </label>

        <?php 
        } 
        ?>
      </div>

      <div class="form-group mt-3">
          <label for="reporte" class="form-label ">Tipo de Reporte</label>
          <select name="reporte" id="reporte" class="form-select">
            <option value="diario">Ventas Diarias (Cuadre de Caja)</option>
            <option value="dias">Ventas Detalladas por Días</option> 
            <option value="mes">Ventas Detalladas por Mes</option> 
            <option value="por">Gráfica de las Ventas</option> 
            <option value="des">Gráfica de Despachos</option> 
            <option value="ordenes">Ordenes de Pagos</option> 
            <option value="ord">Detalles de Ord de Pagos</option> 
            <option value="factura">Detalles de Facturas (Auditoría)</option> 
            <option value="cashea">Reporte Cashea</option> 
            <option value="lysto">Reporte Lysto</option> 
          </select> 
      </div>

      <div class="form-group">
          <label for="divisa" class="form-label ">Divisa</label>
          <select name="divisa" id="divisa" class="form-select">
            <option value="bs">Bolívares</option>
            <option value="dl">Dólares</option>
          </select> 
      </div>
      
      <div class="form-group">
        <label for="linea" class="form-label ">Marcas</label>
        <select name="linea" id="linea" class="form-select">
          <option value="todos">Todas las marcas</option>
          <?php
          $res2 = getLin_art_all();
          for ($i = 0; $i < count($res2); $i++) {
            // Nota: utf8_encode está obsoleto en versiones nuevas de PHP, úsalo solo si es necesario por tu BD
            //$lin_des = utf8_encode($res2[$i]['lin_des']); 
            $lin_des = $res2[$i]['lin_des'];
          ?>
            <option value="<?= $res2[$i]["co_lin"] ?>"><?= $lin_des ?> - <?= $res2[$i]["co_lin"] ?></option>
          <?php   }  ?>
        </select>
      </div>
      
      <div class="form-group">
        <label for="fecha1" class="form-label " require>Desde</label>
        <input type="date" name="fecha1" id="fecha1" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="fecha2" class="form-label " require>Hasta</label>
        <input type="date" name="fecha2" id="fecha2" class="form-control">
      </div>

      <div class="form-group">
        <label for="clave" class="form-label ">Clave de Seguridad</label>
        <input type="password" name="clave" id="clave" class="form-control" placeholder="Ingresa la contraseña">
      </div>
      
      <br>
      <center><button type="submit" class="btn btn-primary">Generar Reporte</button></center>
      <br>
    </div>
  </form>
</div>

<?php include '../../includes/footer.php'; ?>