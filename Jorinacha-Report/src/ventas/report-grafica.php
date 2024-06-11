<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';
include '../../services/adm/ventas/graficas.php';

$divisa = $_GET['divisa'];
$linea = $_GET['linea'];
$fecha1 = date("Ymd", strtotime($_GET['fecha1']));
$fecha2 = date("Ymd", strtotime($_GET['fecha2']));

$fecha_titulo1 = date("d/m/Y", strtotime($_GET['fecha1']));
$fecha_titulo2 = date("d/m/Y", strtotime($_GET['fecha2']));

$Month_beg = date("m", strtotime($fecha1));
$Day = date("d", strtotime($fecha2));
$Month_total = date("m", strtotime($fecha2));
$Year = date("Y", strtotime($fecha2));

$busqueda=true;


for ($i = 1; $i < count($sedes_ar); $i++) {

  $sede = $sedes_ar[$i];

  $m = $Month_beg;
  $Month  = $Month_beg;
  for ($k = $Month_beg; $k <= $Month_total; $k++) {

    $cantidadDias = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);

    $fecha_1 =  $Year . '' . $Month . ''  . '01';
    $fecha_2 =  $Year . '' . $Month . ''  . $cantidadDias;

    if ($sede == 'Sucursal Caracas I' && $Month <= 3 && $Year == '2023') {
      $sede = "Comercial Merina";
    } elseif ($sede == 'Sucursal Caracas II' && $Month <= 3 && $Year == '2023') {
      $sede = "Comercial Merina3";
    } elseif ($sede == 'Sucursal Cagua' && $Month <= 5 && $Year == '2023') {
      $sede = "Comercial Kagu";
    } elseif ($sede == 'Sucursal Maturin' && $Month <= 9 && $Year == '2023') {
      $sede = "Comercial Matur";
    } 

    if ($sede == 'Comercial Merina' && $Month > 3 && $Year == '2023') {
      $sede = "Sucursal Caracas I";
    } elseif ($sede == 'Comercial Merina3' && $Month > 3 && $Year == '2023') {
      $sede = "Sucursal Caracas II";
    } elseif ($sede == 'Comercial Kagu' && $Month > 5 && $Year == '2023') {
      $sede = "Sucursal Cagua";
    } elseif ($sede == 'Comercial Matur' && $Month > 9 && $Year == '2023') {
      $sede = "Sucursal Maturin";
    } 

    $ventas = getVendido_Grafica($sede, $fecha_1, $fecha_2, $Month);
    $dev = getDev_Grafica($sede, $fecha_1, $fecha_2, $Month);

    if ($m < 9) {
      $m++;
      $Month = 0 . $m;
    } else {
      $m++;
      $Month = $m;
    }
  }
}



?>

<style>
  body {
    background-color: white;
  }

  h1,
  h2,
  h3,
  h4 {

    color: black;


  }
  #boton{
    background-color: black;
  }
</style>


