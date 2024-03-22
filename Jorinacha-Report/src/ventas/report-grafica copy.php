<?php
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

#require "../../includes/log.php";
include '../../includes/header2.php';
include '../../services/mysql.php';
include '../../services/adm/ventas/diarias.php';

  $divisa = $_GET['divisa'];
  $linea=$_GET['linea'];
  $fecha1 = date("Ymd", strtotime($_GET['fecha1']));
  $fecha2 = date("Ymd", strtotime($_GET['fecha2']));


  $Day = date("d", strtotime($fecha2));
  $Month_total = date("m", strtotime($fecha2));
  $Year = date("Y", strtotime($fecha2));





?>

<style>
  body{
    background-color: white;
  }
</style>


  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {
        packages: ["corechart"]
      });
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Articulos', 'Total Vendidos'],

          <?php

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PUECRUZ", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT SUM (CONVERT(numeric(10,0), reng_fac.total_art)) as total_art,lin_art.lin_des from factura 
            inner join reng_fac on factura.fact_num=reng_fac.fact_num
            inner join art  on art.co_art=reng_fac.co_art
            inner join lin_art on lin_art.co_lin=art.co_lin
            where factura.anulada =0 and factura.fec_emis between '20230101' and '20231231'
            group by lin_art.lin_des 
            order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

              echo "['" .$row['lin_des']. " / " .$row['total_art']. "'," .$row['total_art']. "],";
            }
          ?>
        ]);

        var options = {
          title: 'Modelos vendidos por Puerto',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }

      
    </script>
  </head>

    <div id="piechart_3d" style="width: 900px; height: 500px;"></div>



<?php include '../../includes/footer.php'; ?>
