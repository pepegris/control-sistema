<?php
ini_set('memory_limit','4096M');
ini_set('max_execution_time',3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

if (isset($_GET)) {


  $linea = $_GET['linea'];

  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));  

  for ($i = 0; $i < 20; $i += 1) {
    $sedes[] = $_GET[$i];
  }



?>


  <style>
    form,
    td {
      font-size: 12px;


    }
  </style>
<center><h1>Articulos con su Precio</h1></center>
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
        <th scope='col'>Total Ref</th>
        <th scope='col'>Bs</th>
        <th scope='col'>Total Bs</th>
        <?php

        for ($i = 0; $i < count($sedes_ar); $i++) {




          if ($sedes_ar[$i] != null) {

            $sede = $sedes_ar[$i];

        ?>
            <th scope='col'>Stock <?= $sede ?></th>
            <?php
            if ($sedes_ar[$i] != 'Previa Shop') {

              echo "<th scope='col'>Ref $i</th>";
              echo "<th scope='col'>Total Ref $i</th>";
              echo "<th scope='col'>PVP $i</th>";
              echo "<th scope='col'>Total PVP $i</th>";
              echo "<th scope='col'>Costo $i</th>";
              echo "<th scope='col'>Total Costo $i</th>";
            }

            ?>
        <?php }
        } ?>

      </tr>
    </thead>
    <tbody>
      <?php

      $res0 = getArt('Previa Shop', $linea, 0,null);

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

        $precio = $res0[$e]['prec_vta3'];
        $prec_vta3_costo = number_format($precio, 2, ',', '.');

        $prec_vta5 = round($res0[$e]['prec_vta5']);

        $total_prec_vta5 = $stock_act * $prec_vta5;
        $total_prec_vta3 = $stock_act * $prec_vta3;

        $total_prec_vta5_todo +=  $total_prec_vta5;
        $total_prec_vta3_todo +=  $total_prec_vta3;

        

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
          <td>$<?= $total_prec_vta5 ?></td>

          <td>Bs<?= $prec_vta3_costo ?></td>
          <td>Bs<?= $total_prec_vta3 ?></td>

          <td><?= $stock_act ?></td>


          <!-- TIENDAS -->
          <?php
          $f = 1;
          for ($i = 0; $i < count($sedes_ar); $i++) {


            if ($sedes_ar[$f] == null) {
              $f++;
            } else {

              $res3 = getArt_stock_tiendas($sedes_ar[$f], $co_art);
              $stock_act_tienda = round($res3[0]['stock_act']);
              $total_stock_act_tienda[$sedes_ar[$f]] += $stock_act_tienda;


              $res6 = getArt($sedes_ar[$f], $linea, $co_art,null);
              $prec_vta5_tienda = round($res6[0]['prec_vta5']);
              $prec_vta1_tienda = number_format($res6[0]['prec_vta1'], 2, ',', '.');

              $total_prec_vta5_tienda = $stock_act_tienda * $prec_vta5_tienda;
              $total_prec_vta1_tienda = $stock_act_tienda * $prec_vta1_tienda;
              $prec_vta3_costo_tienda = $stock_act_tienda * $prec_vta3_costo;

              $total_prec_vta5_tienda_todo +=  $prec_vta5_tienda;
              $total_prec_vta1_tienda_todo +=  $prec_vta1_tienda;
              $prec_vta3_costo_tienda_todo +=  $prec_vta3_costo_tienda;



          ?>
              <td><?= $stock_act_tienda  ?></td>

              <td>$<?= $prec_vta5_tienda ?></td>
              <td>$<?= $total_prec_vta5_tienda ?></td>

              <td>Bs<?= $prec_vta1_tienda ?></td>
              <td>Bs<?= $total_prec_vta1_tienda ?></td>

              <td>Bs<?= $prec_vta3_costo ?></td>
              <td>Bs<?= $prec_vta3_costo_tienda ?></td>



          <?php $f++;
            }
          }
          $n++ ?>
        </tr>




      <?php  } ?>
      <tr>
        <th ></th>
        <td colspan="5"></td>
        <td>
          <h4>Total</h4>
        </td>
        <td></td>
        <td>$<?= $total_prec_vta5_todo ?></td>
        <td></td>
        <td>Bs<?= number_format($total_prec_vta3_todo, 2, ',', '.'); ?></td>
        <td><?= $total_stock_act_previa ?></td>

        <?php

        $h = 1;
        for ($i = 0; $i < count($total_stock_act_tienda); $i++) {
          $vendido = $total_vendido_tienda[$sedes_ar[$h]];

        ?>
          <td><?= $total_stock_act_tienda[$sedes_ar[$h]] ?></td>
          <td></td>
          <td>$<?= $total_prec_vta5_tienda_todo ?></td>
          <td></td>
          <td>Bs<?= number_format($total_prec_vta1_tienda_todo, 2, ',', '.'); ?></td>
          <td></td>
          <td>Bs<?=number_format( $prec_vta3_costo_tienda_todo); ?></td>
          
          



        <?php

          $h++;
        }

        ?>
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
  Cerrar(null);
} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>