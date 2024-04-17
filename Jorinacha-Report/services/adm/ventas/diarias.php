<?php


/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA*/


require "../../services/empresas.php";




/* CONSULTAR ARTICULOS VENDIDOS*/
function getFactura($sede, $fecha1, $fecha2, $data, $linea)
{

    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($data == 'ven') {

                if ($linea != 'todos') {
                    $sql = "SELECT   SUM(reng_fac.total_art) as total_art from reng_fac
                    JOIN factura ON factura.fact_num = reng_fac.fact_num
                    inner join art on art.co_art=reng_fac.co_art
                    where anulada=0 AND fec_emis ='$fecha1' and art.co_lin='$linea'";
                } else {
                    $sql = "SELECT   SUM(reng_fac.total_art) as total_art from reng_fac
                    JOIN factura ON factura.fact_num = reng_fac.fact_num
                    where anulada=0 AND fec_emis ='$fecha1'";
                }
            } elseif ($data == 'ven2') {


                if ($linea != 'todos') {
                    $sql = "SELECT   SUM(reng_fac.total_art) as total_art from reng_fac
                    JOIN factura ON factura.fact_num = reng_fac.fact_num
                    inner join art on art.co_art=reng_fac.co_art
                    where anulada=0 AND fec_emis BETWEEN '$fecha1' AND '$fecha2' and art.co_lin='$linea'";
                } else {
                    $sql = "SELECT   SUM(reng_fac.total_art) as total_art from reng_fac
                    JOIN factura ON factura.fact_num = reng_fac.fact_num
                    where anulada=0 AND fec_emis BETWEEN '$fecha1' AND '$fecha2'";
                }
            } elseif ($data == 'sin') {

                if ($linea != 'todos') {
                    $sql = "SELECT  SUM(tot_neto) as tot_neto from factura
                    inner join reng_fac on reng_fac.fact_num = factura.fact_num
                    inner join art on art.co_art=reng_fac.co_art
                    where anulada=0 AND fec_emis ='$fecha1' and art.co_lin='$linea'";
                } else {
                    $sql = "SELECT  SUM(tot_neto)  as tot_neto from factura
                    where anulada=0 AND fec_emis ='$fecha1'";
                }
            } else {


                if ($linea != 'todos') {
                    $sql = "SELECT  SUM(tot_neto) as tot_neto from factura
                    inner join reng_fac on reng_fac.fact_num = factura.fact_num
                    inner join art on art.co_art=reng_fac.co_art
                    where anulada=0 AND fec_emis BETWEEN '$fecha1' AND '$fecha2' and art.co_lin='$linea'";
                } else {
                    $sql = "SELECT  SUM(tot_neto) as tot_neto from factura
                    where anulada=0 AND fec_emis BETWEEN '$fecha1' AND '$fecha2'";
                }
            }




            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $factura['total_art'] = $row['total_art'];
                    $factura['tot_neto'] = $row['tot_neto'];
                    break;
                }

                $res = $factura;
            } else {

                $factura['total_art'] = 0;
                $factura['tot_neto'] = 0;

                $res = $factura;
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


function getDev_cli($sede, $fecha1, $fecha2, $data, $linea)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($data == 'sin') {


                if ($linea != 'todos') {
                    $sql = "SELECT SUM(dev_cli.tot_neto) as tot_neto  from dev_cli 
                    inner join reng_dvc on reng_dvc.fact_num = dev_cli.fact_num
                    inner join art on art.co_art=reng_dvc.co_art
                    WHERE fec_emis >='$fecha1' and dev_cli.anulada =0 and art.co_lin='$linea'";
                } else {
                    $sql = "SELECT SUM(dev_cli.tot_neto) as tot_neto  from dev_cli 
                    WHERE fec_emis ='$fecha1' and dev_cli.anulada =0 ";
                }
            } elseif ($data == 'ven') {

                if ($linea != 'todos') {
                    $sql = "SELECT SUM(total_art) as total_art  from dev_cli 
                    inner join reng_dvc on reng_dvc.fact_num = dev_cli.fact_num
                    inner join art on art.co_art=reng_dvc.co_art
                    WHERE fec_emis >='$fecha1' and dev_cli.anulada =0 and art.co_lin='$linea'";
                } else {
                    $sql = "SELECT SUM(total_art) as total_art  from dev_cli 
                    JOIN reng_dvc ON dev_cli.fact_num = reng_dvc.fact_num
                    WHERE fec_emis ='$fecha1' and dev_cli.anulada =0";
                }
            } elseif ($data == 'ven2') {

                if ($linea != 'todos') {
                    $sql = "SELECT SUM(total_art) as total_art  from dev_cli 
                    inner join reng_dvc on reng_dvc.fact_num = dev_cli.fact_num
                    inner join art on art.co_art=reng_dvc.co_art
                    WHERE fec_emis >='$fecha1' and dev_cli.anulada =0 and art.co_lin='$linea'";
                } else {

                    $sql = "SELECT SUM(total_art) as total_art  from dev_cli 
                    JOIN reng_dvc ON dev_cli.fact_num = reng_dvc.fact_num
                    WHERE fec_emis BETWEEN '$fecha1' AND '$fecha2' and dev_cli.anulada =0";
                }
            } else {


                if ($linea != 'todos') {
                    $sql = "SELECT SUM(tot_neto) as tot_neto from dev_cli 
                    inner join reng_dvc on reng_dvc.fact_num = dev_cli.fact_num
                    inner join art on art.co_art=reng_dvc.co_art
                    WHERE fec_emis >='$fecha1' and dev_cli.anulada =0 and art.co_lin='$linea'";
                } else {

                    $sql = "SELECT SUM(tot_neto) as tot_neto  from dev_cli 
                    WHERE fec_emis BETWEEN '$fecha1' AND '$fecha2' and dev_cli.anulada =0";
                }
            }




            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $dev_cli['total_art'] = $row['total_art'];
                    $dev_cli['tot_neto'] = $row['tot_neto'];
                    break;
                }

                $res = $dev_cli;
            } else {

                $dev_cli['total_art'] = 0;
                $dev_cli['tot_neto'] = 0;

                $res = $dev_cli;
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}



