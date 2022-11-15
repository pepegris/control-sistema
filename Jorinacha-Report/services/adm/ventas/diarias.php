<?php


/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA*/


$sedes_ar = array(
    "Previa Shop",
    "Comercial Merina",
    "Comercial Merina III",
    "Comercial Corina I",
    "Comercial Corina II",
    "Comercial Punto Fijo",
    "Comercial Matur",
    "Comercial Valena",
    "Comercial Trina",
    "Comercial Kagu",
    "Comercial Nachari",
    "Comercial Higue",
    "Comercial Apura",
    "Comercial Vallepa",
    "Comercial Ojena",
    "Comercial Puecruz",
    "Comercial Acari",
    "Comercial Catica II",
);



function Database2($sede)
{

    $bd = array(
        "Previa Shop" => 'PREVIA_A',
        "Comercial Merina" => 'MERINA21',
        "Comercial Merina III" => 'MRIA3A21',
        "Comercial Corina I" => 'CORINA21',
        "Comercial Corina II" => 'CORI2_21',
        "Comercial Punto Fijo" => 'PUFIJO21',
        "Comercial Matur" => 'MATURA21',
        "Comercial Valena" => 'VALENA21',
        "Comercial Trina" => 'TRAINA21',
        "Comercial Kagu" => 'KAGUA21',
        "Comercial Nachari" => 'NACHAR21',
        "Comercial Higue" => 'HIGUE21',
        "Comercial Apura" => 'APURA21',
        "Comercial Vallepa" => 'VALLEP21',
        "Comercial Ojena" => 'OJENA21',
        "Comercial Puecruz" => 'PUECRU21',
        "Comercial Acari" => 'ACARI21',
        "Comercial Catica II" => 'CATICA21',
    );

    return $bd[$sede];
}
function Database($sede)
{

    $bd = array(
        "Previa Shop" => 'PREVIA_A',
        "Comercial Merina" => 'MERINA',
        "Comercial Merina III" => 'MERINA3',
        "Comercial Corina I" => 'CORINA1',
        "Comercial Corina II" => 'CORINA2',
        "Comercial Punto Fijo" => 'PUFIJO',
        "Comercial Matur" => 'MATUR',
        "Comercial Valena" => 'VALENA',
        "Comercial Trina" => 'TRINA',
        "Comercial Kagu" => 'KAGU',
        "Comercial Nachari" => 'NACHARI',
        "Comercial Higue" => 'HIGUE',
        "Comercial Apura" => 'APURA',
        "Comercial Vallepa" => 'VALLEPA',
        "Comercial Ojena" => 'OJENA',
        "Comercial Puecruz" => 'PUECRUZ',
        "Comercial Acari" => 'ACARI',
        "Comercial Catica II" => 'CATICA2',
    );

    return $bd[$sede];
}

function Cliente($sede)
{

    $bd = array(
        "Comercial Merina"    =>     'T15',
        "Comercial Merina III"    =>     'T23',
        "Comercial Corina I"    =>     'T18',
        "Comercial Corina II"    =>     'T22',
        "Comercial Punto Fijo"    =>     'T13',
        "Comercial Matur"    =>     'T07',
        "Comercial Valena"    =>     'T10',
        "Comercial Trina"    =>     'T16',
        "Comercial Kagu"    =>     'T03',
        "Comercial Nachari"    =>     'T19',
        "Comercial Higue"    =>     'T09',
        "Comercial Apura"    =>     'T17',
        "Comercial Vallepa"    =>     'T06',
        "Comercial Ojena"    =>     'T12',
        "Comercial Puecruz"    =>     'T05',
        "Comercial Acari"    =>     'T04',
        "Comercial Catica II"    =>     'T24',

    );

    return $bd[$sede];
}







