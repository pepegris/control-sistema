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

  var_dump(count($consultas));

?>

  <center>
    <h1>Devoluciones <?= $fecha_titulo1 ?> - <?= $fecha_titulo2  ?></h1>
    <h3>Proveedores</h3>
  </center>
  <table class='table table-dark table-striped' >
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







    
    $n = 1;


    for ($e = 1; $e < count($sedes_ar); $e++) {

      $sede = $sedes_ar[$e];

 

        $res = getDev_pro('Previa Shop', $fecha1, $fecha2);

        if (round($res[0]['reng_dvp_total_art']) >= 1 ) {

          for ($t = 0; $t < count($res); $t++) {


            $co_art = $res[$t]['co_art'];
            $co_lin = $res[$t]['lin_des'];
            $co_subl = $res[$t]['subl_des'];
            $co_cat = $res[$t]['cat_des'];
            $co_color = $res[$t]['des_col'];
            $ubicacion = $res[$t]['ubicacion'];
            $stock_act = round($res[$t]['stock_act']);
  
            $total_stock_act_dev_pro += $stock_act;
  
            $dev_pro_fact = $res[$t]['dev_pro_fact'];
            $dev_pro_descrip = $res[$t]['dev_pro_descrip'];
            $dev_pro_fec_emis = $res[$t]['dev_pro_fec_emis'];
            $fecha_dev_pro = $dev_pro_fec_emis->format('d-m-Y');
            $reng_dvp_total_art = round($res[$t]['reng_dvp_total_art']);

            $total_stock_reng_dvp += $reng_dvp_total_art;

            $res2 = getCompras('Previa Shop', $co_art );

            $compras_fact = $res2[$i]['compras_fact'];
            $com_fecha = $res2[$i]['com_fecha'];
            $fecha_com = $com_fecha->format('d-m-Y');
            $com_total_art = round($res2[$i]['com_total_art']);
  
            $total_stock_com += $com_total_art;

  
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
    
            <td>$dev_pro_fact</td>
            <td>$dev_pro_descrip</td>
            <td>$fecha_dev_pro</td>
            <td>$reng_dvp_total_art</td>
    
            <td>$compras_fact</td>
            <td>$fecha_com</td>
            <td>$com_total_art</td>
      
            </tr>";
            $n++;
          }

        } else {
          echo "
          <tr>
          <th scope='row'>No hay Informacion</th>
          <td>$sede</td>
          <td>No hay Informacion</td>
          <td>$sede</td>
          <td>No hay Informacion</td>
          <td>$sede</td>
          <td>No hay Informacion</td>
          <td>$sede</td>
          <td>No hay Informacion</td>
  
          <td>$sede</td>
          <td>No hay Informacion</td>
          <td>$sede</td>
          <td>No hay Informacion</td>
  
          <td>$sede</td>
          <td>No hay Informacion</td>
          <td>$sede</td>
          <td>No hay Informacion</td>
    
          </tr>";
        }
        

        

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