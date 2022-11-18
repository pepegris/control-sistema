<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/devolucion/dev.php';


if (isset($_GET)) {



  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));



?>

  <center>
    <h1>Devoluciones</h1>
  </center>

  <?php







  for ($o = 0; $o < count($consultas); $o++) {



    if ($consultas[$o] == "Devolucion Cliente") {


      echo "<center> <h3>" . $consultas[$o] . "</h3></center>";
      echo "  <table class='table table-dark table-striped' >
          <thead>
      
            <tr>
                    <th scope='col'>N°</th>
                    <th scope='col'>Tienda</th>
                    <th scope='col'>Codigo</th>
                    <th scope='col'>Marca</th>
                    <th scope='col'>Modelo</th>
                    <th scope='col'>Escala</th>
                    <th scope='col'>Color</th>
                    <th scope='col'>Descripción</th>
                    <th scope='col'>Stock</th>

                    <th scope='col'>Num Dev</th>                    
                    <th scope='col'>Fecha Dev</th>
                    <th scope='col'>Pares</th>
                    <th scope='col'>Comentario</th>
                    

                    <th scope='col'>Num Fac de Compra</th>
                    <th scope='col'>Fecha Comp</th>
                    <th scope='col'>Pares</th>
                    
            </tr>
          </thead>
          <tbody>";
    } elseif ($consultas[$o] == "Devolucion Proveedores") {


      echo "<center> <h3>" . $consultas[$o] . "</h3></center>";
      echo "  <table class='table table-dark table-striped' >
          <thead>
      
            <tr>
                    <th scope='col'>N°</th>
                    <th scope='col'>Tienda</th>
                    <th scope='col'>Codigo</th>
                    <th scope='col'>Marca</th>
                    <th scope='col'>Modelo</th>
                    <th scope='col'>Escala</th>
                    <th scope='col'>Color</th>
                    <th scope='col'>Descripción</th>
                    <th scope='col'>Stock</th>

                    <th scope='col'>Num Dev</th>                    
                    <th scope='col'>Fecha Dev</th>
                    <th scope='col'>Pares</th>
                    <th scope='col'>Comentario</th>
                    

                    <th scope='col'>Num Fac de Compra</th>
                    <th scope='col'>Fecha Comp</th>
                    <th scope='col'>Pares</th>
                    
            </tr>
          </thead>
          <tbody>";
    }

    $consulta = $consultas[$o];
    $n = 1;


    for ($e = 0; $e < count($sedes_ar); $e++) {

      $sede = $sedes_ar[$e];
      
      if ($consulta == "Devolucion Cliente") {

        $res =getDev_cli($sede, $fecha1, $fecha2);

        for ($i=0; $i < count($res); $i++) {
          
          
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
        $fecha_dev_cli = $dev_cli_fec_emis->format('d-m-Y');
        $reng_dvc_total_art = $res[$i]['reng_dvc_total_art'];

        $compras_fact = $res[$i]['compras_fact'];
        $comp_comentario = $res[$i]['comp_comentario'];
        $com_fecha = $res[$i]['com_fecha'];
        $fecha_com = $com_fecha->format('d-m-Y');
        $com_total_art = $res[$i]['com_total_art'];

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
        <td>$comp_comentario</td>
        <td>$fecha_com</td>
        <td>$com_total_art</td>
  
        </tr>";
        $n++;
        }




        
      } elseif ($consulta == "Devolucion Proveedores") {

        $res =getDev_pro($sede, $fecha1, $fecha2);

        for ($i=0; $i < count($res); $i++) {
          
          
          $co_art = $res[$i]['co_art'];
          $co_lin = $res[$i]['lin_des'];
          $co_subl = $res[$i]['subl_des'];
          $co_cat = $res[$i]['cat_des'];
          $co_color = $res[$i]['des_col'];
          $ubicacion = $res[$i]['ubicacion'];
          $stock_act = round($res[$i]['stock_act']);
  
          $total_stock_act_dev_pro += $stock_act;
  
          $dev_pro_fact = $res[$i]['dev_pro_fact'];
          $dev_pro_descrip = $res[$i]['dev_pro_descrip'];
          $dev_pro_fec_emis = $res[$i]['dev_pro_fec_emis'];
          $fecha_dev_pro = $dev_pro_fec_emis->format('d-m-Y');
          $reng_dvp_total_art = $res[$i]['reng_dvp_total_art'];
  
          $compras_fact = $res[$i]['compras_fact'];
          $comp_comentario = $res[$i]['comp_comentario'];
          $com_fecha = $res[$i]['com_fecha'];
          $fecha_com = $com_fecha->format('d-m-Y');
          $com_total_art = $res[$i]['com_total_art'];
  
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
          <td>$comp_comentario</td>
          <td>$fecha_com</td>
          <td>$com_total_art</td>
    
          </tr>";
          $n++;
          }
        
      } 
      
      
    }




      echo "
        <tr>
      
        <td>" . $total_stock_act_dev_cli. "</td>
        <td>" . $total_stock_act_dev_pro. "</td>
        <td></td>
        </tr>
        </tbody>
        </table>";
      $total_monto = 0;

  }





  ?>



<?php


} else {
  header("location: form.php");
}




include '../../includes/footer.php'; ?>