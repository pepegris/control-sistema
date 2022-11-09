<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/cob-eg-ig/gastos.php';


if (isset($_GET)) {



  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));



?>

<table class='table table-dark table-striped' >
          <thead>
      
            <tr>
                    <th scope='col'>N°</th>
                    <th scope='col'>Fecha</th>
                    <th scope='col'>Cod Cta Contable</th>
                    <th scope='col'>Cta Contable</th>
                    <th scope='col'>Cod Cta Egreso</th>
                    <th scope='col'>Cta Egreso</th>
                    
                    <th scope='col'>Empresa</th>
                    <th scope='col'>Tipo de Proc</th>

                    <th scope='col'>Núm. de Proc</th>
                    <th scope='col'>Núm. de Doc</th>

                    <th scope='col'>Descripción</th>
                    <th scope='col'>Monto</th>

            </tr>
          </thead>
          <tbody>



  <?php

  

  for ($i = 0; $i < count($sedes_ar); $i++) {



    $sede = $sedes_ar[$i];

    for ($o = 0; $o < count($consultas); $o++) {



      if ($consultas[$o] == "Ordenes de Pago") {

        $res = getOrd_pago($sede,  $fecha1, $fecha2);


      } elseif ($consultas[$o] == "Documentos de Compras") {

        $res = getDocum_cp($sede,  $fecha1, $fecha2);

    
      } else {

        $res = getMov_caj($sede,  $fecha1, $fecha2);

      }


      $consulta = $consultas[$o];
      $n = 1;
      $total_monto = 0;


      if ($res  != null) {


        for ($e = 0; $e < count($res); $e++) {

          if ($consulta == "Ordenes de Pago") {



            $documento = "Ordenes de Pago";

            $ord_num = $res[$e]['ord_num'];

            $mov_num = $res[$e]['mov_num'];
            $cod_cta = $res[$e]['cod_cta'];
            $des_ban = $res[$e]['des_ban'];

            $cta_egre = $res[$e]['cta_egre'];
            $cta_egre_descrip = $res[$e]['cta_egre_descrip'];

            $descrip = $res[$e]['descrip'];

            $monto = number_format( $res[$e]['monto'], 2, ',', '.');
            $total_monto += $monto;

            $cheque = $res[$e]['cheque'];

            $fecha_che = $res[$e]['fecha'];
            $fecha = $fecha_che->format('d-m-Y');



            $cuenta_contable = getCuenta_contable($sede, $ord_num,  $fecha1, $fecha2);

            $co_cue = $cuenta_contable['co_cue'];
            $des_cue = $cuenta_contable['des_cue'];


            echo "
            <tr>
            <th scope='row'>$n</th>
            <td>$fecha</td>";

            if ($co_cue != null) {
              echo "<td>$co_cue </td>";
              echo "<td> $des_cue</td>";
            } else {
              echo "<td>Sin </td>";
              echo "<td>Contabilizar</td>";
            }

            echo "
 
            <td>$cta_egre </td>
            <td>$cta_egre_descrip</td>
            <td>$sede</td>
            <td>$consulta</td>
            <td>$ord_num</td>
            <td>$cheque</td>
            <td>$descrip</td>
            <td>$monto</td>
            
      
            </tr>";
            $n++;

          } elseif ($consulta == "Documentos de Compras") {


            $documento = "Documentos de Compras";

            $nro_doc = $res[$e]['nro_doc'];
            $nro_fact = $res[$e]['nro_fact'];
            $n_control = $res[$e]['n_control'];

            $co_ingr = $res[$e]['co_ingr'];
            $co_ingr_prov = $res[$e]['co_ingr_prov'];
            $observa = $res[$e]['observa'];


            $monto_net = number_format($res[$e]['monto_net'], 2, ',', '.');
            $total_monto += $monto_net;

            $fec_emis = $res[$e]['fec_emis'];
            $fecha = $fec_emis->format('d-m-Y');

            $cuenta_contable = getCuenta_contable($sede, $nro_doc,  $fecha1, $fecha2);
            $co_cue = $cuenta_contable['co_cue'];
            $des_cue = $cuenta_contable['des_cue'];

            echo "
            <tr>
            <th scope='row'>$n</th>
            <td>$fecha</td>
            
            ";
            
            if ($co_cue != null) {
              echo "<td>$co_cue </td>";
              echo "<td> $des_cue</td>";
            } else {
              echo "<td>Sin </td>";
              echo "<td>Contabilizar</td>";
            }

            echo "
            <td>$co_ingr</td>
            <td>$co_ingr_prov</td>

            <td>$sede</td>
            <td>$consulta</td>

            <td>$nro_doc</td>
            <td>$nro_fact / $n_control</td>
            <td>$observa</td>
            <td>$monto_net</td>
            
      
            </tr>";
            $n++;
          } else {

           

            $documento =  "Movimiento de Caja";

            $mov_num = $res[$e]['mov_num'];
            $descrip = $res[$e]['descrip'];
            $cta_egre = $res[$e]['cta_egre'];
            $cta_egre_descrip = $res[$e]['cta_egre_descrip'];

            $monto_d = number_format($res[$e]['monto_d'], 2, ',', '.') ;
            $total_monto += $monto_d;

            $fecha_cheq = $res[$e]['fecha'];
            $fecha = $fecha_cheq->format('d-m-Y');

            $cuenta_contable = getCuenta_contable($sede, $mov_num,  $fecha1, $fecha2);
            $co_cue = $cuenta_contable['co_cue'];
            $des_cue = $cuenta_contable['des_cue'];


            echo "
            <tr>
            <th scope='row'>$n</th>
            <td>$fecha</td>";

            if ($co_cue != null) {
              echo "<td>$co_cue </td>";
              echo "<td> $des_cue</td>";
            } else {
              echo "<td>Sin </td>";
              echo "<td>Contabilizar</td>";
            }

            echo "
            <td>$cta_egre</td>
            <td>$cta_egre_descrip</td>

            <td>$sede</td>
            <td>$consulta</td>

            <td>$mov_num</td>
            <td></td>

            <td>$descrip</td>
            <td>$monto_d</td>  
            </tr>";
            $n++;
          }
        }
      }

      if ( $res == null or $total_monto == 0 ) {

        echo "<tr><th colspan=13 ><center>$sede No hay Información Disponible $consulta</center></th></tr>";

       
      }
    }
  }




  ?>



<?php



} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>

        </tbody>
        </table>