/* CONSULTAR ARTICULOS VENDIDOS*/
function getFactura($sede, $fecha1, $fecha2)
{

    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($fecha2 == null) {

                $sql = "SELECT  SUM(tot_neto) , SUM(total_art) from factura
                JOIN reng_fac ON factura.fact_num = reng_fac.fact_num
                where anulada=0 AND fec_emis ='$fecha1'";

            } else {

                $sql = "SELECT  SUM(tot_neto) , SUM(total_art) from factura
                JOIN reng_fac ON factura.fact_num = reng_fac.fact_num
                where anulada=0 AND fec_emis BETWEEN '$fecha1' AND '$fecha2'";

            }
            



            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $factura = $row['total_art'];
                    $factura = $row['tot_neto'];
                    break;
                }

                $res = $factura;

            } else {

                $factura['total_art'] = 0;
                $factura['tot_neto'] = 0;

                $res = $factura ;
            }

            return $res;

        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


function getDev_cli ($sede, $fecha1, $fecha2)
{

    
    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($fecha2 == null) {

                $sql = "SELECT SUM(tot_neto),SUM(total_art) from dev_cli 
                JOIN reng_dvc ON dev_cli.fact_num = reng_dvc.fact_num
                WHERE fec_emis ='$fecha1' and dev_cli.anulada =0 ";

            } else {

                $sql = "SELECT SUM(tot_neto),SUM(total_art) from dev_cli 
                JOIN reng_dvc ON dev_cli.fact_num = reng_dvc.fact_num
                WHERE fec_emis BETWEEN '$fecha1' AND '$fecha2' and dev_cli.anulada =0";

            }
            



            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $dev_cli = $row['total_art'];
                    $dev_cli = $row['tot_neto'];
                    break;
                }

                $res = $dev_cli;

            } else {

                $dev_cli['total_art'] = 0;
                $dev_cli['tot_neto'] = 0;

                $res = $dev_cli ;
            }

            return $res;

        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}



function getDep_caj ($sede, $fecha1, $fecha2)
{

    
    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($fecha2 == null) {

                $sql = "SELECT SUM(total_efec) , SUM(total_tarj) from dep_caj
                WHERE fecha ='$fecha1'";

            } else {

                $sql = "SELECT SUM(total_efec) , SUM(total_tarj) from dep_caj
                WHERE fecha  BETWEEN '$fecha1' AND '$fecha2'";

            }
            



            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $dep_caj = $row['total_efec'];
                    $dep_caj = $row['total_tarj'];
                    break;
                }

                $res = $dep_caj;

            } else {

                $dep_caj['total_efec'] = 0;
                $dep_caj['total_tarj'] = 0;

                $res = $dep_caj ;
            }

            return $res;

        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}




function getMov_ban ($sede, $fecha1, $fecha2)
{

    
    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($fecha2 == null) {

                $sql = "SELECT SUM(monto_h) from mov_ban
                WHERE fecha ='$fecha1' AND anulado = 0";

            } else {

                $sql = "SELECT SUM(monto_h) from mov_ban
                WHERE fecha  BETWEEN '$fecha1' AND '$fecha2' AND anulado = 0";

            }
            



            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $mov_ban = $row['monto_h'];
                    break;
                }

                $res = $mov_ban;

            } else {

                $mov_ban['monto_h'] = 0;

                $res = $mov_ban ;
            }

            return $res;

        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}




function getOrd_pago($sede, $fecha1, $fecha2)
{

    
    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($fecha2 == null) {

                $sql = "SELECT SUM(monto) from ord_pago
                WHERE fecha ='$fecha1' AND anulada = 0";

            } else {

                $sql = "SELECT SUM(monto) from ord_pago
                WHERE fecha  BETWEEN '$fecha1' AND '$fecha2' AND anulada = 0";

            }
            



            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $ord_pago = $row['monto'];
                    break;
                }

                $res = $ord_pago;

            } else {

                $ord_pago['monto'] = 0;

                $res = $ord_pago ;
            }

            return $res;

        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}




function getTasas($sede, $fecha1, $fecha2)
{

    
    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $sql = "SELECT TOP 1 tasa_v from tasas 
            where fecha >= CAST('$fecha1' AS datetime) ";


            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $tasas = $row['tasa_v'];
                    break;
                }

                $res = $tasas;

            } else {

                $tasas['tasa_v'] = 0;

                $res = $tasas ;
            }

            return $res;

        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}
