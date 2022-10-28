<?php
ini_set('memory_limit','4096M');
ini_set('max_execution_time',3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

if (isset($_GET)) {


  $linea = $_GET['linea'];
  $tasa = $_GET['tasa'];

  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));  


  for ($i = 0; $i < 20; $i += 1) {
    $sedes[] = $_GET[$i];
  }



?>


<center><h1>Valor de Inventario</h1></center>
  <table class="table table-dark table-striped" id="tblData">
    <thead>
      <tr>
        <th scope="col">Tasa: <?= $tasa ?></th>
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
            <th scope='col'>Total Vendido</th>
            <?php
            if ($sedes_ar[$i] != 'Previa Shop') {

              echo "<th scope='col'>Pares Vendidos $sede</th>";
              echo "<th scope='col'>Ref $sede</th>";
              
              echo "<th scope='col'>Total Ref $sede</th>";
              echo "<th scope='col'>PVP $sede</th>";
              echo "<th scope='col'>Total PVP $sede</th>";
              echo "<th scope='col'>Costo $sede</th>";
              echo "<th scope='col'>Total Costo $sede</th>";
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

        $prec_vta5 = round($res0[$e]['prec_vta5']);

        $precio = $prec_vta5 * $tasa;
        $prec_vta3_costo = number_format($precio, 2, ',', '.');

        $total_prec_vta5 = $stock_act * $prec_vta5;
        $total_prec_vta3 = $stock_act * $prec_vta3_costo;

        $total_prec_vta5_todo +=  $total_prec_vta5;
        $total_prec_vta3_todo +=  $total_prec_vta3;

        $estilo1='normal';
        $estilo2='normal';
        $estilo2='normal';

        if ($total_prec_vta5 >=1) {
          $estilo1='bold';
        }else {
          $estilo1='normal';
        }

        if ($total_prec_vta3 >=1) {
          $estilo2='bold';
        }else {
          $estilo2='normal';
        }

        if ($stock_act >=1) {
          $estilo3='bold';
        }else {
          $estilo3='normal';
        }

        $g = 1;
        $total_vendido = 0;
        for ($i = 0; $i < count($sedes_ar); $i++) {

          if ($sedes_ar[$g] != null) {

            $res_v = getReng_fac($sedes_ar[$g],  $co_art, $fecha1, $fecha2);
            $total_vendido += round($res_v);
            
          }
          $g++;
        }

        $total_vendido_todo +=$total_vendido;

        if ($total_vendido >=1) {
          $estilo4='bold';
        }else {
          $estilo4='normal';
        }


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
          <td style="font-weight:<?= $estilo1 ?>;">$<?= number_format($total_prec_vta5, 0, ',', '.'); ?></td>

          <td>Bs<?= $prec_vta3_costo ?></td>
          <td style="font-weight:<?= $estilo2 ?>;">Bs<?= number_format($total_prec_vta3, 2, ',', '.'); ?></td>

          <td style="font-weight:<?= $estilo3 ?>;"><?= $stock_act ?></td>
          <td style="font-weight:<?= $estilo4 ?>;"><?= $total_vendido ?></td>


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
              $precio_tienda = $prec_vta5_tienda * $tasa;
              $prec_vta1_tienda = number_format($precio_tienda, 2, ',', '.');

              $res4 = getReng_fac($sedes_ar[$f],  $co_art, $fecha1, $fecha2);
              $vendido_tienda = number_format($res4, 0, ',', '.');
              $total_vendido_tienda [$sedes_ar[$f]] += $vendido_tienda;

              $descuento=$prec_vta3_costo * 0.30;
              $prec_vta3_costo_tienda = $prec_vta3_costo - $descuento;

              $total_prec_vta5_tienda = $stock_act_tienda * $prec_vta5_tienda;
              $total_prec_vta1_tienda = $stock_act_tienda * $prec_vta1_tienda;
              $total_prec_vta3_costo_tienda = $stock_act_tienda * $prec_vta3_costo_tienda;
   
              $total_prec_vta5_tienda_todo[$sedes_ar[$f]] +=  $total_prec_vta5_tienda;
              $total_prec_vta1_tienda_todo[$sedes_ar[$f]] +=  $total_prec_vta1_tienda;
              $total_prec_vta3_costo_tienda_todo[$sedes_ar[$f]] +=  $total_prec_vta3_costo_tienda;


              $estilo1='normal';
              $estilo2='normal';
              $estilo3='normal';
              $estilo4='normal';
      
              if ($stock_act_tienda >=1) {
                $estilo1='bold';
              }else {
                $estilo1='normal';
              }
      
              if ($total_prec_vta5_tienda >=1) {
                $estilo2='bold';
              }else {
                $estilo2='normal';
              }
      
              if ($total_prec_vta1_tienda >=1) {
                $estilo3='bold';
              }else {
                $estilo3='normal';
              }

              if ($total_prec_vta3_costo_tienda >=1) {
                $estilo4='bold';
              }else {
                $estilo4='normal';
              }
              if ($vendido_tienda >=1) {
                $estilo5='bold';
              }else {
                $estilo5='normal';
              }




          ?>
              <td style="font-weight:<?= $estilo1 ?>;"><?= $stock_act_tienda  ?></td>
              <td style="font-weight:<?= $estilo5 ?>;"><?= $vendido_tienda ?></td>

              <td>$<?= $prec_vta5_tienda ?></td>
              <td style="font-weight:<?= $estilo2 ?>;">$<?= $total_prec_vta5_tienda ?></td>

              <td>Bs<?= $prec_vta1_tienda ?></td>
              <td style="font-weight:<?= $estilo3 ?>;">Bs<?= number_format($total_prec_vta1_tienda, 2, ',', '.'); ?></td>

              <td>Bs<?=  $prec_vta3_costo_tienda  ?></td>
              <td style="font-weight:<?= $estilo4 ?>;">Bs<?= number_format($total_prec_vta3_costo_tienda, 2, ',', '.'); ?></td>



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
        <td><b>$<?= number_format($total_prec_vta5_todo, 0, ',', '.'); ?></b></td>
        <td></td>
        <td><b>Bs<?= number_format($total_prec_vta3_todo, 2, ',', '.'); ?></b></td>
        <td><b><?= $total_stock_act_previa ?></td>
        <td><b><?= $total_vendido_todo ?></td>
        

        <?php

        $h = 1;
        for ($i = 0; $i < count($total_stock_act_tienda); $i++) {
          $vendido = $total_vendido_tienda[$sedes_ar[$h]];

        ?>
          <td><b><?= $total_stock_act_tienda[$sedes_ar[$h]] ?></b></td>
          <td><b><?= $total_vendido_tienda [$sedes_ar[$h]] ?></b></td>
          
          <td></td>
          <td><b>$<?= number_format($total_prec_vta5_tienda_todo[$sedes_ar[$h]], 0, ',', '.');  ?></b></td>
          <td></td>
          <td><b>Bs<?= number_format($total_prec_vta1_tienda_todo[$sedes_ar[$h]], 2, ',', '.'); ?></b></td>
          <td></td>
          <td><b>Bs<?=number_format( $total_prec_vta3_costo_tienda_todo[$sedes_ar[$h]]); ?></b></td>
          
          



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