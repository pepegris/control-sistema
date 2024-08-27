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

            $sede = $sedes_ar[1];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[1];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_caracas1'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_caracas1'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
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

<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>


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

            <?php

            $sede = $sedes_ar[2];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[2];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_caracas2'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_caracas2'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
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
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>
<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->





<!-- Cagua -->

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

            $sede = $sedes_ar[3];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[3];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_Cagua'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_Cagua'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_Cagua" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_Cagua" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>
<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->





<!-- Maturin -->

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

            $sede = $sedes_ar[4];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[4];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_Maturin'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_Maturin'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_Maturin" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_Maturin" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>
<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->




<!-- Acari -->

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

            $sede = $sedes_ar[5];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[5];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_Acari'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_Acari'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_Acari" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_Acari" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>
<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->





<!-- Puecruz -->

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

            $sede = $sedes_ar[6];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[6];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_Puecruz'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_Puecruz'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_Puecruz" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_Puecruz" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->






<!-- Vallepa -->

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

            $sede = $sedes_ar[7];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[7];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_Vallepa'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_Vallepa'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_Vallepa" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_Vallepa" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->



<!-- Higue -->

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

            $sede = $sedes_ar[8];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[8];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_Higue'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_Higue'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_Higue" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_Higue" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->






<!-- Valena -->

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

            $sede = $sedes_ar[9];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[9];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_Valena'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_Valena'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_Valena" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_Valena" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->






<!-- Ojena -->

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

            $sede = $sedes_ar[10];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[10];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_Ojena'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_Ojena'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_Ojena" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_Ojena" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->






<!-- PuntoFijo -->

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

            $sede = $sedes_ar[11];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[11];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_PuntoFijo'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_PuntoFijo'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_PuntoFijo" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_PuntoFijo" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->





<!-- Trina -->

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

            $sede = $sedes_ar[12];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[12];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_Trina'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_Trina'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_Trina" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_Trina" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->





<!-- Apura -->

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

            $sede = $sedes_ar[13];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[13];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_Apura'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_Apura'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_Apura" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_Apura" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->





<!-- CorinaI -->

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

            $sede = $sedes_ar[14];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[14];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_CorinaI'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_CorinaI'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_CorinaI" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_CorinaI" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->







<!-- CorinaII -->

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

            $sede = $sedes_ar[16];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[16];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_CorinaII'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_CorinaII'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_CorinaII" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_CorinaII" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->






<!-- Nachari -->

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

            $sede = $sedes_ar[15];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[15];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_Nachari'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_Nachari'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_Nachari" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_Nachari" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>
<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->






<!-- CaticaII -->

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

            $sede = $sedes_ar[17];

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                    WHERE tienda= '$sede'
                    group by linea_des
                    order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($consulta)) {

                
                $total = $row['total_art'] ;

                echo "['" . $row['linea_des'] . " / " . $total . "'," . $total . "],";
            }
            ?>
        ]);

        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
            <?php

            $sede = $sedes_ar[17];
            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
                    WHERE tienda= '$sede'
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
            title: 'Despachados por Marcas',
            width: 600,
            height: 500
        };
        var piechart = new google.visualization.PieChart(document.getElementById('piechart_div_CaticaII'));
        piechart.draw(data, piechart_options);

        var barchart_options = {
            title: 'Bultos por Marcas',
            width: 600,
            height: 500,
            legend: 'none'
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div_CaticaII'));
        barchart.draw(data2, barchart_options);
    }
</script>


<!--Table and divs that hold the pie charts-->
<center>
    <h2> Despacho de <?= $sede ?> <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  </center>
<table class="columns">
    <tr>
        <td>
            <div id="piechart_div_CaticaII" style="border: 1px solid #ccc"></div>
        </td>
        <td>
            <div id="barchart_div_CaticaII" style="border: 1px solid #ccc"></div>
        </td>
    </tr>
</table>
<center><h2>Detalles de los Despachos <?= $sede ?></h2></center>
<?php include 'includes/despachos/tabla-totales.php'; ?>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->
