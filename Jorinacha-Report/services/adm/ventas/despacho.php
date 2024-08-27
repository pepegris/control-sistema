<?php


function getFactura_Grafica($sede, $fecha1, $fecha2,$mes,$sede_cliente)
{

        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT SUM (CONVERT(numeric(10,0), reng_fac.total_art)) as total_art,lin_art.lin_des from factura 
            inner join reng_fac on factura.fact_num=reng_fac.fact_num
            inner join art  on art.co_art=reng_fac.co_art
            inner join lin_art on lin_art.co_lin=art.co_lin
            where factura.anulada =0 and factura.fec_emis between '$fecha1' and '$fecha2' and co_cli='$sede_cliente'
            group by lin_art.lin_des 
            order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);

            $connectionInfo2 = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn2 = sqlsrv_connect($serverName, $connectionInfo2);
            while ($row = sqlsrv_fetch_array($consulta)) {

                if ($sede == 'Comercial Merina' ) {
                    $sede = "Sucursal Caracas I";
                  } elseif ($sede == 'Comercial Merina3' ) {
                    $sede = "Sucursal Caracas II";
                  } elseif ($sede == 'Comercial Kagu' ) {
                    $sede = "Sucursal Cagua";
                  } elseif ($sede == 'Comercial Matur') {
                    $sede = "Sucursal Maturin";
                  } 

                $total_art = $row['total_art'];
                $linea_des = $row['lin_des'];
                $sql2 = "INSERT INTO art_grafica (linea_des,mes,total_art,tienda) values ('$linea_des','$mes',$total_art,'$sede')";
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

}




function getOrdenes_Grafica($sede, $fecha1, $fecha2,$mes,$sede_cliente)
{

        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT SUM (CONVERT(numeric(10,0), reng_nde.total_art)) as total_art,lin_art.lin_des from not_ent 
            inner join reng_nde on not_ent.fact_num=reng_nde.fact_num
            inner join art  on art.co_art=reng_nde.co_art
            inner join lin_art on lin_art.co_lin=art.co_lin
            where not_ent.anulada =0 and not_ent.fec_emis between '$fecha1' and '$fecha2' and co_cli='$sede_cliente'
            group by lin_art.lin_des 
            order by total_art desc";

            $consulta = sqlsrv_query($conn, $sql);

            $connectionInfo2 = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn2 = sqlsrv_connect($serverName, $connectionInfo2);
            while ($row = sqlsrv_fetch_array($consulta)) {

                if ($sede == 'Comercial Merina' ) {
                    $sede = "Sucursal Caracas I";
                  } elseif ($sede == 'Comercial Merina3' ) {
                    $sede = "Sucursal Caracas II";
                  } elseif ($sede == 'Comercial Kagu' ) {
                    $sede = "Sucursal Cagua";
                  } elseif ($sede == 'Comercial Matur') {
                    $sede = "Sucursal Maturin";
                  } 

                $total_art = $row['total_art'];
                $linea_des = $row['lin_des'];
                $sql2 = "INSERT INTO art_grafica (linea_des,mes,total_art,tienda) values ('$linea_des','$mes',$total_art,'$sede')";
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

}



function getBultos_Grafica($sede, $fecha1, $fecha2,$mes,$sede_cliente)
{


        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $sql = "SELECT SUM (CONVERT(numeric(10,0), reng_nde.total_uni)) as total_dev,lin_art.lin_des from not_ent
                inner join reng_nde on not_ent.fact_num=reng_nde.fact_num
                inner join art  on art.co_art=reng_nde.co_art
                inner join lin_art on lin_art.co_lin=art.co_lin
                where not_ent.anulada =0 and not_ent.fec_emis between '$fecha1' and '$fecha2'  and co_cli='$sede_cliente'
                group by lin_art.lin_des 
                order by total_dev desc";

            $consulta = sqlsrv_query($conn, $sql);

            $connectionInfo2 = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn2 = sqlsrv_connect($serverName, $connectionInfo2);
            while ($row = sqlsrv_fetch_array($consulta)) {

                if ($sede == 'Comercial Merina' ) {
                    $sede = "Sucursal Caracas I";
                  } elseif ($sede == 'Comercial Merina3' ) {
                    $sede = "Sucursal Caracas II";
                  } elseif ($sede == 'Comercial Kagu' ) {
                    $sede = "Sucursal Cagua";
                  } elseif ($sede == 'Comercial Matur') {
                    $sede = "Sucursal Maturin";
                  } 

                $total_art = $row['total_dev'];
                $linea_des = $row['lin_des'];
                $sql2 = "INSERT INTO art_grafica_dev (linea_des,mes,total_dev,tienda) values ('$linea_des','$mes',$total_art,'$sede')";
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

}


/* CONSULTAR MARCA*/
function getBultos_Grafica_fac($sede, $consulta)
{

    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => 'SISTEMAS', "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE linea_des ='$consulta'
            group by linea_des
            order by total_dev desc";
            
            $consulta = sqlsrv_query($conn, $sql);
            
            while ($row = sqlsrv_fetch_array($consulta)) {
            
                $total_art = $row['total_dev'];
                break;
            }
            return $total_art;

        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}
/* CONSULTAR MARCA*/
function getBultos_Grafica_fac2($sede, $consulta)
{

    $database = Database($sede);
    if ($database != null) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => 'SISTEMAS', "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            $sql = "SELECT linea_des,SUM (CONVERT(numeric(10,0), total_dev)) as total_dev  from art_grafica_dev
            WHERE linea_des ='$consulta' AND  tienda= '$sede'
            group by linea_des
            order by total_dev desc";
            
            $consulta = sqlsrv_query($conn, $sql);
            
            while ($row = sqlsrv_fetch_array($consulta)) {
            
                $total_art = $row['total_dev'];
                break;
            }
            return $total_art;

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

        $sql1 = "DELETE FROM art_grafica";
        $consulta1 = sqlsrv_query($conn, $sql1);


        $sql2 = "DELETE FROM art_grafica_dev";
        $consulta2 = sqlsrv_query($conn, $sql2);

        if ($consulta1 == null && $consulta2 == null ) {

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


?>