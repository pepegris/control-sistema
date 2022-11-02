<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/efec-tarj/efec-tarj.php';




if (isset($_GET)) {


  $tipo_cob = $_GET['tipo_cob'];
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));






?>


  <center>
    <h1>Cobros</h1>
  </center>
  <?php

  for ($i = 1; $i < count($sedes_ar); $i++) {

    $res = getReng_tip($sedes_ar[$i], 'todos', $fecha1, $fecha2);

    echo "<center><h2>" . $sedes_ar[$i]. "</h2></center>";
    var_dump($sedes_ar[$i]);
    echo "<br>";
    var_dump(count($res[$i]));
    var_dump($res[$i]);
    echo "<br>";
    var_dump($res[$i][$i]);
    echo "<br>";
    var_dump($res[$i][$i][$i]);
    echo "  <table class='table table-dark table-striped' >
    <thead>
      <tr>
              <th scope='col'>Fecha</th>
              <th scope='col'>Tipo</th>
              <th scope='col'>Factura</th>
              <th scope='col'>Cobro</th>
              <th scope='col'>Mov</th>
  
              <th scope='col'>Banco</th>
              <th scope='col'>Monto</th>
      </tr>
    </thead>
    <tbody>";

    for ($e = 0; $e < count($res[$i]); $e++) {

      $tipo_cob =$res[$i][$e];
      $doc_num =$res[$i][$e];
      $cob_num =$res[$i][$e];
      $movi =$res[$i][$e];
      $nombre_ban =$res[$i][$e];
      $mont_doc =$res[$i][$e];
      $fecha =$res[$i][$e];


      echo "
      
      <td>$fecha</td>

      <td>$tipo_cob</td>
      <td>$doc_num</td>
      <td>$cob_num</td>

      <td>$movi</td>
      <td>$nombre_ban</td>
      <td>$mont_doc</td>";
    }

    echo "</tbody>
          </table>";

    if ($res[$i] == null) {

      echo "<center><h1>ERROR</h1>";
      echo "<h3>No es Posible hacer conexion con la base de dato de " . $sedes_ar[$i]. " </h3>";
      echo "</center>";

    }
  }




  ?>



<?php
  var_dump($fecha2);
  var_dump($tipo_cob);
  var_dump($reng_tip);
  Cerrar(null);
} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>