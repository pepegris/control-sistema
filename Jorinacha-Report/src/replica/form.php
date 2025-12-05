<div id="body">
  <div class="slideExpandUp">
    <ul class="list-group">
      <li class="list-group-item disabled" style="background-color:black; color:white;" aria-disabled="true">
        <center><b>Estado de Réplica (Facturación)</b></center>
      </li>
      
      <?php
      // 1. Definir fechas de referencia UNA SOLA VEZ antes del bucle (Optimización)
      $fecha_actual_str = date("d-m-Y");
      $now_warning = new DateTime(date("d-m-Y", strtotime("-2 day"))); // 2 días atrás (Amarillo)
      $now_danger  = new DateTime(date("d-m-Y", strtotime("-5 day"))); // 5 días atrás (Sincronizando/Rojo)

      for ($i = 0; $i < count($sedes_ar); $i++) {
        
        $sede = $sedes_ar[$i];

        if ($sede != 'Previa Shop') {

          // A. Obtener datos
          $res_replica = Replica($sede);
          $res_inventario = Inventario($sede);

          // B. Procesar Inventario (Icono)
          $icon_inventario = "";
          if ($res_inventario != null && isset($res_inventario['cerrado'])) {
             $icon_inventario = " <span class='badge badge-info' style='font-size:0.8em'>Inventario <img src='./img/cart-full.svg' style='width:15px'></span>";
          }

          // C. Procesar Fechas y Estado
          $fecha_mostrar = 'Sin Conexión';
          $icon_status = './img/cloud-off.svg'; // Icono por defecto (Error)
          $li_style = ""; // Estilo por defecto

          // Verificamos si la consulta trajo datos
          if ($res_replica != null && isset($res_replica['fec_emis'])) {
              
              // Es un objeto DateTime de SQL Server
              $obj_fecha = $res_replica['fec_emis']; 
              $fecha_mostrar = $obj_fecha->format('d-m-Y');
              
              // Comparaciones
              if ($obj_fecha >= $now_warning) {
                  // Caso: AL DÍA (Menos de 2 días)
                  $icon_status = './img/cloud-check.svg';
                  
              } elseif ($obj_fecha >= $now_danger) {
                  // Caso: RETRASO LEVE (Entre 2 y 5 días)
                  $icon_status = './img/cloud-sync.svg'; 
                  
              } else {
                  // Caso: DESACTUALIZADO (Más de 5 días)
                  $icon_status = './img/cloud-upload.svg';
              }
          } else {
              // Caso: No conectó o devolvió null
              $fecha_mostrar = "Sincronizando...";
              $icon_status = './img/cloud-upload.svg'; 
          }

          // D. Renderizar
          echo "
          <li class='list-group-item d-flex justify-content-between align-items-center'>
            <span>
                <b style='color:black'> <a href='detal.php?sede=$sede'>$sede</a> </b> 
                <span style='color:#555; font-size:0.9em; margin-left:5px;'>/ $fecha_mostrar</span>
            </span>
            <span>
                $icon_inventario
                <img src='$icon_status' alt='status' title='Estado de réplica'>
            </span>
          </li>";
        }
      }
      ?>
    </ul>
  </div>
</div>