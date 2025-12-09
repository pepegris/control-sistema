<?php
// --- ACTIVAR VISUALIZACIÓN DE ERRORES ---
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ----------------------------------------

ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/adm/replica/replica.php';

?>
<link rel="stylesheet" href="../../assets/css/animations.css">
<style>
  img { width: 23px; }
  ul { margin-top: 10px; }

  @media (max-width: 900px) {
    ul li { font-size: 10px; }
    img { width: 19px; }
    ul { margin-top: 10px; }
  }
</style>


<div id="body">

  <div class="slideExpandUp">
    <ul class="list-group">
      <li class="list-group-item disabled" style="background-color:black" aria-disabled="true">
        <center><b>Replica</b></center>
      </li>
      <?php
      // Validación de seguridad para evitar errores si no carga el array
      if (isset($sedes_ar) && is_array($sedes_ar)) {
          for ($i = 0; $i < count($sedes_ar); $i++) {

            if ($sedes_ar[$i] != 'Previa Shop') {

              $sede = $sedes_ar[$i];
              $res = Replica($sedes_ar[$i]);
              
              if($res) {
                  $res1 = $res['fec_emis'];

                  if ($res1 == null) {
                    $fecha = 'Sincronizando';
                    $past = null;
                  } else {
                    // Manejo seguro de fechas (Objeto vs String)
                    if (is_object($res1)) {
                        $fecha = $res1->format('d-m-Y');
                    } else {
                        $fecha = $res1; 
                    }

                    $fecha_actual = date("d-m-Y");
                    $fecha1 = date("d-m-Y", strtotime($fecha_actual . "- 2 day"));
                    $fecha2 = date("d-m-Y", strtotime($fecha_actual . "- 5 day"));

                    $past = new DateTime($fecha);
                    $now_1 = new DateTime($fecha1);
                    $now_2 = new DateTime($fecha2);
                  }
              } else {
                  $fecha = "Sin Datos";
                  $past = null;
              }

              $res3 = Inventario($sedes_ar[$i]);

              if ($res3 == null) {
                $inventario = "";
              } else {
                $inventario = "Inventario<img src='./img/cart-full.svg' alt=''>";
              }

              // Lógica de Semáforo (Verde, Naranja, Rojo)
              if ($past != null && isset($now_1) && $past >= $now_1) {
                echo "<li class='list-group-item'><span><b style='color:black'> <a href='detal.php?sede=$sede'>$sede</a> </b> /  $fecha</span> <img src='./img/cloud-check.svg' alt=''> $inventario </li>";
              } elseif ($past != null && isset($now_2) && $past >= $now_2) {
                echo "<li class='list-group-item'><span><b style='color:black'> <a href='detal.php?sede=$sede'>$sede</a> </b> /  $fecha</span>  <img src='./img/cloud-sync.svg' alt=''> $inventario </li>";
              } else {
                echo "<li class='list-group-item'><span><b style='color:black'> <a href='detal.php?sede=$sede'>$sede</a> </b> /  $fecha</span>  <img src='./img/cloud-upload.svg' alt=''>  $inventario </li>";
              }
            }
          }
      } else {
          echo "<div class='alert alert-danger'>Error: La variable <b>\$sedes_ar</b> no está definida. Revisa replica.php o mysql.php</div>";
      }
      ?>

    </ul>
  </div>

</div>
<br>
<br>
<br>

<div class="d-grid gap-2 col-6 mx-auto"> 
    
    <a href="import-database.php" class="btn btn-dark btn-lg">
        📂 Ejecutar Import-Database
    </a>

    <a href="panel_control_replicas.php" class="btn btn-dark btn-lg">
        🔄 Reiniciar Suscripciones (Panel Control)
    </a>

    <hr style="border-color: #666;">

    <a href="panel_crear_articulos.php" class="btn btn-dark btn-lg" style="border: 1px solid #ffd700; color: #ffd700;">
        📦 Crear Artículos (De Previa_A a 16 Tiendas)
    </a>

    <a href="#" class="btn btn-dark btn-lg disabled">
        💲 Actualizar Precios (De Previa_A a 16 Tiendas)
    </a>

</div>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<br><br><br>

<?php include '../../includes/footer.php'; ?>