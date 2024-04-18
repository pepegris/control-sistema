<!-- CARACAS 1 -->
<script type="text/javascript">
    // Load Charts and the corechart and barchart packages.
    google.charts.load('current', {
        'packages': ['corechart']
    });

    // Draw the pie chart and bar chart when Charts is loaded.
    google.charts.setOnLoadCallback(drawChart);


    function drawChart() {

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

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
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

        var piechart_options = {
            title: 'Pie Chart: How Much Pizza I Ate Last Night',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_caracas1'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Barchart: How Much Pizza I Ate Last Night',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_caracas1'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_caracas1" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_caracas1" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->

<!-- CARACAS 2 -->
<script type="text/javascript">
    // Load Charts and the corechart and barchart packages.
    google.charts.load('current', {
        'packages': ['corechart']
    });

    // Draw the pie chart and bar chart when Charts is loaded.
    google.charts.setOnLoadCallback(drawChart);


    function drawChart() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
            ['Mushrooms', 30],
            ['Onions', 1],
            ['Olives', 1],
            ['Zucchini', 1],
            ['Pepperoni', 2]
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            ['Mushrooms', 3],
            ['Onions', 1],
            ['Olives', 1],
            ['Zucchini', 1],
            ['Pepperoni', 2]
        ]);

        var piechart_options = {
            title: 'Pie Chart: How Much Pizza I Ate Last Night',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_caracas2'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Barchart: How Much Pizza I Ate Last Night',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_caracas2'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_caracas2" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_caracas2" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->