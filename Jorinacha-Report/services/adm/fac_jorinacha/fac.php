<?php


/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA*/


require "../../services/empresas.php";



/* CONSULTAR ARTICULOS */
function getFact( $fact_num)
{

        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $sql = "SELECT fact_num,fec_emis,rif,
                    CONVERT(numeric(10,2), tot_bruto)as tot_bruto,
                    CONVERT(numeric(10,2), FACTURA.iva)as iva,
                    CONVERT(numeric(10,2), tot_neto) as tot_neto ,
                    cli_des,direc1,telefonos
                    FROM FACTURA 
                    INNER JOIN clientes ON FACTURA.co_cli=clientes.co_cli
                    where fact_num='$fact_num'";



            $consulta = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($consulta)) {

                $consulta['fact_num'] = $row['fact_num'];
                $consulta['fec_emis'] = $row['fec_emis'];
                $consulta['tot_bruto'] = $row['tot_bruto'];
                $consulta['iva'] =  $row['iva'];
                $consulta['tot_neto'] = $row['tot_neto'];
                $consulta['cli_des'] = $row['cli_des'];
                $consulta['direc1'] =  $row['direc1'];
                $consulta['telefonos'] =  $row['telefonos'];
                $consulta['rif'] =  $row['rif'];

                break;
            }
            $res = $consulta;
            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }

}



/* CONSULTAR ARTICULOS */
function get_Reng_Fact( $fact_num)
{

        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $sql = "SELECT art_des,
                    CONVERT(numeric(10,0), total_art) as total_art ,
                    CONVERT(numeric(10,2), prec_vta) as prec_vta ,
                    CONVERT(numeric(10,2), reng_neto) as reng_neto from reng_fac 
                    INNER JOIN art ON reng_fac.co_art=art.co_art
                    where fact_num='$fact_num'";



            $consulta = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($consulta)) {

                $art[] = $row;
            }
            $res = $art;
            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }

}
