<?php
ini_set('memory_limit','4096M');
ini_set('max_execution_time',3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

if (isset($_POST)) {


  $linea = $_POST['linea'];

  $cantidad=$_POST['cantidad'];


?>


  <style>

form , td {
  font-size: 12px;
}


  </style>
  <center><h1>Fallas Sin Pedidos</h1></center>
  <table class="table table-dark table-striped" id="tblData">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope='col'>Codigo</th>
        <th scope='col'>Marca</th>
        <th scope='col'>Modelo</th>
        <th scope='col'>Desc</th>
        <th scope='col'>Escala</th>
        <th scope='col'>Color</th>

        <?php

        for ($i = 0; $i < count($sedes_ar); $i++) {

          
          

          if ($sedes_ar[$i] != null) {

            $sede = $sedes_ar[$i];

        ?>
            <th scope='col'><?= $sede ?></th>

        <?php }
        } ?>

      </tr>
    </thead>
    <tbody>
      <?php

      $res0 = getArt('Previa Shop', $linea,0);
      
      $n = 1;
      for ($e = 0; $e < count($res0); $e++) {

        $co_art = $res0[$e]['co_art'];
        $co_lin = getLin_art($res0[$e]['co_lin']);
        $co_subl = getSub_lin($res0[$e]['co_subl']);
        $co_cat = getCat_art($res0[$e]['co_cat']);
        $co_color = getColores($res0[$e]['co_color']);
        $desc = $res0[$e]['ubicacion'];

        $stock_act = round($res0[$e]['stock_act']);
        $total_stock_act_previa += $stock_act;

        $precio = round($res0[$e]['prec_vta1']);
        $prec_vta1 = number_format($precio, 2, ',', '.');

        $prec_vta5 = round($res0[$e]['prec_vta5']);

      ?>

        <tr>
          <th scope='row'><?= $n ?></th>
          <td><?= $co_art ?></td>
          <td><?= $co_lin ?></td>
          <td><?= $co_subl ?></td>
          <td><?= $desc ?></td>
          <td><?= $co_cat ?></td>
          <td><?= $co_color ?></td>


          <td><?= $stock_act ?></td>
          <?php
          $f = 1;
          for ($i = 0; $i < count($sedes_ar); $i++) {


            if ($sedes_ar[$f] == null) {
              $f++;
            } else {

              $res3 = getArt_stock_tiendas($sedes_ar[$f], $co_art);
              $stock_act_tienda = round($res3[0]['stock_act']);
              $total_stock_act_tienda[$sedes_ar[$f]] += $stock_act_tienda;



          ?>
              <td><?= $stock_act_tienda  ?></td>


          <?php $f++;
            }
          }
          $n++ ?>
        </tr>




      <?php  } ?>
      <tr>
        <th></th>
        <td colspan="7"></td>
        <td>
          <h4>Total</h4>
        </td>
        <td><?= $total_stock_act_previa ?></td>
        <td><?= $total_stock_act_tienda[$sedes_ar[$h]] ?></td>


          <?php

          $h++;
        

          ?>
      </tr>

    </tbody>
  </table>
  <script src="../../assets/js/excel.js"></script>
  <center>
    <button id="submitExport" class="btn btn-success">Exportar Reporte a EXCEL</button>
  </center>




<?php
} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>