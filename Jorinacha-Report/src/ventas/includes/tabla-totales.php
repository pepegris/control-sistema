<table class="table table-secondary table-striped" id="tblData">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope='col'>Articulos</th>
            <th scope='col'>Vendidos</th>
            <th scope='col'>Devoluciones</th>
        </tr>
    </thead>
    <tbody>
        <?php


        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if ($busqueda==true) {
            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            group by linea_des
            order by total_art desc";
        }else {
            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
            WHERE tienda= '$sede'
            group by linea_des
            order by total_art desc";
        }


        $consulta = sqlsrv_query($conn, $sql);
        $total_vendido=0;
        $total_dev=0;
        $n=1;
        while ($row = sqlsrv_fetch_array($consulta)) {

            if ($busqueda==true) {
                $dev = getDev_Grafica_fac($sede, $row['linea_des']);
            }else {
                $dev = getDev_Grafica_fac2($sede, $row['linea_des']);
            } 
            
            if ($dev !=null) {
                $dev;
            } else {
                $dev=0;
            }

            $total = $row['total_art'] - $dev;
            
            
        ?>
            <tr>
                <th scope='row'><?= $n ?></th>
                <td><?= $row['linea_des'] ?></td>
                <td><?= $total ?></td>
                <td><?= $dev ?></td>

            </tr>
        <?php

        $total_vendido+=$total;
        $total_dev+=$dev;

        $n++;
        }
        ?>

        <tr>

            <td colspan="2">
                <h3>Total</h3>
            </td>
            <td><?= $total_vendido ?></td>
            <td><?= $total_dev ?></td>

        </tr>

    </tbody>
</table>