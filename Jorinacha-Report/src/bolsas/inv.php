<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

?>

<style>
    .form-check {
        display: flexbox;



    }
</style>

<div id="body">
    <form action="#" method="POST">

        <div class="fieldset">
            <br>
            <center>
                <legend>Reporte</legend>
            </center>


            <label for="tienda" class="form-label ">Tienda</label>
            <select name="tienda" id="">
                <?php
                $res1 = getTiendas();
                $i = 0;
                while ($row1 = mysqli_fetch_array($res1)) {

                    $sede = $row1['sedes_nom'];

                    if ($sede != 'Sede Boleita') {
                        
                        echo "<option value='$sede'> $sede </option>";
                    }

                    $i++;
                } ?>
            </select>
        </div>


        <br>
        <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
        <br>
</div>
</form>
</div>




<?php include '../../includes/footer.php'; ?>