<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/devolucion/dev.php';


if (isset($_GET)) {


  $fecha_titulo1 = date("d/m/Y", strtotime($_GET['fecha1']));
  $fecha_titulo2 = date("d/m/Y", strtotime($_GET['fecha2']));
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));

  $art=$_GET['art'];
  $linea=$_GET['linea'];

  var_dump($art);
  var_dump($linea);


  

?>

  <center>
    <h1>Devoluciones <?= $fecha_titulo1 ?> - <?= $fecha_titulo2  ?></h1>

    <h3> Clientes </h3>
  </center>

  <?php







for ($e = 1; $e < count($sedes_ar); $e++) {

  $sede = $sedes_ar[$e];

  ?>

<center><h1><?= $sede  ?></h1></center>
  
  <table class='table table-dark table-striped'>
    <thead>



      <tr>
        <th scope='col'>N°</th>
        <th scope='col'>Tienda</th>
        <th scope='col'>Codigo</th>
        <th scope='col'>Marca</th>
        <th scope='col'>Modelo</th>
        <th scope='col'>Descripción</th>
        <th scope='col'>Escala</th>
        <th scope='col'>Color</th>
        <th scope='col'>Stock</th>

        <th scope='col'>Num Dev</th>
        <th scope='col'>Comentario</th>
        <th scope='col'>Fecha Dev</th>
        <th scope='col'>Pares</th>



        <th scope='col'>Num Fac de Compra</th>
        <th scope='col'>Fecha Comp</th>
        <th scope='col'>Pares</th>

      </tr>
    </thead>
    <tbody>
      

      <?php



        $res = getDev_cli($sede, $fecha1, $fecha2 , $art);
        var_dump($res);

        $n = 1;
        for ($i = 0; $i < count($res); $i++) {


          $co_art = $res[$i]['co_art'];
          $co_lin = $res[$i]['lin_des'];
          $co_subl = $res[$i]['subl_des'];
          $co_cat = $res[$i]['cat_des'];
          $co_color = $res[$i]['des_col'];
          $ubicacion = $res[$i]['ubicacion'];
          $stock_act = round($res[$i]['stock_act']);

          $total_stock_act_dev_cli += $stock_act;

          $dev_cli_fact = $res[$i]['dev_cli_fact'];
          $dev_cli_comentario = $res[$i]['dev_cli_comentario'];
          $dev_cli_fec_emis = $res[$i]['dev_cli_fec_emis'];
          $fecha_dev_cli = 'fecha';
          $reng_dvc_total_art = round($res[$i]['reng_dvc_total_art']);

          $total_stock_reng_dvc += $reng_dvc_total_art;

          $res2 = getCompras($sede,  $fecha2 , $co_art );

          $compras_fact = $res2[$i]['compras_fact'];
          #$com_fecha = $res2[$i]['com_fecha'];
          $fecha_com = 'fecha';
          $com_total_art = round($res2[$i]['com_total_art']);

          $total_stock_com_total_art += $com_total_art;

          echo "
        <tr>
        <th scope='row'>$n</th>
        <td>$sede</td>
        <td>$co_art</td>
        <td>$co_lin</td>
        <td>$co_subl </td>
        <td>$ubicacion</td>
        <td>$co_cat </td>
        <td>$co_color</td>
        <td>$stock_act</td>

        <td>$dev_cli_fact</td>
        <td>$dev_cli_comentario</td>
        <td>$fecha_dev_cli</td>
        <td>$reng_dvc_total_art</td>

        <td>$compras_fact</td>
        <td>$fecha_com</td>
        <td>$com_total_art</td>
  
        </tr>";
          $n++;
        }
 




      echo "
      <tr>
    
      <th colspan='8' >Totales</th>
      <td>" . $total_stock_act_dev_cli . "</td>
      <th colspan='3' ></th>
      <td>" . $total_stock_com_total_art . "</td>
      <th colspan='2' ></th>
      <td>" . $total_stock_reng_dvc . "</td>
      </tr>
      </tbody>
      </table>";


      $total_stock_act_dev_cli =0;
      $total_stock_com_total_art =0;
      $total_stock_reng_dvc =0;





      /*         <td>" . $total_stock_act_dev_pro . "</td>
        <td>" . $total_stock_com . "</td>
        <td>" . $total_stock_reng_dvp . "</td> */






      }

      ?>



    <?php


  } else {
    header("location: form.php");
  }




  include '../../includes/footer.php'; ?>