function getDep_caj($sede, $fecha1, $fecha2, $data)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($data == 'sin') {

                $sql = "SELECT SUM(total_efec) as  total_efec , SUM(total_tarj) as total_tarj from dep_caj
                WHERE fecha ='$fecha1'";
            } else {

                $sql = "SELECT SUM(total_efec) as  total_efec , SUM(total_tarj) as total_tarj  from dep_caj
                WHERE fecha  BETWEEN '$fecha1' AND '$fecha2'";
            }




            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $dep_caj['total_efec'] = $row['total_efec'];
                    $dep_caj['total_tarj'] = $row['total_tarj'];
                    break;
                }

                $res = $dep_caj;
            } else {

                $dep_caj['total_efec'] = 0;
                $dep_caj['total_tarj'] = 0;

                $res = $dep_caj;
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}




function getMov_ban($sede, $fecha1, $fecha2, $data)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($data == 'sin') {

                $sql = "SELECT SUM(monto_h) as monto_h from mov_ban
                WHERE fecha ='$fecha1' AND anulado = 0 AND origen = 'DEP' AND cta_egre='045'";
            } else {

                $sql = "SELECT SUM(monto_h) as monto_h  from mov_ban
                WHERE fecha  BETWEEN '$fecha1' AND '$fecha2' AND anulado = 0 AND origen = 'DEP' AND cta_egre='045'";
            }




            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $mov_ban['monto_h'] = $row['monto_h'];
                    break;
                }

                $res = $mov_ban;
            } else {

                $mov_ban['monto_h'] = 0;

                $res = $mov_ban;
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}




