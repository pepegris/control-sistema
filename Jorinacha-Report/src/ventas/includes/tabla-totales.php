<table class="table table-dark table-striped" id="tblData">
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

        $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_art)) as total_art  from art_grafica
                group by linea_des
                order by total_art desc";
        $consulta = sqlsrv_query($conn, $sql);
        $n=1;
        while ($row = sqlsrv_fetch_array($consulta)) {

            $dev = getDev_Grafica_fac($sede, $row['linea_des']);
            $total = $row['total_art'] - $dev;
            $n++;
        ?>
            <tr>
                <th scope='row'><?= $n ?></th>
                <td><?= $row['linea_des'] ?></td>
                <td><?= $total ?></td>
                <td><?= $dev ?></td>

            </tr>
        <?php

        $total_vendido=+$total;
        $total_dev=+$dev;


        }
        ?>

        <tr>
            <th></th>
            <td colspan="1"></td>
            <td>
                <h4>Total</h4>
            </td>
            <td><?= $total_vendido ?></td>
            <td><?= $total_dev ?></td>

            ?>
        </tr>

    </tbody>
</table>