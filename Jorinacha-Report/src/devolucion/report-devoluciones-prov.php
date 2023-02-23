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

  $art = $_GET['art'];
  $linea = $_GET['linea'];
  $doc1 = $_GET['doc1'];
  $doc2 = $_GET['doc2'];

  var_dump($art);
  if ($art=='') {
    echo "VACIO";
  }
  var_dump('<br>');
  var_dump($linea);
  echo "$linea";
  var_dump('<br>');
  var_dump( $doc1 );
  var_dump('<br>');
  var_dump( $doc2 );

?>

  <center>
    <h1>Devoluciones <?= $fecha_titulo1 ?> - <?= $fecha_titulo2  ?></h1>
    <h3>Proveedores</h3>
  </center>
  <table class='table table-dark table-striped'>
    <thead>



      <tr>
        <th scope='col'>N°</th>
        <th scope='col'>N° Devolución</th>
        <th scope='col'>Fecha</th>
        <th scope='col'>Proveedor</th>
        <th scope='col'>Cod Art</th>
        <th scope='col'>Descripción</th>
        <th scope='col'>N° Compra</th>
        <th scope='col'>Fecha Últ Compra</th>
        <th scope='col'>Último Costo</th>
        <th scope='col'>Precio Actual</th>


      </tr>
    </thead>
    <tbody>

      <?php

    $n = 1;




      $res = getDev_pro('Previa Shop', $fecha1, $fecha2, $art, $linea);



      for ($t = 0; $t < count($res); $t++) {


        $art_des = $res[$t]['art_des'];
        $prec_vta5 = $res[$t]['prec_vta5'];

        $stock_act = round($res[$t]['stock_act']);

        $total_stock_act_dev_pro += $stock_act;

        $dev_pro_fact = $res[$t]['dev_pro_fact'];
        $dev_pro_descrip = $res[$t]['dev_pro_descrip'];
        $dev_pro_fec_emis = $res[$t]['dev_pro_fec_emis'];
        $fecha_dev_pro = 'fecha dev';
        $reng_dvp_total_art = round($res[$t]['reng_dvp_total_art']);

        $total_stock_reng_dvp += $reng_dvp_total_art;

        $res2 = getCompras('Previa Shop', $co_art);

        $compras_fact = $res2[$i]['compras_fact'];
        $com_fecha = $res2[$i]['com_fecha'];
        $fecha_com = 'fecha com';
        $com_total_art = round($res2[$i]['com_total_art']);
        $tot_neto = $res2[$i]['tot_neto'];

        $total_stock_com += $com_total_art;


        echo "
            <tr>
            <th scope='row'>$n</th>
            <td>$dev_pro_fact</td>
            <td>$fecha_dev_pro</td>
            

            <td>$co_art</td>
            <td>$art_des</td>

            <td>$compras_fact</td>
            <td>$fecha_com</td>
            <td>$tot_neto</td>
            <td>$prec_vta5</td>
 
            </tr>";
        $n++;
      }






      echo "
      <tr>
    
      <th colspan='8' >Totales</th>
      <td>" . $total_stock_act_dev_pro . "</td>
      <th colspan='3' ></th>
      <td>" . $total_stock_com . "</td>
      <th colspan='3' ></th>
      <td>" . $total_stock_reng_dvp . "</td>
      </tr>
      </tbody>
      </table>";












      ?>



    <?php


  } else {
    header("location: form.php");
  }




  include '../../includes/footer.php'; ?>