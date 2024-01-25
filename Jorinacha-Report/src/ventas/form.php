<?php
require '../../includes/log.php';
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

?>

<style>
  .form-check {
    display: none;
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
        <legend>Reporte</legend>
      </center>


      <div class="form-check">
        <?php
        $res1 = getTiendas();
        $i = 0;
        while ($row1 = mysqli_fetch_array($res1)) {

          $sede = $row1['sedes_nom'];
          if ($sede == 'Sede Boleita') {
            $sede = 'Previa Shop';
          }

        ?>

          <input class="form-check-input" type="checkbox" value="<?= $sede ?>" name="<?= $i ?>" checked>
          <label class="form-check-label" for="<?= $sede ?>">
            <?= $sede ?>
          </label>

        <?php $i++;
        } ?>

      </div>


      <!-- FORMULAIO DE FECHAS -->

      <label for="reporte" class="form-label ">Reporte</label>
      <select name="reporte" id="">

        
        <option value="diario">Ventas Diarias</option>
        <!-- <option value="ventas">Ventas Detalladas</option>  -->
        <option value="acumulado">Ventas Acumuladas</option>
        <option value="dias">Ventas Detalladas por Dias</option> 
        <option value="mes">Ventas Detalladas por Mes</option> 
        <option value="ord">Detalles de Ord de Pagos</option> 
        <option value="factura">Detalles de Facturas, Cobros y Devoluciones</option> 
        

      </select> 

      <label for="divisa" class="form-label ">Divisa</label>
      <select name="divisa" id="">

        
        <option value="bs">Bolivares</option>
        <option value="dl">Dolares</option>

      </select> 

      
      <div class="form-group">
        <label for="linea" class="form-label ">Marcas</label>
        <select name="linea" id="">
          <option value="todos">Todas las marcas</option>

          <?php


          $res2 = getLin_art_all();

          for ($i = 0; $i < count($res2); $i++) {

            $lin_des = utf8_encode("$res2[$i]['lin_des']")
          ?>
            
            <option value="<?= $res2[$i]["co_lin"] ?>"><?= $res2[$i]['lin_des'] ?> - <?= $res2[$i]["co_lin"] ?></option>

          <?php   }  ?>

        </select>
      </div>
      


      <div class="form-group">
        <label for="fecha1" class="form-label " require>Desde</label>
        <input type="date" name="fecha1" id="" required>
      </div>

      <div class="form-group">
        <label for="fecha2" class="form-label " require>Hasta</label>
        <input type="date" name="fecha2" id="" >
      </div>






      
        <label for="clave" class="form-label ">Clave</label>
          <input type="password" name="clave" id="" placeholder="Ingresa la contraseña">
      
      

      <br>
      <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
      <br>
    </div>
  </form>
</div>




<?php include '../../includes/footer.php'; ?>