function getOrd_pago($sede, $fecha1, $fecha2, $data)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($data == 'sin') {

                $sql = "SELECT SUM(monto) as monto from ord_pago
                JOIN benefici ON benefici.cod_ben = ord_pago.cod_ben
                WHERE fecha ='$fecha1' AND anulada = 0   AND ord_num < 6000000 AND cta_egre ='878'";
            } elseif ($data == 'ven') {

                $sql = "SELECT SUM(monto) as monto from ord_pago
                JOIN benefici ON benefici.cod_ben = ord_pago.cod_ben
                WHERE fecha ='$fecha1' AND anulada = 0 AND benefici.ben_des<>'PREVIA SHOP' AND cta_egre <>'878' AND ord_num < 6000000";
            } elseif ($data == 'ven2') {

                $sql = "SELECT SUM(monto) as monto from ord_pago
                 JOIN benefici ON benefici.cod_ben = ord_pago.cod_ben
                 WHERE fecha BETWEEN '$fecha1' AND '$fecha2'  AND anulada = 0 AND benefici.ben_des<>'PREVIA SHOP' AND cta_egre <>'878' AND ord_num < 6000000";
            } else {

                $sql = "SELECT SUM(monto) as monto from ord_pago
                JOIN benefici ON benefici.cod_ben = ord_pago.cod_ben
                WHERE fecha  BETWEEN '$fecha1' AND '$fecha2' AND anulada = 0  AND ord_num < 6000000 AND cta_egre ='878'";
            }




            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $ord_pago['monto'] = $row['monto'];

                    break;
                }

                $res = $ord_pago;
            } else {

                $ord_pago['monto'] = 0;

                $res = $ord_pago;
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


function getOrd_pago_inf($sede, $fecha1, $fecha2)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT fecha,ord_num,descrip,monto  from ord_pago
            WHERE fecha BETWEEN '$fecha1' AND '$fecha2' AND anulada = 0   AND ord_num < 6000000 AND cta_egre ='878'";

            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $ord_pago[] = $row;
                }

                $res = $ord_pago;
            } else {

                $ord_pago = 0;

                $res = $ord_pago;
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}





function getTasas($sede, $fecha1)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            if ($sede == "Sucursal Caracas I" or $sede == "Comercial Merina") {

                $sql = "SELECT TOP 1 CONVERT(numeric(10,2), tasa_v) as tasa_v  from tasas 
                where Convert(char(10), fecha, 111) <= '$fecha1'
                ORDER BY fecha DESC";
            } else {

                $sql = "SELECT TOP 1 CONVERT(numeric(10,2), tasa_v) as tasa_v from tasas 
                where Convert(char(10), fecha, 111) BETWEEN '$fecha1' AND  '$fecha1'
                ORDER BY fecha DESC";
            }



            #$sql = "SELECT TOP 1 tasa_v from tasas 
            #where Convert(char(10), fecha, 111) <= '$fecha1'
            #ORDER BY fecha DESC";

            #$sql = "SELECT TOP 1 tasa_v from tasas 
            #where fecha >= CAST('$fecha1' AS datetime) ";


            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $tasas['tasa_v'] = $row['tasa_v'];
                    break;
                }

                $res = $tasas;
            } else {

                $tasas['tasa_v'] = 0;

                $res = $tasas;
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}





function getFacturaDetalles($sede, $fecha1, $fecha2)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT

            reng_cob.tp_doc_cob,
            reng_cob.doc_num as FACTURA,
            reng_cob.neto,
             
            cobros.cob_num as COBROS,
            cobros.fec_cob,
            
            reng_tip.tip_cob,
            reng_tip.mont_doc,
            reng_tip.cod_caja,
            reng_tip.des_caja
            

            FROM 
            cobros
            JOIN reng_tip ON cobros.cob_num = reng_tip.cob_num
            JOIN reng_cob ON cobros.cob_num = reng_cob.cob_num
            WHERE cobros.anulado=0  AND cobros.fec_cob BETWEEN'$fecha1' AND '$fecha2'";


            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $cobros[] = $row;
                }

                $res = $cobros;
            } else {

                $cobros = 0;

                $res = $cobros;
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}



function getMov_banco($sede, $fecha)
{


    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT  mov_num , codigo , tipo_op , doc_num , descrip , monto_h , monto_d , idb from mov_ban where fecha ='$fecha'
            order by codigo";


            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $mov_banc[] = $row;
                }

                $res = $mov_banc;
            } else {

                $mov_banc = 0;

                $res = $mov_banc;
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}







/* CONSULTAR MARCA*/
function getLin_art($sede, $fecha1, $fecha2, $data, $linea)
{

    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT art.co_lin,lin_des from factura
            inner join reng_fac on factura.fact_num = reng_fac.fact_num
            inner join art on reng_fac.co_art = art.co_art
            inner join lin_art on lin_art.co_lin = art.co_lin
            where factura.anulada=0 and factura.fec_emis BETWEEN '$fecha1' AND '$fecha2'
            group by art.co_lin,lin_des";




            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $factura['co_lin'] = $row['co_lin'];
                    $factura['lin_des'] = $row['lin_des'];
                    break;
                }

                $res = $factura;
            } else {

                $factura['co_lin'] = 0;
                $factura['lin_des'] = 0;

                $res = $factura;
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}





