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
    form,
    td {
      font-size: 12px;


    }
  </style>
<center><h1>Fallas Con Pedidos</h1></center>
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
        <th scope='col'>Costo Bs</th>
        <th scope='col'>Fecha ult Costo</th>
        <th scope='col'>Precio Bs</th>
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
              echo "<th scope='col'>Cant Env $sede</th>";
              echo "<th scope='col'>Total Vendido $sede</th>";
              echo "<th scope='col'>Ref $sede</th>";
              echo "<th scope='col'>Status $sede</th>";
              echo "<th scope='col'>Pedido $sede</th>";
              echo "<th scope='col'>Fallas $sede</th>";
            }

            ?>
        <?php }
        } ?>

      </tr>
    </thead>
    <tbody>
      <?php

      $res0 = getArt('Previa Shop', $linea, 0 ,$almacen );

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

        $stock_act_1 = round($res0[$e]['stock_act']);
        $stock_act =  $stock_act_1 - $pedido; 
        $total_stock_act_previa += $stock_act;


        $precio = $res0[$e]['prec_vta3'];
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

          $res2 = getCompras($co_art);
          $prec_vta =  number_format($res2['prec_vta'], 2, ',', '.');
          $fec_lote = $res2['fec_lote'];

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
          <td style="font-weight:<?= $estilo1 ?>;"><?= $total_vendido ?></td>
          <td style="font-weight:<?= $estilo2 ?>;"><?= $stock_act ?></td>


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

              $res4 = getFactura($sedes_ar[$f], $co_art, $fecha1, $fecha2 , null);
              $total_enviado = number_format($res4['total_art'], 0, ',', '.');
              $total_enviado_tienda[$sedes_ar[$f]] += $total_enviado;

              $res5 = getReng_fac($sedes_ar[$f],  $co_art, $fecha1, $fecha2);
              $vendido_tienda = number_format($res4, 0, ',', '.');
              $total_vendido_tienda[$sedes_ar[$f]] += $vendido_tienda;

              $res6 = getArt($sedes_ar[$f], $linea, $co_art , null );
              $prec_vta5_tienda = round($res6[0]['prec_vta5']);

              /* REVISANDO SI TIENE COTIZACION O PEDIDO */
              $test1 = getPedidos($sedes_ar[$f], $co_art);

              $total_pedido = $res7['total_art'];
              $status = $res7['status'];
              $documento = $res7['fact_num'];

              $estilo1='normal';
              $estilo2='normal';
              $estilo3='normal';

              if ($stock_act_tienda >=1) {
                $estilo1='bold';
              }else {
                $estilo1='normal';
              }

              if ($total_enviado >=1) {
                $estilo2='bold';
              }else {
                $estilo2='normal';
              }

              if ($vendido_tienda >=1) {
                $estilo3='bold';
              }else {
                $estilo3='normal';
              }

          ?>
              <td style="font-weight:<?= $estilo1 ?>;"><?= $stock_act_tienda  ?></td>
              <td style="font-weight:<?= $estilo2 ?>;"><?= $total_enviado  ?></td>
              <td style="font-weight:<?= $estilo3 ?>;"><?= $vendido_tienda ?></td>
              <td>$<?= $prec_vta5_tienda ?></td>
              <td><?php
                  if ($status== null) {
                    echo "<p style='color:red'>Sin Informacion/p>";
                  } else {
                    switch ($status) {

                      case 0:
                        echo "<p style='color:green'>Sin Procesar</p> $documento";
                        break;
                      case 1:
                        echo "<p style='color:yellow'>Parc/Procesada</p> $documento";
                        break;
      

                      default:
                        echo "<p style='color:orange'>Sin Pedido Nuevo</p>";
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
        <td colspan="10"></td>
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
          <td><?= $total_enviado_tienda[$sedes_ar[$h]] ?></td>
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