<head>
  <center>
    <h1> Graficas de Ventas de <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h1>
  </center>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    // Load Charts and the corechart package.
    google.charts.load('current', {
      'packages': ['corechart']
    });


    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ['Articulos', 'Total Vendidos'],

        <?php

        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            group by linea_des
            order by total_art desc";

        $consulta = sqlsrv_query($conn, $sql);
        while ($row = sqlsrv_fetch_array($consulta)) {

          $dev = getDev_Grafica_fac($sede, $row['linea_des']);
          $total = $row['total_art'] - $dev;

          echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
        }
        ?>
      ]);

      var options = {
        title: 'Modelos vendidos en Todas las Tiendas',
        is3D: true,
      };

      var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
      chart.draw(data, options);
    }



    //---------------------------------------------------------------------------------------------||

    google.charts.setOnLoadCallback(drawChartArea);

    function drawChartArea() {
      var data = google.visualization.arrayToDataTable([
        ['Mes', 'Ventas por Mes', 'Devoluciones por Mes'],
        <?php

        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        $m = $Month_beg;
        $Month  = $Month_beg;
        $u = 0 + $Month_beg;
        for ($k = $Month_beg; $k <= $Month_total; $k++) {

          $sql = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
          WHERE mes='$Month'";
          $consulta = sqlsrv_query($conn, $sql);

          $sql2 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
          WHERE mes='$Month'";
          $consulta2 = sqlsrv_query($conn, $sql2);
          $row2 = sqlsrv_fetch_array($consulta2);
          $total2 = $row2['total_dev'];

          while ($row = sqlsrv_fetch_array($consulta)) {

            $total = $row['total_art'] - $total2;
            $mes = Meses($Month);
            echo "['" . $mes . "',$total,$total2],";
            break;
          }
          $u++;

          if ($m < 9) {
            $m++;
            $Month = 0 . $m;
          } else {
            $m++;
            $Month = $m;
          }
        }


        ?>

      ]);

      var options = {
        title: 'Ventas Por Tiendas',
        hAxis: {
          title: 'Meses',
          titleTextStyle: {
            color: '#333'
          }
        },
        vAxis: {
          minValue: 0
        }
      };
      var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }

    //---------------------------------------------------------------------------------------------||


    // Draw the pie chart for Sarah's pizza when Charts is loaded.
    google.charts.setOnLoadCallback(drawSarahChart);

    // Draw the pie chart for the Anthony's pizza when Charts is loaded.
    google.charts.setOnLoadCallback(drawAnthonyChart);

    // Callback that draws the pie chart for Sarah's pizza.
    function drawSarahChart() {

      // Create the data table for Sarah's pizza.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Topping');
      data.addColumn('number', 'Slices');
      data.addRows([
        <?php

        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            group by linea_des
            order by total_art desc";

        $consulta = sqlsrv_query($conn, $sql);
        while ($row = sqlsrv_fetch_array($consulta)) {

          $dev = getDev_Grafica_fac($sede, $row['linea_des']);
          $total = $row['total_art'] - $dev;

          echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
        }
        ?>
      ]);

      // Set options for Sarah's pie chart.
      var options = {
        title: 'Total de ventas',
        width: 600,
        height: 500
      };

      // Instantiate and draw the chart for Sarah's pizza.
      var chart = new google.visualization.PieChart(document.getElementById('Sarah_chart_div'));
      chart.draw(data, options);
    }

    // Callback that draws the pie chart for Anthony's pizza.
    function drawAnthonyChart() {

      // Create the data table for Anthony's pizza.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Topping');
      data.addColumn('number', 'Slices');
      data.addRows([
        <?php

        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            group by linea_des
            order by total_dev desc";

        $consulta = sqlsrv_query($conn, $sql);
        while ($row = sqlsrv_fetch_array($consulta)) {

          $total = $row['total_dev'];

          echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
        }
        ?>
      ]);

      // Set options for Anthony's pie chart.
      var options = {
        title: 'Total de Devoluciones',
        width: 600,
        height: 500
      };

      // Instantiate and draw the chart for Anthony's pizza.
      var chart = new google.visualization.PieChart(document.getElementById('Anthony_chart_div'));
      chart.draw(data, options);
    }
    //---------------------------------------------------------------------------------------------||
    //---------------------------------------------------------------------------------------------||
    //---------------------------------------------------------------------------------------------||
    //---------------------------------------------------------------------------------------------||

  </script>
</head>

<center>
  <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
</center>
<br>
<br>
<br>
<center>
  <h2> Ventas por Mes desde <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  <div id="chart_div" style="width: 900px; height: 500px;"></div>
</center>
<br>
<br>

<center><h2>Detalles de las Ventas</h2></center>
<?php include 'includes/tabla-totales.php'; 
$busqueda=false;
include 'includes/grafica_global_tiendas.php';
include 'includes/grafica_tiendas.php';

deleteVendido_Grafica(); ?>
<center><input type="button" id='boton' class="btn btn-dark" name="imprimir" value="PDF" onclick="window.print();"> </center>
<?php include '../../includes/footer.php'; ?>

