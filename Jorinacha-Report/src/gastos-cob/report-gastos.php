<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/gastos-cob/gastos.php';




if (isset($_GET)) {



  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));



?>



  <?php

  for ($i = 0; $i < count($sedes_ar); $i++) {

    echo "<center><h2>" . $sedes_ar[$i] . "</h2></center>";

    
    
    $sede=$sedes_ar[$i] ;

    for ($o = 0; $o < count($consultas); $o++) {

      

      

      if ($consultas[$o] == "Ordenes de Pago" ) {

        $res = getOrd_pago($sede,  $fecha1, $fecha2);

        echo "<center> $sede<h3>" . $consultas[$o] . "</h3></center>";
        echo "  <table class='table table-dark table-striped' >
        <thead>
    
          <tr>
                  <th scope='col'>N°</th>
                  <th scope='col'>Núm. de Ord</th>
                  <th scope='col'>Núm. de Mov</th>
                  <th scope='col'>Banco</th>
                  <th scope='col'>Cta Egreso</th>
                  <th scope='col'>Cta Contable</th>
                  <th scope='col'>Descripción</th>
                  <th scope='col'>Núm. Docum</th>
                  <th scope='col'>Monto</th>
                  <th scope='col'>Fecha</th>
          </tr>
        </thead>
        <tbody>";

      }elseif ($consultas[$o] == "Documentos de Pago" ) {

        $res = getDocum_cp($sede,  $fecha1, $fecha2);

        echo "<center><h3>" . $consultas[$o] . "</h3></center>";
        echo "  <table class='table table-dark table-striped' >
        <thead>
    
          <tr>
                  <th scope='col'>N°</th>
                  <th scope='col'>Núm. de Doc</th>
                  <th scope='col'>Núm. Factura</th>
                  <th scope='col'>Núm. Control</th>
                  <th scope='col'>Proveedor</th>
                  <th scope='col'>Cta Contable</th>
                  <th scope='col'>Descripción</th>
                  <th scope='col'>Monto</th>
                  <th scope='col'>Fecha</th>
          </tr>
        </thead>
        <tbody>";

      }else{

        $res = getMov_caj($sede,  $fecha1, $fecha2);

        echo "<center><h3>" . $consultas[$o] . "</h3></center>";
        echo "  <table class='table table-dark table-striped' >
        <thead>
    
          <tr>
                  <th scope='col'>N°</th>
                  <th scope='col'>Núm. de Mov Caj</th>
                  <th scope='col'>>Cta Egreso</th>
                  <th scope='col'>Cta Contable</th>
                  <th scope='col'>Descripción</th>
                  <th scope='col'>Monto</th>
                  <th scope='col'>Fecha</th>
          </tr>
        </thead>
        <tbody>";
      }

      $n = 1;
      $total_monto = 0;

      for ($e = 0; $e < count($res); $e++) {

      if ($consultas[$o] == "Ordenes de Pago") {

        $ord_num = $res[$e]['ord_num'];
        $mov_num = $res[$e]['mov_num'];
        $cod_cta = $res[$e]['cod_cta'];
        $des_ban = $res[$e]['des_ban'];

        $cta_egre = $res[$e]['cta_egre'];
        $cta_egre_descrip = $res[$e]['cta_egre_descrip'];

        $descrip = $res[$e]['descrip'];
        
        $cheque = $res[$e]['cheque'];

        $fecha_che = $res[$e]['fecha'];
        $fecha = $fec_che->format('d-m-Y');

        $monto = $res[$e]['monto'];
        $total_monto += $monto;

        $cuenta_contable = getCuenta_contable($sede, $ord_num ,  $fecha1, $fecha2);
        $co_cue = $cuenta_contable['co_cue'];
        $descri = $cuenta_contable['descri'];

        echo "
        <tr>
        <th scope='row'>$n</th>
        <td>$ord_num</td>
        <td>$mov_num</td>

        <td>$cod_cta - $des_ban</td>
        <td>$cta_egre - $cta_egre_descrip</td>

        <td>$co_cue - $descri</td>

        <td>$descrip</td>
        <td>$cheque</td>

        <td>$monto</td>
        <td>$fecha</td>
  
        </tr>";
        $n++;

      }elseif ($consultas[$o] == "Documentos de Pago" ) {

        $nro_doc = $res[$e]['nro_doc'];
        $nro_fact = $res[$e]['nro_fact'];
        $n_control = $res[$e]['n_control'];
        $co_cli = $res[$e]['co_cli'];
        $prov_des = $res[$e]['prov_des'];
        $observa = $res[$e]['observa'];


        $monto_net = $res[$e]['monto_net'];
        $total_monto += $monto_net;

        $fec_emis = $res[$e]['fec_emis'];
        $fecha = $fec_emis->format('d-m-Y');

        $cuenta_contable = getCuenta_contable($sede, $nro_doc ,  $fecha1, $fecha2);
        $co_cue = $cuenta_contable['co_cue'];
        $descri = $cuenta_contable['descri'];

        echo "
        <tr>
        <th scope='row'>$n</th>
        <td>$nro_doc</td>
        <td>$nro_fact</td>
        <td>$n_control</td>
  
        <td>$co_cli - $prov_des</td>
        <td>$co_cue - $descri</td>

        <td>$observa</td>
        <td>$monto_net</td>
        <td>$fecha</td>
  
        </tr>";
        $n++;

      }else {

        $mov_num = $res[$e]['mov_num'];
        $descrip = $res[$e]['descrip'];
        $cta_egre = $res[$e]['cta_egre'];
        $cta_egre_descrip = $res[$e]['cta_egre_descrip'];

        $monto_d = $res[$e]['monto_d'];
        $total_monto += $monto_d;

        $fecha_cheq = $res[$e]['fecha'];
        $fecha = $fecha_cheq->format('d-m-Y');

        $cuenta_contable = getCuenta_contable($sede, $mov_num ,  $fecha1, $fecha2);
        $co_cue = $cuenta_contable['co_cue'];
        $descri = $cuenta_contable['descri'];

        echo "
        <tr>
        <th scope='row'>$n</th>
        <td>$mov_num</td>

        <td>$cta_egre - $cta_egre_descrip</td>
        <td>$co_cue - $descri</td>
        
        <td>$descrip</td>
        <td>$monto_d</td>
  
        <td>$movi</td>
        <td>$fecha</td>
  
        </tr>";
        $n++;

      }


      }

      if ($total_mont_doc == 0) {

        echo "</tbody>
        </table>";
        echo "<center><h1>ERROR</h1>";
        echo "<h3>No es Posible hacer conexion con la base de dato de " . $sede . " </h3>";
        echo "</center>";
      } else {
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
  }




  ?>



<?php


} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>