/* CONSULTAR MARCA*/
function getSub_lin($sede, $fecha1, $fecha2, $data, $linea)
{

    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT art.co_subl, subl_des , art.co_lin ,lin_des from factura
            inner join reng_fac on factura.fact_num = reng_fac.fact_num
            inner join art on reng_fac.co_art = art.co_art
            inner join lin_art on lin_art.co_lin = art.co_lin
            inner join sub_lin on sub_lin.co_subl = art.co_subl
            where factura.anulada=0 and factura.fec_emis BETWEEN '$fecha1' AND '$fecha2'
            group by art.co_subl,subl_des, art.co_lin ,lin_des
            order by lin_des";




            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta != null) {
                while ($row = sqlsrv_fetch_array($consulta)) {

                    $factura['co_subl'] = $row['co_subl'];
                    $factura['subl_des'] = $row['subl_des'];
                    $factura['co_lin'] = $row['co_lin'];
                    $factura['lin_des'] = $row['lin_des'];
                    break;
                }

                $res = $factura;
            } else {

                $factura['co_subl'] = 0;
                $factura['subl_des'] = 0;
                $factura['co_lin'] = 0;
                $factura['lin_des'] = 0;

                $res = $factura;
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}




function getVendido_Grafica($sede, $fecha1, $fecha2)
{

    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT SUM (CONVERT(numeric(10,0), reng_fac.total_art)) as total_art,lin_art.lin_des from factura 
            inner join reng_fac on factura.fact_num=reng_fac.fact_num
            inner join art  on art.co_art=reng_fac.co_art
            inner join lin_art on lin_art.co_lin=art.co_lin
            where factura.anulada =0 and factura.fec_emis between '$fecha1' and '$fecha2'
            group by lin_art.lin_des 
            order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);

            $connectionInfo2 = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn2 = sqlsrv_connect($serverName, $connectionInfo2);
            while ($row = sqlsrv_fetch_array($consulta)) {

                $total_art = $row['total_art'];
                $linea_des = $row['lin_des'];
                $sql2 = "INSERT INTO art_grafica (linea_des,mes,total_art,tienda) values ('$linea_des','',$total_art,'$sede')";
                $consulta2 = sqlsrv_query($conn2, $sql2);
            }

            if ($consulta2 == null) {

                $res = false;
                return $res;
            } else {

                $res = true;
                return $res;
            }

        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


function getDev_Grafica($sede, $fecha1, $fecha2)
{

    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT SUM (CONVERT(numeric(10,0), reng_dvc.total_art)) as total_dev,lin_art.lin_des from dev_cli
			inner join reng_dvc  on dev_cli.fact_num=reng_dvc.fact_num
			inner join art  on art.co_art=reng_dvc.co_art
            inner join lin_art on lin_art.co_lin=art.co_lin
			where dev_cli.anulada =0 and dev_cli.fec_emis between '$fecha1' and '$fecha2'
            group by lin_art.lin_des 
            order by total_dev desc";

            $consulta = sqlsrv_query($conn, $sql);

            $connectionInfo2 = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn2 = sqlsrv_connect($serverName, $connectionInfo2);
            while ($row = sqlsrv_fetch_array($consulta)) {

                $total_art = $row['total_dev'];
                $linea_des = $row['lin_des'];
                $sql2 = "INSERT INTO art_grafica_dev (linea_des,mes,total_dev,tienda) values ('$linea_des','',$total_art,'$sede')";
                $consulta2 = sqlsrv_query($conn2, $sql2);
            }

            if ($consulta2 == null) {

                $res = false;
                return $res;
            } else {

                $res = true;
                return $res;
            }

        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


function deleteVendido_Grafica()
{


    try {

        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        $sql = "DELETE FROM art_grafica";

        $consulta = sqlsrv_query($conn, $sql);

        if ($consulta == null) {

            $res = false;
            return $res;
        } else {

            $res = true;
            return $res;
        }
    } catch (\Throwable $th) {

        throw $th;
    }
}


/* 
  CREATE TABLE art_grafica (
  linea_des VARCHAR(200) ,
  mes VARCHAR(200) ,
  total_art INT  ,
  tienda VARCHAR(200) NOT NULL  ,
  )

    CREATE TABLE art_grafica_dev (
  linea_des VARCHAR(200) ,
  mes VARCHAR(200) ,
  total_dev INT  ,
  tienda VARCHAR(200) NOT NULL  ,
  )

); 
*/


