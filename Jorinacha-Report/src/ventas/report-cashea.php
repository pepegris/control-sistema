<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/cashea.php';


if (isset($_GET)) {


  $fecha_titulo1 = date("d/m/Y", strtotime($_GET['fecha1']));
  $fecha_titulo2 = date("d/m/Y", strtotime($_GET['fecha2']));
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));

  

?>

  <center>
    <h1>Factura por Cashea <?= $fecha_titulo1 ?> - <?= $fecha_titulo2  ?></h1>
  </center>

  <?php







for ($e = 1; $e < count($sedes_ar); $e++) {

  $sede = $sedes_ar[$e];

  ?>

<center><h1><?= $sede  ?></h1></center>
  
  <table class='table table-dark table-striped'>
    <thead>
<!-- 				-->


      <tr>
        <th scope='col'>N°</th>
        <th scope='col'>Tienda</th>
        <th scope='col'>Fecha</th>
        <th scope='col'>Factura</th>
        <th scope='col'>Cobros</th>
        <th scope='col'>CI</th>
        <th scope='col'>Cliente</th>
        <th scope='col'>Monto por Cobrar</th>
        <th scope='col'>Monto de la Factura</th>

      </tr>
    </thead>
    <tbody>
      

      <?php



        $res = getCobros_Cashea($sede, $fecha1, $fecha2 );

        $n = 1;
        $total_mont_doc=0;
        for ($i = 0; $i < count($res); $i++) {


          $cob_num = $res[$i]['cob_num'];
          $fec_cob = $res[$i]['fec_cob'];
          $fecha = $fec_cob->format('d-m-Y');
          $doc_num = $res[$i]['doc_num'];
          $mont_doc = $res[$i]['mont_doc'];
          $tot_neto = $res[$i]['tot_neto'];
          $co_cli = $res[$i]['co_cli'];
          $cli_des = $res[$i]['cli_des'];


          $total_mont_doc += $mont_doc;


          echo "
        <tr>
        <th scope='row'>$n</th>
        <td>$sede</td>
        <td>$fecha</td>

        <td>$doc_num</td>
        <td>$cob_num </td>
        
        <td>$co_cli</td>
        <td>$cli_des</td>
        
        <td>$mont_doc</td>
        <td>$tot_neto</td>



        </tr>";
          $n++;
        }
 




      echo "
      <tr>
    
      <th colspan='6' ></th>
      <th  >Totales:</th>
      <td>" . $total_mont_doc . "</td>
      </tr>
      </tbody>
      </table>";


      $total_mont_doc =0;




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