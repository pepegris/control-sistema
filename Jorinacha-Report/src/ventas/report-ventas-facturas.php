<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';          // Trae $sedes_ar y Database()
include '../../services/db_connection.php';  // NECESARIO: Trae ConectarSQLServer()
include '../../services/adm/ventas/diarias.php'; // Trae getFacturaDetalles optimizado

if ($_GET) {

  $fecha_titulo = date("d/m/Y", strtotime($_GET['fecha1']));
  $fecha_titulo2 = date("d/m/Y", strtotime($_GET['fecha2']));
  $fecha1 = date("Ymd", strtotime($_GET['fecha1'])); // Formato SQL YYYYMMDD
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));

?>

  <style>
    img { width: 28px; }
  </style>
  <link rel='stylesheet' href='responm.css'>

  <center>
    <h1>Facturas <?= $fecha_titulo ?> - <?= $fecha_titulo2 ?></h1>
  </center>

  <table class="table table-dark table-striped" id="tblData">
    <thead>
      <tr>
        <th scope='col'>Fecha</th>
        <th scope="col">Cod</th>
        <th scope='col'>Empresa</th>
        <th scope='col'>Documen</th>
        <th scope='col'>Num Fact</th>
        <th scope='col'>Fact Monto</th>
        <th scope='col'>Num Cob</th>
        <th scope='col'>Tipo de Cob</th>
        <th scope='col'>Cob Monto</th>
        <th scope='col'>Cod Caja</th>
        <th scope='col'>Caja</th>
      </tr>
    </thead>
    <tbody>

      <?php
      // Inicializamos totales para que no den error undefined variable
      $total_pagos_bs = 0;
      $total_pagos_usd = 0;

      // Bucle comenzando en 1 para saltar "Previa Shop"
      for ($i = 1; $i < count($sedes_ar); $i++) {
        
        $sede = $sedes_ar[$i];
        $cod = Cliente($sede);

        // 1. OBTENER NOMBRE BD Y CONECTAR (NUEVA LÓGICA)
        $db_name = Database($sede);
        $conn = ConectarSQLServer($db_name);

        // Validamos si conectó
        if ($conn) {
            
            // 2. LLAMAR FUNCIÓN PASANDO LA CONEXIÓN ($conn)
            $res_factura = getFacturaDetalles($conn, $fecha1, $fecha2);
            
            // 3. CERRAR CONEXIÓN (Liberar recursos)
            sqlsrv_close($conn);

            // 4. VERIFICAR SI HAY DATOS Y RECORRER
            if (is_array($res_factura)) {
                
                for ($x = 0; $x < count($res_factura); $x++) {

                  $tp_doc_cob = $res_factura[$x]['tp_doc_cob'];
                  $doc_num = $res_factura[$x]['FACTURA'];
                  $neto = $res_factura[$x]['neto'];

                  $cob_num = $res_factura[$x]['COBROS'];
                  
                  // Manejo seguro de fecha (a veces viene null)
                  $fecha = "";
                  if (isset($res_factura[$x]['fec_cob']) && $res_factura[$x]['fec_cob'] instanceof DateTime) {
                      $fecha = $res_factura[$x]['fec_cob']->format("d-m-Y");
                  }

                  $tip_cob = $res_factura[$x]['tip_cob'];
                  $mont_doc = $res_factura[$x]['mont_doc'];
                  $cod_caja = $res_factura[$x]['cod_caja'];
                  $des_caja = $res_factura[$x]['des_caja'];

                  // Aquí deberías sumar los totales si quieres que aparezcan abajo
                  // Ejemplo (Ajusta la lógica según tu moneda):
                  // if ($cod_caja == '01') $total_pagos_bs += $mont_doc;
                  // else $total_pagos_usd += $mont_doc;

              ?>
                  <tr>
                    <td><?= $fecha   ?></td>
                    <td><?= $cod   ?></td>
                    <td><?= $sede ?></td>
                    <td><?= $tp_doc_cob ?></td>
                    <td><?= $doc_num    ?></td>
                    <td><?= number_format($neto, 2, ',', '.')  ?></td>
                    <td><?= $cob_num  ?></td>
                    <td><?= $tip_cob  ?></td>
                    <td><?= number_format($mont_doc, 2, ',', '.')  ?></td>
                    <td><?= $cod_caja  ?></td>
                    <td><?= $des_caja  ?></td>
                  </tr>
              <?php
                } // Fin for x
            } // Fin if is_array
        } // Fin if conn
      } // Fin for i
      ?>

      <tr>
        <td colspan="6">
          <h3>Totales</h3>
        </td>
        <td><b>Bs<?= number_format($total_pagos_bs, 2, ',', '.')  ?></b></td>
        <td><b>$<?= number_format($total_pagos_usd, 2, ',', '.')  ?></b></td>
      </tr>

    </tbody>
  </table>

<?php
} else {
  header("location: form.php");
}

include '../../includes/footer.php'; ?>