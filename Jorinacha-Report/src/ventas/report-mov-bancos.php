<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

if ($_GET) {

  $divisa = $_GET['divisa'];

  $fecha = date("Ymd", strtotime($_GET['fecha1']));



?>

  <style>
    img {


      width: 28px;
    }
  </style>


  <link rel='stylesheet' href='responm.css'>


  

  <?php

  for ($k = 0; $k < count($sedes_ar); $k++) {


    echo "<center> <h2> $sedes_ar[$k] Movimientos de Bancos $fecha</h2> </center>";


  ?>


    <table class="table table-dark table-striped" id="tblData">
      <thead>



        <tr>
          <th scope="col">Cuenta</th>
          <th scope='col'>Numero</th>

          <th scope='col'>Tipo</th>
          <th scope='col'>Nro. Docum</th>

          <th scope='col'>Descripcion</th>
          <th scope='col'>Origen</th>

          <th scope='col'>Debe</th>
          <th scope='col'>Haber</th>
          <th scope='col'>I.T.F</th>

        </tr>

      </thead>
      <tbody>

        <?php

        $mov_banco = getMov_banco($sedes_ar[$k],  $fecha);


        for ($i = 1; $i < count($mov_banco); $i++) {


          $mov_num = $mov_banco[$i];


        ?>
          <tr>

          <td><?= $mov_num   ?></td>
          <td><?= $mov_num   ?></td>

          <td><?= $mov_num   ?></td>
          <td><?= $mov_num   ?></td>

          <td><?= $mov_num   ?></td>
          <td><?= $mov_num   ?></td>

          <td><?= $mov_num   ?></td>
          <td><?= $mov_num   ?></td>
          <td><?= $mov_num   ?></td>



        </td>

          <?php

        }

          ?>



          <tr>
            <td colspan="">
              <h3>Totales</h3>
            </td>



            <td><b></b></td>
            <td><b></b></td>

            <td><b></b></td>
            <td><b></b></td>

            <td><b></b></td>

            <td><b></b></td>
            <td><b></b></td>

            <td><b></b></td>
            <td><b></b></td>
            <td><b></b></td>


            <td></td>

          </tr>

      </tbody>


    </table>

<?php


  }
} else {
  header("location: form.php");
}



include '../../includes/footer.php'; ?>