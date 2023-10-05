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







for ($e = 1; $e <= count($sedes_ar); $e++) {

  $sede = $sedes_ar[$e];

  ?>

<center><h1><?= $sede  ?></h1></center>
  
  <table class='table table-dark table-striped'>
    <thead>



      <tr>
        <th scope='col'>N°</th>
        <th scope='col'>Tienda</th>
        <th scope='col'>Codigo</th>
        <th scope='col'>Descrip</th>
        <th scope='col'>Fecha</th>

      </tr>
    </thead>
    <tbody>
      

      <?php



        $res = getDev_pro($sede, $fecha1, $fecha2 , $art,null);

        $n = 1;
        for ($i = 0; $i < count($res); $i++) {

          
          $fact_num = $res[$i]['fact_num'];
          $descrip = $res[$i]['descrip'];
          $fec_emis = $res[$i]['fec_emis'];
          $fecha = $fec_emis->format('d-m-Y');

          echo "
        <tr>
        <th scope='row'>$n</th>
        <td>$sede</td>
        <td>$fact_num</td>
        <td>$descrip</td>
        <td>$fecha </td>

  
        </tr>";
          $n++;
        }
 




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