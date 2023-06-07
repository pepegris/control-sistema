<?php
ini_set('memory_limit','4096M');
ini_set('max_execution_time',3600);

require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/replica/replica.php';

?>
<link rel="stylesheet" href="../../assets/css/animations.css">
<style>
  img {


    width: 23px;
  }

  ul {
    margin-top: 10px;
  }



  @media (max-width: 900px) {

    ul li {
      font-size: 10px;
    }

    img {


      width: 19px;
    }

    ul {
      margin-top: 10px;
    }

  }
</style>


<div id="body">

  <div class="slideExpandUp">
    <ul class="list-group">
      <li class="list-group-item disabled" style="background-color:black" aria-disabled="true">
        <center><b>Replica</b></center>

      </li>
      <?php

      for ($i = 0; $i < count($sedes_ar); $i++) {

        if ($sedes_ar[$i] != 'Previa Shop') {

          $sede = $sedes_ar[$i];

          $res = Replica($sedes_ar[$i]);
          $res1 = $res['fec_emis'];
          
          if ($res1==null) {

            $fecha = 'Sincronizando';

          }else{

            $fecha = $res1->format('d-m-Y');

            $fecha_actual = date("d-m-Y");

            $fecha1 = date("d-m-Y", strtotime($fecha_actual . "- 3 day"));
            $fecha2 = date("d-m-Y", strtotime($fecha_actual . "- 7 day"));

            $past = new DateTime($fecha);
            $now_1 = new DateTime($fecha1);
            $now_2 = new DateTime($fecha2);
  
          }



            if ($past  >= $now_1) {

              echo "<li class='list-group-item'><span><b style='color:black'> <a href='detal.php?sede=$sede'>$sede</a> </b> /  $fecha</span> <img src='./img/cloud-check.svg' alt=''> </li>";
            } elseif ($past  >= $now_2) {
              echo "<li class='list-group-item'><span><b style='color:black'> <a href='detal.php?sede=$sede'>$sede</a> </b> /  $fecha</span>  <img src='./img/cloud-sync.svg' alt=''> </li>";
            } else {
              echo "<li class='list-group-item'><span><b style='color:black'> <a href='detal.php?sede=$sede'>$sede</a> </b> /  $fecha</span>  <img src='./img/cloud-upload.svg' alt=''> </li>";
            }

            

        }
      }




      ?>

    </ul>
  </div>






</div>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<br><br><br>

<?php include '../../includes/footer.php'; ?>