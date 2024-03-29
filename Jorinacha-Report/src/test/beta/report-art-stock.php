<?php
ini_set('memory_limit','4096M');
ini_set('max_execution_time',3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

if ($_POST) {


  $linea = $_POST['linea'];
  /*   $fecha1 = $_POST['fecha1'];
  $fecha2 = $_POST['fecha2']; */
  $fecha1 = date("Ymd", strtotime($_POST['fecha1']));
  $fecha2 = date("Ymd", strtotime($_POST['fecha2']));

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
<center><h1>Fallas Beta</h1></center>
  <table class="table table-dark table-striped" id="tblData">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope='col'>Codigo</th>
        <th scope='col'>Marca</th>
        <th scope='col'>Modelo</th>
        <th scope='col'>Escala</th>
        <th scope='col'>Color</th>
        <th scope='col'>Desc</th>
        <th scope='col'>Costo Bs</th>
        <th scope='col'>Fecha ult Costo</th>
        <th scope='col'>Precio Bs</th>
        <th scope='col'>Ref</th>
        <th scope='col'>Total Vendido</th>
        <?php

        for ($i = 0; $i < count($sedes); $i++) {




          if ($sedes[$i] != null) {

            $sede = $sedes[$i];

        ?>
            <th scope='col'><?= $sede ?></th>
            <?php
            if ($sedes[$i] != 'Previa Shop') {
              echo "<th scope='col'>Cant Env $i</th>";
              echo "<th scope='col'>Total Vendido $i</th>";
              echo "<th scope='col'>Ref $i</th>";
              echo "<th scope='col'>Status $i</th>";
              echo "<th scope='col'>Pedido $i</th>";
              echo "<th scope='col'>Fallas $i</th>";
            }

            ?>
        <?php }
        } ?>

      </tr>
    </thead>
    <tbody>
      <?php

      $res0 = getArt('Previa Shop', $linea, 0);

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
          <td><?= $co_cat ?></td>
          <td><?= $co_color ?></td>
          <td><?= $desc ?></td>
          <?php
          $g = 1;
          $total_vendido = 0;
          for ($i = 0; $i < count($sedes); $i++) {

            if ($sedes[$g] != null) {

              $res1 = getReng_fac($sedes[$g],  $co_art, $fecha1, $fecha2);
              $total_vendido += round($res1);
            }
            $g++;
          }

          $res2 = getCompras($co_art);
          $prec_vta = $res2['prec_vta'];
          $fec_lote = $res2['fec_lote'];


          ?>

          <td>Bs<?php
                if ($prec_vta == null) {
                  echo 0;
                } else {
                  echo $prec_vta ;
                }
                ?></td>
          <td><?php
              if ($fec_lote == null) {
                echo "N/A";
              } else {
                echo $fec_lote->format('Y-m-d');
              }
              ?></td>
          <td><?= $prec_vta1 ?></td>
          <td><?= $prec_vta5 ?></td>
          <td><?= $total_vendido ?></td>
          <td><?= $stock_act ?></td>


          <!-- TIENDAS -->
          <?php
          $f = 1;
          for ($i = 0; $i < count($sedes); $i++) {


            if ($sedes[$f] == null) {
              $f++;
            } else {

              $res3 = getArt_stock_tiendas($sedes[$f], $co_art);
              $stock_act_tienda = round($res3[0]['stock_act']);
              $total_stock_act_tienda[$sedes[$f]] += $stock_act_tienda;

              $res4 = getFactura($sedes[$f], $co_art, $fecha1, $fecha2);
              $total_enviado = round($res4['total_art']);
              $total_enviado_tienda[$sedes[$f]] += $total_enviado;

              $res5 = getReng_fac($sedes[$f],  $co_art, $fecha1, $fecha2);
              $vendido_tienda = round($res5);
              $total_vendido_tienda[$sedes[$f]] += $vendido_tienda;

              $res6 = getArt($sedes[$f], $linea, $co_art);
              $prec_vta5_tienda = round($res6[0]['prec_vta5']);

              /* REVISANDO SI TIENE COTIZACION O PEDIDO */
              $test1 = getPedidos($sedes[$f], $co_art);
              if ($test1 != null) {
                $res7=$test1;

              }else {
                $test2=getCotizacion($sedes[$f], $co_art);
                if ($test2 != null) {
                  $res7=$test2;
                }

              }

              $total_pedido = $res7['total_art'];
              $status = $res7['status'];
              $documento = $res7['doc'];

          ?>
              <td><?= $stock_act_tienda  ?></td>
              <td><?= $total_enviado  ?></td>
              <td><?= $vendido_tienda ?></td>
              <td>$<?= $prec_vta5_tienda ?></td>
              <td><?php
                  if ($status== null) {
                    echo "Sin Pedido";
                  } else {
                    switch ($status) {
                      case 0:
                        echo "<p style='color:orangered'>Sin Procesar</p> $documento";
                        break;
                      case 1:
                        echo "<p style='color:yellow'>Parc/Procesada</p> $documento";
                        break;
                      case 2:
                        echo "<p style='color:green'>Procesada</p> $documento";
                        break;

                      default:
                        echo "<p style='color:red'>Sin ningun Pedido</p>";
                        break;
                    }
                  }

                  ?></td>
              <td><?php
                  if ($total_pedido == null) {
                    echo 0;
                  } else {
                    echo "$total_pedido";
                  }
                  ?></td>
              <td></td>


          <?php $f++;
            }
          }
          $n++ ?>
        </tr>




      <?php  } ?>
      <tr>
        <th ></th>
        <td colspan="9"></td>
        <td>
          <h4>Total</h4>
        </td>
        <td><?= $total_stock_act_previa ?></td>
        <?php

        $h = 1;
        for ($i = 0; $i < count($total_stock_act_tienda); $i++) {
          $vendido = $total_vendido_tienda[$sedes[$h]];

        ?>
          <td><?= $total_stock_act_tienda[$sedes[$h]] ?></td>
          <td><?= $total_enviado_tienda[$sedes[$h]] ?></td>
          <td><?= $vendido  ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>

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
  Cerrar(null);
} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>