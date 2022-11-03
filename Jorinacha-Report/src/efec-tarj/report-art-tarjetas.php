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
    echo "  <table class='table table-dark table-striped' >
    <thead>

      <tr>
              <th scope='col'>N°</th>
              <th scope='col'>Tipo Cob</th>
              <th scope='col'>N° Factura</th>
              <th scope='col'>N° Cobro</th>
              <th scope='col'>N° Mov Caja</th>
  
              <th scope='col'>Banco</th>
              <th scope='col'>Monto</th>
              <th scope='col'>Fecha</th>
      </tr>
    </thead>
    <tbody>";
    $n=1;
    $total_mont_doc=0;
    for ($e = 0; $e < count($res); $e++) {

      $tipo_cob =$res[$e]['tip_cob'];
      $doc_num =$res[$e]['doc_num'];
      $cob_num =$res[$e]['cob_num'];
      $movi =$res[$e]['movi'];
      
      if (empty($res[$e]['nombre_ban']) == false) {
        $nombre_ban == 'N/A';
      }else{
        $nombre_ban =$res[$e]['nombre_ban'];
      }
      
      $mont_doc =$res[$e]['mont_doc'];
      $total_mont_doc += $mont_doc;

      $fec_cheq =$res[$e]['fec_cheq'];
      $fecha = $fec_cheq->format('d-m-Y');


      echo "
      <tr>
      <th scope='row'>$n</th>
      <td>$tipo_cob</td>
      <td>$doc_num</td>
      <td>$cob_num</td>

      <td>$movi</td>
      <td>$nombre_ban</td>
      <td>$mont_doc</td>
      <td>$fecha</td>

      </tr>";
      $n++;
    }

    if ($total_mont_doc == 0) {

      echo "</tbody>
      </table>";
      echo "<center><h1>ERROR</h1>";
      echo "<h3>No es Posible hacer conexion con la base de dato de " . $sedes_ar[$i]. " </h3>";
      echo "</center>";

    }else {
      echo "
      <tr>
      <td colspan='6'><h3>Total</h3></td>
      <td>$total_mont_doc</td>
      <td></td>
      </tr>
      </tbody>
            </table>";
    }
  }




  ?>



<?php

  var_dump($res[14]['nombre_ban']);
  var_dump($res[15]['nombre_ban']);
  var_dump(empty($res[14]['nombre_ban']));
  var_dump(empty($res[15]['nombre_ban']));
} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>