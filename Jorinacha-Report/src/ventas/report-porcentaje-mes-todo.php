
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
          $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
          $conn = sqlsrv_connect($serverName, $connectionInfo);

          $sql = "SELECT TOP 5 art_des,co_art FROM art order by fe_us_in desc   ";

          $consulta = sqlsrv_query($conn, $sql);
          while ($row = sqlsrv_fetch_array($consulta)) {

            echo "['" .$row['art_des']. "','" .$row['co_art']. "'],";
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
