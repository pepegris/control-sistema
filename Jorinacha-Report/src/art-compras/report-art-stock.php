<?php
require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

if ($_POST) {


  $linea = $_POST['linea'];
  $fecha1 = $_POST['fecha1'];
  $fecha2 = $_POST['fecha2'];

  for ($i = 0; $i < 20; $i += 1) {
    $sedes[] = $_POST[$i];
  }


?>


  <style>
    form,
    td {
      font-size: 12px;
    }
  </style>
  <center><h1>Compras</h1></center>
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
        <th scope='col'>Factura de Compra</th>
        <th scope='col'>Fecha Factura</th>
        <th scope='col'>Cantidad</th>
        <th scope='col'>Costo</th>
        <th scope='col'>Ref</th>

        <?php

        for ($i = 0; $i < count($sedes); $i++) {

          if ($sedes[$i] != null) {

            $sede = $sedes[$i];

        ?>
            <th scope='col'><?= $sede ?></th>
        <?php }
        } ?>

      </tr>
    </thead>
    <tbody>
      <?php

      $res1 = getArt('Previa Shop', $linea,0);
      $n = 1;
      for ($e = 0; $e < count($res1); $e++) {

        $co_art = $res1[$e]['co_art'];
        $co_lin = getLin_art($res1[$e]['co_lin']);
        $co_subl = getSub_lin($res1[$e]['co_subl']);
        $co_cat = getCat_art($res1[$e]['co_cat']);
        $co_color = getColores($res1[$e]['co_color']);
        $desc = $res0[$e]['ubicacion'];

        $stock_act = round($res1[$e]['stock_act']);
        $total_stock_act_previa += $stock_act;

        $precio = round($res1[$e]['prec_vta1']);
        $prec_vta1 = number_format($precio, 2, ',', '.');

        $prec_vta5 = round($res1[$e]['prec_vta5']);

      ?>

        <tr>
          <th scope='row'><?= $n ?></th>
          <td><?= $co_art ?></td>
          <td><?= $co_lin ?></td>
          <td><?= $co_subl ?></td>
          <td><?= $desc ?></td>
          <td><?= $co_cat ?></td>
          <td><?= $co_color ?></td>
          <?php

          $res2 = getCompras($co_art);

          $fact_num =  $res2['fact_num'];
          //$res3 =  $res['fec_lote'];
          $fec_lote = $res2['fec_lote'];
          $total_art = $res2['total_art'];
          $prec_vta = $res2['prec_vta'];

          ?>
          <td><?= $fact_num  ?></td>
          <td><?php
              if ($fec_lote == null) {
                echo "N/A";
              } else {
                echo $fec_lote->format('Y-m-d');
              } ?></td>
          <td><?= $total_art ?></td>
          <td><?= $prec_vta ?></td>
          <td><?= $prec_vta5 ?></td>
          <td><?= $stock_act ?></td>
          <?php
          $f = 1;
          for ($i = 0; $i < count($sedes); $i++) {


            if ($sedes[$f] == null) {
              $f++;
            } else {

              $res3 = getArt_stock_tiendas($sedes[$f], $co_art);
              $stock_act_tienda = round($res3[0]['stock_act']);
              $total_stock_act_tienda[$sedes[$f]] += $stock_act_tienda;


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
        <td colspan="6"></td>
        <td>
          <h4>Total</h4>
        </td>
        <td><?= $total_stock_act_previa ?></td>
        <?php

        $h = 1;
        for ($i = 0; $i < count($total_stock_act_tienda); $i++) {

        ?>
          <td><?= $total_stock_act_tienda[$sedes[$h]] ?></td>

        <?php

          $h++;
        }

        ?>
      </tr>

    </tbody>
  </table>
  <script type="text/javascript">
    function exportTableToExcel(tableID, filename = '') {
      var downloadLink;
      var dataType = 'application/vnd.ms-excel';
      var tableSelect = document.getElementById(tableID);
      var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

      // Specify file name
      filename = filename ? filename + '.xls' : 'excel_data.xls';

      // Create download link element
      downloadLink = document.createElement("a");

      document.body.appendChild(downloadLink);

      if (navigator.msSaveOrOpenBlob) {
        var blob = new Blob(['ufeff', tableHTML], {
          type: dataType
        });
        navigator.msSaveOrOpenBlob(blob, filename);
      } else {
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

        // Setting the file name
        downloadLink.download = filename;

        //triggering the function
        downloadLink.click();
      }
    }
  </script>
<!-- 
  <script src="../../assets/js/excel.js"></script> -->
  <center>
    <button onclick="exportTableToExcel('tblData')" class="btn btn-success">Exportar Reporte a EXCEL</button>
  </center>




<?php
} else {
  header("location: form.php");
}

var_dump($total_vendido);


include '../../includes/footer.php'; ?>