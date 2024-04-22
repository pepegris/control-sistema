<script type="text/javascript">
    //---------------------------------------------------------------------------------------------||

    google.charts.setOnLoadCallback(drawChartGlobal);

    function drawChartGlobal() {

      var data = google.visualization.arrayToDataTable([
    ['Mes', 'Sucursal Caracas I', 'Sucursal Caracas II"',
       'Sucursal Cagua','Sucursal Maturin','Comercial Acari','Comercial Puecruz',
       'Comercial Vallepa','Comercial Higue','Comercial Valena','Comercial Ojena',
       'Comercial Punto Fijo','Comercial Trina','Comercial Apura','Comercial Corina I',
       'Comercial Nachari','Comercial Corina II','Comercial Catica II'],

        <?php

        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        $m = $Month_beg;
        $Month  = $Month_beg;
        for ($k = $Month_beg; $k <= $Month_total; $k++) {

            ///////////////////////////////////////////////////////////////////
            $sede1 = $sedes_ar[1];
            $sql_sede1 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede1'";
            $consulta_sede1 = sqlsrv_query($conn, $sql_sede1);
            $row_sede1 = sqlsrv_fetch_array($consulta_sede1);
            $total_sede1 = $row_sede1['total_art'];
  
            $sql2_sede1 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede1'";
            $consulta2_sede1 = sqlsrv_query($conn, $sql2_sede1);
            $row2_sede1 = sqlsrv_fetch_array($consulta2);
            $total2_sede1 = $row2_sede1['total_dev'];
            ///////////////////////////////////////////////////////////////////
            $sede2 = $sedes_ar[2];
            $sql_sede2 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede2'";
            $consulta_sede2 = sqlsrv_query($conn, $sql_sede2);
            $row_sede2 = sqlsrv_fetch_array($consulta_sede2);
            $total_sede2 = $row_sede2['total_art'];
  
            $sql2_sede2 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede2'";
            $consulta2_sede2 = sqlsrv_query($conn, $sql2_sede2);
            $row2_sede2 = sqlsrv_fetch_array($consulta2);
            $total2_sede2 = $row2_sede2['total_dev'];
            ///////////////////////////////////////////////////////////////////
            $sede3 = $sedes_ar[3];
            $sql_sede3 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede3'";
            $consulta_sede3 = sqlsrv_query($conn, $sql_sede3);
            $row_sede3 = sqlsrv_fetch_array($consulta_sede3);
            $total_sede3 = $row_sede3['total_art'];
  
            $sql2_sede3 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede3'";
            $consulta2_sede3 = sqlsrv_query($conn, $sql2_sede3);
            $row2_sede3 = sqlsrv_fetch_array($consulta2);
            $total2_sede3 = $row2_sede3['total_dev'];
           ///////////////////////////////////////////////////////////////////
            $sede4 = $sedes_ar[4];
            $sql_sede4 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede4'";
            $consulta_sede4 = sqlsrv_query($conn, $sql_sede4);
            $row_sede4 = sqlsrv_fetch_array($consulta_sede4);
            $total_sede4 = $row_sede4['total_art'];
  
            $sql2_sede4 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede4'";
            $consulta2_sede4 = sqlsrv_query($conn, $sql2_sede4);
            $row2_sede4 = sqlsrv_fetch_array($consulta2);
            $total2_sede4 = $row2_sede4['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede5 = $sedes_ar[5];
            $sql_sede5 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede5'";
            $consulta_sede5 = sqlsrv_query($conn, $sql_sede5);
            $row_sede5 = sqlsrv_fetch_array($consulta_sede5);
            $total_sede5 = $row_sede5['total_art'];
  
            $sql2_sede5 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede5'";
            $consulta2_sede5 = sqlsrv_query($conn, $sql2_sede5);
            $row2_sede5 = sqlsrv_fetch_array($consulta2);
            $total2_sede5 = $row2_sede5['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede6 = $sedes_ar[6];
            $sql_sede6 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede6'";
            $consulta_sede6 = sqlsrv_query($conn, $sql_sede6);
            $row_sede6 = sqlsrv_fetch_array($consulta_sede6);
            $total_sede6 = $row_sede6['total_art'];
  
            $sql2_sede6 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede6'";
            $consulta2_sede6 = sqlsrv_query($conn, $sql2_sede6);
            $row2_sede6 = sqlsrv_fetch_array($consulta2);
            $total2_sede6 = $row2_sede6['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede7 = $sedes_ar[7];
            $sql_sede7 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede7'";
            $consulta_sede7 = sqlsrv_query($conn, $sql_sede7);
            $row_sede7 = sqlsrv_fetch_array($consulta_sede7);
            $total_sede7 = $row_sede7['total_art'];
  
            $sql2_sede7 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede7'";
            $consulta2_sede7 = sqlsrv_query($conn, $sql2_sede7);
            $row2_sede7 = sqlsrv_fetch_array($consulta2);
            $total2_sede7 = $row2_sede7['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede8 = $sedes_ar[8];
            $sql_sede8 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede8'";
            $consulta_sede8 = sqlsrv_query($conn, $sql_sede8);
            $row_sede8 = sqlsrv_fetch_array($consulta_sede8);
            $total_sede8 = $row_sede8['total_art'];
  
            $sql2_sede8 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede8'";
            $consulta2_sede8 = sqlsrv_query($conn, $sql2_sede8);
            $row2_sede8 = sqlsrv_fetch_array($consulta2);
            $total2_sede8 = $row2_sede8['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede9 = $sedes_ar[9];
            $sql_sede9 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede9'";
            $consulta_sede9 = sqlsrv_query($conn, $sql_sede9);
            $row_sede9 = sqlsrv_fetch_array($consulta_sede9);
            $total_sede9 = $row_sede9['total_art'];
  
            $sql2_sede9 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede9'";
            $consulta2_sede9 = sqlsrv_query($conn, $sql2_sede9);
            $row2_sede9 = sqlsrv_fetch_array($consulta2);
            $total2_sede9 = $row2_sede9['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede10 = $sedes_ar[10];
            $sql_sede10 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede10'";
            $consulta_sede10 = sqlsrv_query($conn, $sql_sede10);
            $row_sede10 = sqlsrv_fetch_array($consulta_sede10);
            $total_sede10 = $row_sede10['total_art'];
  
            $sql2_sede10 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede10'";
            $consulta2_sede10 = sqlsrv_query($conn, $sql2_sede10);
            $row2_sede10 = sqlsrv_fetch_array($consulta2);
            $total2_sede10 = $row2_sede10['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede11 = $sedes_ar[11];
            $sql_sede11 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede11'";
            $consulta_sede11 = sqlsrv_query($conn, $sql_sede11);
            $row_sede11 = sqlsrv_fetch_array($consulta_sede11);
            $total_sede11 = $row_sede11['total_art'];
  
            $sql2_sede11 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede11'";
            $consulta2_sede11 = sqlsrv_query($conn, $sql2_sede11);
            $row2_sede11 = sqlsrv_fetch_array($consulta2);
            $total2_sede11 = $row2_sede11['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede12 = $sedes_ar[12];
            $sql_sede12 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede12'";
            $consulta_sede12 = sqlsrv_query($conn, $sql_sede12);
            $row_sede12 = sqlsrv_fetch_array($consulta_sede12);
            $total_sede12 = $row_sede12['total_art'];
  
            $sql2_sede12 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede12'";
            $consulta2_sede12 = sqlsrv_query($conn, $sql2_sede12);
            $row2_sede12 = sqlsrv_fetch_array($consulta2);
            $total2_sede12 = $row2_sede12['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede13 = $sedes_ar[13];
            $sql_sede13 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede13'";
            $consulta_sede13 = sqlsrv_query($conn, $sql_sede13);
            $row_sede13 = sqlsrv_fetch_array($consulta_sede13);
            $total_sede13 = $row_sede13['total_art'];
  
            $sql2_sede13 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede13'";
            $consulta2_sede13 = sqlsrv_query($conn, $sql2_sede13);
            $row2_sede13 = sqlsrv_fetch_array($consulta2);
            $total2_sede13 = $row2_sede13['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede14 = $sedes_ar[14];
            $sql_sede14 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede14'";
            $consulta_sede14 = sqlsrv_query($conn, $sql_sede14);
            $row_sede14 = sqlsrv_fetch_array($consulta_sede14);
            $total_sede14 = $row_sede14['total_art'];
  
            $sql2_sede14 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede14'";
            $consulta2_sede14 = sqlsrv_query($conn, $sql2_sede14);
            $row2_sede14 = sqlsrv_fetch_array($consulta2);
            $total2_sede14 = $row2_sede14['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede15 = $sedes_ar[15];
            $sql_sede15 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede15'";
            $consulta_sede15 = sqlsrv_query($conn, $sql_sede15);
            $row_sede15 = sqlsrv_fetch_array($consulta_sede15);
            $total_sede15 = $row_sede15['total_art'];
  
            $sql2_sede15 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede15'";
            $consulta2_sede15 = sqlsrv_query($conn, $sql2_sede15);
            $row2_sede15 = sqlsrv_fetch_array($consulta2);
            $total2_sede15 = $row2_sede15['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede16 = $sedes_ar[16];
            $sql_sede16 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede16'";
            $consulta_sede16 = sqlsrv_query($conn, $sql_sede16);
            $row_sede16 = sqlsrv_fetch_array($consulta_sede16);
            $total_sede16 = $row_sede16['total_art'];
  
            $sql2_sede16 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede16'";
            $consulta2_sede16 = sqlsrv_query($conn, $sql2_sede16);
            $row2_sede16 = sqlsrv_fetch_array($consulta2);
            $total2_sede16 = $row2_sede16['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede17 = $sedes_ar[17];
            $sql_sede17 = "SELECT SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE mes='$Month' tienda= '$sede17'";
            $consulta_sede17 = sqlsrv_query($conn, $sql_sede17);
            $row_sede17 = sqlsrv_fetch_array($consulta_sede17);
            $total_sede17 = $row_sede17['total_art'];
  
            $sql2_sede17 = "SELECT SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE mes='$Month' tienda= '$sede17'";
            $consulta2_sede17 = sqlsrv_query($conn, $sql2_sede17);
            $row2_sede17 = sqlsrv_fetch_array($consulta2);
            $total2_sede17 = $row2_sede17['total_dev'];
            //////////////////////////////////////////////////////////////////
            $sede1_t=$total_sede1-$total2_sede1;
            $sede2_t=$total_sede2-$total2_sede2;
            $sede3_t=$total_sede3-$total2_sede3;
            $sede4_t=$total_sede4-$total2_sede4;
            $sede5_t=$total_sede5-$total2_sede5;
            $sede6_t=$total_sede6-$total2_sede6;
            $sede7_t=$total_sede7-$total2_sede7;
            $sede8_t=$total_sede8-$total2_sede8;
            $sede9_t=$total_sede9-$total2_sede9;
            $sede10_t=$total_sede10-$total2_sede10;
            $sede11_t=$total_sede11-$total2_sede11;
            $sede12_t=$total_sede12-$total2_sede12;
            $sede13_t=$total_sede13-$total2_sede13;
            $sede14_t=$total_sede14-$total2_sede14;
            $sede15_t=$total_sede15-$total2_sede15;
            $sede16_t=$total_sede16-$total2_sede16;
            $sede17_t=$total_sede17-$total2_sede17;
            $mes = Meses($Month);
            echo "['".$mes."',
            $sede1_t,$sede2_t,$sede3_t,
            $sede4_t,$sede5_t,$sede6_t,
            $sede7_t,$sede8_t,$sede9_t,
            $sede10_t,$sede11_t,$sede12_t,
            $sede13_t,$sede14_t,$sede15_t,
            $sede16_t,$sede17_t ],";

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
          hAxis: {title: 'Meses',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };
      var chart = new google.visualization.AreaChart(document.getElementById('chart_div_global'));
      chart.draw(data, options);
    }
</script>


<br>
<center>
  <h2> Ventas de Tiendas por Mes desde <?= $fecha_titulo1  ?> hasta <?= $fecha_titulo2  ?></h2>
  <div id="chart_div_global" style="width: 100%; height: 500px;"></div>
</center>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->
