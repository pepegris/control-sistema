<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
require '../../services/adm/precios/precios.php';

if (isset($_GET)) {


  $linea = $_GET['linea'];
  $tasa = $_GET['tasa'];

  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));


  for ($i = 0; $i < 20; $i += 1) {
    $sedes[] = $_GET[$i];
  }



?>


  <center>
    <h1>Valor de Inventario</h1>
  </center>
  <table class="table table-dark table-striped" id="tblData">
    <thead>
      <tr>
        <th scope="col">Tasa: <?= $tasa ?></th>
        <th scope='col'>Codigo</th>
        <th scope='col'>Marca</th>
        <th scope='col'>Modelo</th>
        <th scope='col'>Escala</th>
        <th scope='col'>Color</th>

        <th scope='col'>Stock Previa Shop</th>

        <th scope='col'>Costo</th>
        <th scope='col'>Total Costo</th>
        <th scope='col'>PVP</th>
        <th scope='col'>Total PVP</th>

        <th scope='col'>Total Pares Vendido Tiendas</th>

        <?php

        for ($i = 1; $i < count($sedes_ar); $i++) {




          if ($sedes_ar[$i] != null) {

            $sede = $sedes_ar[$i];

        ?>

            <th scope='col'>Stock <?= $sede ?></th>

        <?php
            if ($sedes_ar[$i] != 'Previa Shop') {

              echo "<th scope='col'>Pares Vendidos $sede</th>";

              echo "<th scope='col'>Costo $sede</th>";
              echo "<th scope='col'>Total Costo $sede</th>";

              echo "<th scope='col'>PVP $sede</th>";
              echo "<th scope='col'>Total PVP $sede</th>";
            }
          }
        } ?>

        <th scope='col'>Stock Total Tiendas</th>
        <th scope='col'>Total Costo Tiendas</th>
        <th scope='col'>Total PVP</th>

      </tr>
    </thead>

    <tbody>

      <?php

      $getArt1 = getArt('Previa Shop', $linea, 0, 0);

      $n = 1;
      for ($e = 0; $e < count($getArt1); $e++) {

        $co_art = $getArt1[$e]['co_art'];
        $co_lin = $getArt1[$e]['co_lin'];
        $co_subl = $getArt1[$e]['co_subl'];
        $co_cat = $getArt1[$e]['co_cat'];
        $co_color = $getArt1[$e]['co_color'];



        $test1 = getPedidos(null, $co_art);

        $pedido = $test1['total_art'];

        $res_stock = getArt('Previa Shop', $linea, $co_art, 'BOLE');

        $stock_act_1 = round($res_stock[0]['stock_act']);


        $stock_act =  $stock_act_1 - $pedido;
        $total_stock_act_previa += $stock_act;





        $prec_vta5 = $getArt1[$e]['prec_vta5'];

        $prec_vta4 = $getArt1[$e]['prec_vta4'];


        $total_prec_vta5 = $stock_act * $prec_vta5;
        $total_prec_vta4 = $stock_act * $prec_vta4;

        $total_prec_vta5_todo +=  $total_prec_vta5;
        $total_prec_vta4_todo +=  $total_prec_vta4;


        $g = 1;
        $total_vendido = 0;
        for ($i = 0; $i < count($sedes_ar); $i++) {

          if ($sedes_ar[$g] != null) {

            $getReng_fac1 = getReng_fac($sedes_ar[$g],  $co_art, $fecha1, $fecha2);
            $total_vendido += round($getReng_fac1);
          }
          $g++;
        }

        $total_vendido_todo += $total_vendido;


        require '../../services/adm/precios/estilo1.php';



      ?>

        <tr>
          <th scope='row'><?= $n ?></th>
          <td><?= $co_art ?></td>
          <td><?= $co_lin ?></td>
          <td><?= $co_subl ?></td>
          <td><?= $co_cat ?></td>
          <td><?= $co_color ?></td>

          <td style="font-weight:<?= $estilo3 ?>;"><?= $stock_act ?></td>



          <td>$<?= number_format($prec_vta4, 2, ',', '.'); ?></td>
          <td style="font-weight:<?= $estilo2 ?>;">$<?= number_format($total_prec_vta4,  2, ',', '.'); ?></td>

          <td>$<?= number_format($prec_vta5, 0, ',', '.'); ?></td>
          <td style="font-weight:<?= $estilo1 ?>;">$<?= number_format($total_prec_vta5, 0, ',', '.'); ?></td>


          <td style="font-weight:<?= $estilo4 ?>;"><?= $total_vendido ?></td>



          <!-- TIENDAS -->
          <?php
          $f = 1;
          for ($i = 0; $i < count($sedes_ar); $i++) {


            if ($sedes_ar[$f] == null) {
              $f++;
            } else {



              $getArt2 = getArt($sedes_ar[$f], $linea, $co_art, null);
              $stock_act_tienda = round($getArt2[0]['stock_act']);
              $total_stock_act_tienda[$sedes_ar[$f]] += $stock_act_tienda;
              $total_stock_art_tienda += $stock_act_tienda;

              $prec_vta5_tienda = $getArt2[0]['prec_vta5'];


              $getReng_fac2 = getReng_fac($sedes_ar[$f],  $co_art, $fecha1, $fecha2);
              $vendido_tienda = number_format($getReng_fac2, 0, ',', '.');
              $total_vendido_tienda[$sedes_ar[$f]] += $vendido_tienda;


              $total_prec_vta5_tienda = $stock_act_tienda * $prec_vta5_tienda;
              $total_pvp_tienda += $total_prec_vta5_tienda;

              $total_prec_vta3_costo_tienda = $stock_act_tienda * $prec_vta4;
              $total_costo_tienda += $total_prec_vta3_costo_tienda;

              $total_prec_vta5_tienda_todo[$sedes_ar[$f]] +=  $total_prec_vta5_tienda;
              $total_prec_vta3_costo_tienda_todo[$sedes_ar[$f]] +=  $total_prec_vta3_costo_tienda;


              require '../../services/adm/precios/estilo2.php';


          ?>
              <td style="font-weight:<?= $estilo1 ?>;"><?= $stock_act_tienda  ?></td>

              <td style="font-weight:<?= $estilo5 ?>;"><?= $vendido_tienda ?></td>

              <td>$<?= number_format($prec_vta4, 2, ',', '.');  ?></td>
              <td style="font-weight:<?= $estilo4 ?>;">$<?= number_format($total_prec_vta3_costo_tienda, 2, ',', '.');  ?></td>

              <td>$<?= number_format($prec_vta5_tienda, 0, ',', '.');  ?></td>
              <td style="font-weight:<?= $estilo2 ?>;">$<?= number_format($total_prec_vta5_tienda, 0, ',', '.'); ?></td>






          <?php $f++;
            }
          } ?>

          <td><?= $total_stock_art_tienda ?></td>
          <td>$<?= number_format($total_pvp_tienda, 0, ',', '.'); ?></td>
          <td>$<?= number_format($total_costo_tienda, 2, ',', '.'); ?></td>

          <?php

          $total_stock_art_tienda = 0;
          $total_pvp_tienda = 0;
          $total_costo_tienda = 0;

          $n++ ?>





        </tr>




      <?php  } ?>
      <tr>

        <td colspan="6">
          <h3>Totales</h3>
        </td>




        <td><b><?= $total_stock_act_previa ?></td>

        <td></td>
        <td><b>$<?= number_format($total_prec_vta4_todo, 2, ',', '.'); ?></b></td>
        
        <td></td>
        <td><b>$<?= number_format($total_prec_vta5_todo, 0, ',', '.'); ?></b></td>
        
          
        <td><b><?= $total_vendido_todo ?></td>



        <?php

        $h = 1;
        $tienda_total_ref = 0;
        $tienda_total_bs = 0;
        $tienda_total_stock = 0;
        for ($i = 0; $i < count($total_stock_act_tienda); $i++) {
          $vendido = $total_vendido_tienda[$sedes_ar[$h]];

        ?>
          <td><b><?= $total_stock_act_tienda[$sedes_ar[$h]] ?></b></td>

          <td><b><?= $total_vendido_tienda[$sedes_ar[$h]] ?></b></td>

          <td></td>
          <td><b>$<?= number_format($total_prec_vta3_costo_tienda_todo[$sedes_ar[$h]], 2, ',', '.'); ?></b></td>

          <td></td>
          <td><b>$<?= number_format($total_prec_vta5_tienda_todo[$sedes_ar[$h]], 0, ',', '.');  ?></b></td>



        <?php

          $tienda_total_ref += $total_prec_vta5_tienda_todo[$sedes_ar[$h]];
          $tienda_total_cost += $total_prec_vta3_costo_tienda_todo[$sedes_ar[$h]];

          $tienda_total_stock += $total_stock_act_tienda[$sedes_ar[$h]];

          $h++;
        }

        ?>
      </tr>
      <!--  -->
      <tr>

        <td colspan="1">
          <h3>Totales Valor Previa</h3>
        </td>




        <td><b>$<?= number_format($total_prec_vta4_todo, 2, ',', '.'); ?></b></td>
        <td><b>$<?= number_format($total_prec_vta5_todo, 0, ',', '.'); ?></b></td>
        
        <td>Stock</td>
        <td><b><?= $total_stock_act_previa ?></td>



      </tr>
      <!--  -->
      <tr>

        <td colspan="1">
          <h3>Totales Valor Tiendas</h3>
        </td>




        <td><b>$<?= number_format($tienda_total_cost, 2, ',', '.'); ?></b></td>
        <td><b>$<?= number_format($tienda_total_ref, 0, ',', '.'); ?></b></td>
        <td>Stock</td>
        <td><b><?= $tienda_total_stock ?></td>




      </tr>

    </tbody>
  </table>

  <!-- 
  <script>
    function htmlExcel(idTabla, nombreArchivo = '') {
  let linkDescarga;
  let tipoDatos = 'application/vnd.ms-excel';
  let tablaDatos = document.getElementById(idTabla);
  let tablaHTML = tablaDatos.outerHTML.replace(/ /g, '%20');

  // Nombre del archivo
  nombreArchivo = nombreArchivo ? nombreArchivo + '.xls' : 'Reporte_Puntos_Canjeados.xls';

  // Crear el link de descarga
  linkDescarga = document.createElement("a");

  document.body.appendChild(linkDescarga);

  if (navigator.msSaveOrOpenBlob) {
    let blob = new Blob(['\ufeff', tablaHTML], {
      type: tipoDatos
    });
    navigator.msSaveOrOpenBlob(blob, nombreArchivo);
  } else {
    // Crear el link al archivo
    linkDescarga.href = 'data:' + tipoDatos + ', ' + tablaHTML;

    // Setear el nombre de archivo
    linkDescarga.download = nombreArchivo;

    //Ejecutar la función
    linkDescarga.click();
  }
}
  </script>

<button onclick="htmlExcel('tblData', 'Reporte_Puntos_Canjeados')">Exportar reporte</button>

  <script src="../../assets/js/excel.js"></script>
  <center>
    <button id="submitExport" class="btn btn-success">Exportar Reporte a EXCEL</button>
  </center>
 -->



<?php

} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>