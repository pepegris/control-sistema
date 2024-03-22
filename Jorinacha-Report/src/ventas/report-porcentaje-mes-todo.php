
  <html>

  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {
        packages: ["corechart"]
      });
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],

          <?php

          $serverName = "172.16.1.39";
          $connectionInfo = array("Database" => "PUECRUZ", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
          $conn = sqlsrv_connect($serverName, $connectionInfo);

          $sql = "SELECT SUM (CONVERT(numeric(10,0), reng_fac.total_art)) as total_art,lin_art.lin_des from factura 
          inner join reng_fac on factura.fact_num=reng_fac.fact_num
          inner join art  on art.co_art=reng_fac.co_art
          inner join lin_art on lin_art.co_lin=art.co_lin
          where factura.anulada =0 and factura.fec_emis between '20230101' and '20231231'
          group by lin_art.lin_des ";

          $consulta = sqlsrv_query($conn, $sql);
          while ($row = sqlsrv_fetch_array($consulta)) {

            echo "['" .$row['total_art']. "'," .$row['lin_des']. "],";
          }




          ?>
        ]);

        var options = {
          title: 'My Daily Activities',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
  </head>

  <body>
    <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
  </body>

  </html>
