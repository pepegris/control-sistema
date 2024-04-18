<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

#require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

$divisa = $_GET['divisa'];
$linea = $_GET['linea'];
$fecha1 = date("Ymd", strtotime($_GET['fecha1']));
$fecha2 = date("Ymd", strtotime($_GET['fecha2']));

$Month_beg = date("m", strtotime($fecha1));
$Day = date("d", strtotime($fecha2));
$Month_total = date("m", strtotime($fecha2));
$Year = date("Y", strtotime($fecha2));


for ($i = 1; $i < count($sedes_ar); $i++) {

  $sede = $sedes_ar[$i];

  $m = $Month_beg;
  $Month  = $Month_beg;
 for ($k = $Month_beg; $k < $Month_total; $k++) {

  $cantidadDias = cal_days_in_month(CAL_GREGORIAN, $Month, $Year );

  $fecha_1 =  $Year .''. $Month .''  . '01';
  $fecha_2 =  $Year .''. $Month .''  . $cantidadDias;

  $ventas = getVendido_Grafica($sede, $fecha_1, $fecha_2,$Month);
  $dev = getDev_Grafica($sede, $fecha_1, $fecha_2,$Month);
  echo "$Month / $sede $Month_total" ;
  echo "<br>";
  
  if ($m < 9) {
    echo "<br> menor";
    $m++;
    $Month = 0 . $m;
  } else {
    echo "<br> mayor";
    $m++;
  }
}
  
}



?>

<style>
  body {
    background-color: white;
  }
</style>


<head>
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
    //---------------------------------------------------------------------------------------------||


    // Draw the pie chart for Sarah's pizza when Charts is loaded.
    google.charts.setOnLoadCallback(drawBackgroundColor);
    // Draw the pie chart for the Anthony's pizza when Charts is loaded.
    google.charts.setOnLoadCallback(drawLineColors);

    // Callback that draws the pie chart for Sarah's pizza.
    function drawBackgroundColor() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      data.addColumn('number', 'Ventas');

      data.addRows([
        [0, 0],
        [1, 10],
        [2, 23],
        [3, 17],
        [4, 18],
        [5, 9],
        [6, 11],
        [7, 27],
        [8, 33],
        [9, 40],
        [10, 32],
        [11, 35],
        [12, 30]
      ]);

      var options = {
        hAxis: {
          title: 'Meses'
        },
        vAxis: {
          title: 'Cantidad'
        },
        backgroundColor: '#f1f8e9'
      };

      var chart = new google.visualization.PieChart(document.getElementById('Completo_chart_div'));
      chart.draw(data, options);
    }



    function drawLineColors() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      data.addColumn('number', 'Dogs');
      data.addColumn('number', 'Cats');
      data.addColumn('number', 'Birt');

      data.addRows([
        [0, 0, 0, 0],
        [1, 10, 5, 15],
        [2, 23, 15, 15],
        [3, 17, 9, 15],
        [4, 18, 10, 15],
        [5, 9, 5, 15],
        [6, 11, 3, 15],
        [7, 27, 19, 15],
        [8, 33, 25, 15],
        [9, 40, 32, 15],
        [10, 32, 24, 15],
        [11, 35, 27, 15],
        [12, 30, 22, 15]
      ]);

      var options = {
        hAxis: {
          title: 'Time'
        },
        vAxis: {
          title: 'Popularity'
        },
        colors: ['#a52714', '#097138']
      };

      var chart = new google.visualization.PieChart(document.getElementById('Detallado_chart_div'));
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
<br>
<br>

<!--Table and divs that hold the pie charts-->
<table class="columns">
  <tr>
    <td>
      <div id="Completo_chart_div" style="border: 1px solid #ccc"></div>
    </td>
    <td>
      <div id="Detallado_chart_div" style="border: 1px solid #ccc"></div>
    </td>
  </tr>
</table>


<?php
deleteVendido_Grafica();
include '../../includes/footer.php'; ?>