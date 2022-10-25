<?php
ini_set('memory_limit','4096M');
ini_set('max_execution_time',3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

if (isset($_GET)) {


  $linea = $_GET['linea'];
  $almacen =$_GET['almacen'];

  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));  


?>


  <style>

form , td {
  font-size: 12px;
}


  </style>
  <center><h1>Fallas</h1></center>
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
        


        <th scope='col'>Ref</th>

        <th scope='col'>Total Vendido</th>
        

        <?php

        for ($i = 0; $i < count($sedes_ar); $i++) {

          if ($sedes_ar[$i] != null) {

            $sede = $sedes_ar[$i];

        ?>
            <th scope='col'><?= $sede ?></th>
        <?php  
          if ($sedes_ar[$i] != 'Previa Shop') {
            echo "<th scope='col'>Ref $i</th>";
            echo "<th scope='col'>PVP $i</th>";
            echo "<th scope='col'>Costo $i</th>";
            echo "<th scope='col'>Total Vendido $i</th>";
          }
            
        ?>
        <?php }
        } ?>

      </tr>
    </thead>
    <tbody>
      <?php

      $res0 = getArt('Previa Shop', $linea,0 ,$almacen );
      
      $n = 1;
      for ($e = 0; $e < count($res0); $e++) {



        $co_art = $res0[$e]['co_art'];
        $co_lin = getLin_art($res0[$e]['co_lin']);
        $co_subl = getSub_lin($res0[$e]['co_subl']);
        $co_cat = getCat_art($res0[$e]['co_cat']);
        $co_color = getColores($res0[$e]['co_color']);
        $desc = $res0[$e]['ubicacion'];
        
        $test1 = getPedidos(null, $co_art);


        $pedido = $test1['total_art'];

        $precio = $res0[$e]['prec_vta3'];
        $prec_vta3_costo = number_format($precio, 2, ',', '.');

        $stock_act_1 = round($res0[$e]['stock_act']);
        $stock_act =  $stock_act_1 - $pedido; 
        $total_stock_act_previa += $stock_act;



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
          <td>$<?= $prec_vta5 ?></td>
          <?php
          $g = 1;
          $total_vendido = 0; 
          for ($i = 0; $i < count($sedes_ar); $i++) {

            if ($sedes_ar[$g] != null) {

              $res1 = getReng_fac($sedes_ar[$g],  $co_art, $fecha1, $fecha2);
              $total_vendido += round($res1);


            }
            $g++;
          }

          $estilo1='normal';
          $estilo2='normal';

          if ($total_vendido >=1) {
            $estilo1='bold';
          }else {
            $estilo1='normal';
          }

          if ($stock_act >=1) {
            $estilo2='bold';
          }else {
            $estilo2='normal';
          }

          ?>

          <td style="font-weight:<?= $estilo1 ?>;"><?= $total_vendido ?></td>
          <td style="font-weight:<?= $estilo2 ?>;"><?= $stock_act ?></td>
          <?php
          $f = 1;
          for ($i = 0; $i < count($sedes_ar); $i++) {


            if ($sedes_ar[$f] == null) {
              $f++;
            } else {

              $res3 = getArt_stock_tiendas($sedes_ar[$f], $co_art);
              $stock_act_tienda = round($res3[0]['stock_act']);
              $total_stock_act_tienda[$sedes_ar[$f]] += $stock_act_tienda;

              $res4 = getReng_fac($sedes_ar[$f],  $co_art, $fecha1, $fecha2);
              $vendido_tienda = number_format($res4, 0, ',', '.');
              $total_vendido_tienda [$sedes_ar[$f]] += $vendido_tienda;

              $res5 = getArt($sedes_ar[$f], $linea, $co_art,null);
              $prec_vta5_tienda = round($res5[0]['prec_vta5']);
              $prec_vta1_tienda = round($res5[0]['prec_vta1']);


              $total_pedido = $res7['total_art'];
              $status = $res7['status'];
              $documento = $res7['doc'];

              $estilo1='normal';
              $estilo2='normal';
              $estilo3='normal';

              if ($stock_act_tienda >=1) {
                $estilo1='bold';
              }else {
                $estilo1='normal';
              }

              if ($vendido_tienda >=1) {
                $estilo3='bold';
              }else {
                $estilo3='normal';
              }


          ?>
              <td style="font-weight:<?= $estilo1 ?>;"><?= $stock_act_tienda  ?></td>
              <td>$<?= $prec_vta5_tienda  ?></td>
              <td>Bs<?= $prec_vta1_tienda  ?></td>
              <td>Bs<?= $prec_vta3_costo  ?></td>
              
              <td style="font-weight:<?= $estilo3 ?>;"><?= $vendido_tienda ?></td>

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
        <?php

        $h = 1;
        for ($i = 0; $i < count($total_stock_act_tienda); $i++) {
          $vendido = $total_vendido_tienda[$sedes_ar[$h]];

        ?>
          <td><?= $total_stock_act_tienda[$sedes_ar[$h]] ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td><?= $vendido  ?></td>

          <?php

          $h++;
         }

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