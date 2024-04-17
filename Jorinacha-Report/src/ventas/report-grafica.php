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

      // GRAFICAS GENERAL
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



      // VARIAS GRAFICAS

      // Load Charts and the corechart and barchart packages.
      google.charts.load('current', {'packages':['corechart']});

      // Draw the pie chart and bar chart when Charts is loaded.
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Mushrooms', 3],
          ['Onions', 1],
          ['Olives', 1],
          ['Zucchini', 1],
          ['Pepperoni', 2]
        ]);

        var piechart_options = {title:'Pie Chart: How Much Pizza I Ate Last Night',
                       width:400,
                       height:300};
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div'));
        piechart.draw(data, piechart_options);

        var barchart_options = {title:'Barchart: How Much Pizza I Ate Last Night',
                       width:400,
                       height:300,
                       legend: 'none'};
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div'));
        barchart.draw(data, barchart_options);
      }











      var data = google.visualization.arrayToDataTable([
         ['Element', 'Density', { role: 'style' }],
         ['Copper', 8.94, '#b87333'],            // RGB value
         ['Silver', 10.49, 'silver'],            // English color name
         ['Gold', 19.30, 'gold'],

       ['Platinum', 21.45, 'color: #e5e4e2' ], // CSS-style declaration
      ]);
      
    </script>
    
  </head>








    <div id="piechart_3d" style="width: 900px; height: 500px;"></div>



    <!--Table and divs that hold the pie charts-->
    <table class="columns">
      <tr>
        <td><div id="piechart_div" style="border: 1px solid #ccc"></div></td>
        <td><div id="barchart_div" style="border: 1px solid #ccc"></div></td>
      </tr>
    </table>









    



<?php include '../../includes/footer.php'; ?>
