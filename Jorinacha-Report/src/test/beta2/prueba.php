<?php

ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 3600);

require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';


?>
<table class="table table-dark table-striped" id="tblData">
    <thead>
        <tr>
            <th scope='col'>Codigo</th>
            <th scope='col'>Eviado</th>
            <th scope='col'>Modelo</th>
        </tr>
    </thead>
    <tbody>


        <tr>

            <td></td>
            <td>
                <h4>Total</h4>
            </td>
            <td></td>
        </tr>

    </tbody>
</table>