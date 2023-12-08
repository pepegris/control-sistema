<?php



require "../../services/empresas.php";




function Factura_Ordenes($sede,$fecha)
{


    $database = Database($sede);
    $cliente = Cliente($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            if ($cliente =='S04' or $cliente =='S03' 
                or $cliente =='S02' or $cliente =='S01'  ) {

                $sql = "SELECT fact_num,contrib,
                CONVERT(numeric(10,2), saldo) AS saldo ,CONVERT(numeric(10,2), tot_bruto)AS tot_bruto ,CONVERT(numeric(10,2), tot_neto)AS tot_neto ,CONVERT(numeric(10,2), iva)   AS iva
                FROM not_ent 
                WHERE co_cli='$cliente' AND FEC_EMIS='$fecha' AND campo7 <>'IMPORTADOp'  AND anulada=0";


            }else{

                $sql = "SELECT fact_num,contrib,
                CONVERT(numeric(10,2), saldo) AS saldo ,CONVERT(numeric(10,2), tot_bruto)AS tot_bruto ,CONVERT(numeric(10,2), tot_neto)AS tot_neto ,CONVERT(numeric(10,2), iva)   AS iva
                FROM factura 
                WHERE co_cli='$cliente' AND FEC_EMIS = '$fecha' AND  campo7 <>'IMPORTADOp' AND anulada=0";

            }

            $consulta = sqlsrv_query($conn, $sql);
            $r=0;
            while ($row = sqlsrv_fetch_array($consulta)) {

                $ordenes_facturas[$r]['fact_num'] = $row['fact_num'] ;
                $ordenes_facturas[$r]['contrib'] = $row['contrib'] ;
                $ordenes_facturas[$r]['saldo'] = $row['saldo'] ;
                $ordenes_facturas[$r]['tot_bruto'] = $row['tot_bruto'] ;
                $ordenes_facturas[$r]['tot_neto'] = $row['tot_neto'] ;
                $ordenes_facturas[$r]['iva'] = $row['iva'] ;
                $r++;
            }

            if ($consulta == null) {

                return "IMPORTADOS";

            }else {

                $res = $ordenes_facturas;
                return $res;
                
            }


        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


function Reng_Factura($sede,$fecha,$fact_num)
{


    $database = Database($sede);
    $cliente = Cliente($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);



            if ($cliente =='S04' or $cliente =='S03' 
            or $cliente =='S02' or $cliente =='S01'  ) {

                $sql = "SELECT not_ent.fact_num,reng_num,reng_nde.co_art, 
                CONVERT(numeric(10,0), reng_nde.total_art) AS total_art, CONVERT(numeric(10,2), reng_nde.prec_vta) AS prec_vta,
                CONVERT(numeric(10,2), reng_nde.reng_neto) AS reng_neto,
                CONVERT(numeric(10,2),art.cos_pro_un) AS  cos_pro_un , 
                CONVERT(numeric(10,2),art.ult_cos_un) AS  ult_cos_un, 
                CONVERT(numeric(10,2),art.ult_cos_om) AS  ult_cos_om,
                CONVERT(numeric(10,2),art.cos_pro_om) AS  cos_pro_om
                FROM reng_nde
                INNER JOIN not_ent  ON reng_nde.fact_num = not_ent.fact_num
                inner join art on art.co_art = reng_nde.co_art
                WHERE co_cli='$cliente' AND FEC_EMIS='$fecha' AND not_ent.fact_num='$fact_num' AND anulada=0";


        }else{



            $sql = "SELECT factura.fact_num,reng_num,reng_fac.co_art, 
            CONVERT(numeric(10,0), reng_fac.total_art) AS total_art, CONVERT(numeric(10,2), reng_fac.prec_vta) AS prec_vta,
            CONVERT(numeric(10,2), reng_fac.reng_neto) AS reng_neto,
            CONVERT(numeric(10,2),art.cos_pro_un) AS  cos_pro_un , 
            CONVERT(numeric(10,2),art.ult_cos_un) AS  ult_cos_un, 
            CONVERT(numeric(10,2),art.ult_cos_om) AS  ult_cos_om,
            CONVERT(numeric(10,2),art.cos_pro_om) AS  cos_pro_om
            FROM reng_fac
            INNER JOIN factura  ON reng_fac.fact_num = factura.fact_num
            inner join art on art.co_art = reng_fac.co_art
            WHERE co_cli='$cliente' AND FEC_EMIS='$fecha' AND factura.fact_num='$fact_num' AND anulada=0";

        }



            $consulta = sqlsrv_query($conn, $sql);

            $r=0;
            while ($row = sqlsrv_fetch_array($consulta)) {

                $Reng_Factura[$r]['fact_num'] = $row['fact_num'] ;
                $Reng_Factura[$r]['reng_num'] = $row['reng_num'] ;
                $Reng_Factura[$r]['co_art'] = $row['co_art'] ;
                $Reng_Factura[$r]['total_art'] = $row['total_art'] ;
                $Reng_Factura[$r]['prec_vta'] = $row['prec_vta'] ;
                $Reng_Factura[$r]['reng_neto'] = $row['reng_neto'] ;
                $Reng_Factura[$r]['cos_pro_un'] = $row['cos_pro_un'] ;
                $Reng_Factura[$r]['ult_cos_un'] = $row['ult_cos_un'] ;
                $Reng_Factura[$r]['ult_cos_om'] = $row['ult_cos_om'] ;
                $Reng_Factura[$r]['cos_pro_om'] = $row['cos_pro_om'] ;
                $r++;
            }
            $res = $Reng_Factura;

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}



#########################################################################################################################################################
#########################################################################################################################################################
#########################################################################################################################################################



function Ordenes_Compra($sede,$fact_num,$contrib,$saldo,$tot_bruto,$tot_neto,$iva )
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $dif=$tot_bruto/16;
            if ($database=='CAGUA') {
                $sql = "INSERT into ordenes (fact_num,contrib,status,comentario,descrip
                ,co_sucu,forma_pag,moneda,co_cli,co_ven,co_tran,
                saldo,tot_bruto,tot_neto,iva,
                tasag,tasag10,co_us_in,co_us_mo,dis_cen)
                values('10$fact_num',$contrib,0,'<Orden de Compra Importada>','Factura $fact_num',
                1,'CRED','BOD','002',127,1,
                $saldo,$tot_bruto,$tot_neto,$iva ,
                16,12,'001','001','<IVA> <1>16/$tot_bruto/$dif</1>  </IVA> ')";
            }else{
                $sql = "INSERT into ordenes (fact_num,contrib,status,comentario,descrip
                ,co_sucu,forma_pag,moneda,co_cli,co_ven,co_tran,
                saldo,tot_bruto,tot_neto,iva,
                tasag,tasag10,co_us_in,co_us_mo,dis_cen)
                values('10$fact_num',$contrib,0,'<Orden de Compra Importada>','Factura $fact_num',
                1,'CRED','BSD','002',127,1,
                $saldo,$tot_bruto,$tot_neto,$iva ,
                16,12,'001','001','<IVA> <1>16/$tot_bruto/$dif</1>  </IVA> ')";
            }
    


            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta == null) {

                $res = false;
                return $res;
            }else{

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





function Reng_Ordenes($sede,$fact_num,$reng_num,$co_art,$total_art,$prec_vta,$reng_neto,
$cos_pro_un,
$ult_cos_un,
$ult_cos_om,
$cos_pro_om)
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);



            $sql = "INSERT into reng_ord 
            (fact_num,reng_num,comentario,
            uni_venta,total_uni, co_alma,tipo_imp,
            co_art,total_art,pendiente,prec_vta,prec_vta2,reng_neto,
            cos_pro_un,ult_cos_un,ult_cos_om,cos_pro_om)
            values('10$fact_num',$reng_num,'<Orden de Compra Importada>',
            'PAR',1, 1,1,
            '$co_art',$total_art,$total_art,$prec_vta,$prec_vta,$reng_neto,
            $cos_pro_un,$ult_cos_un,$ult_cos_om,$cos_pro_om)";


            $consulta = sqlsrv_query($conn, $sql);

            if ($consulta == null) {

                $res = false;
                return $res;

            }else{
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




#########################################################################################################################################################
#########################################################################################################################################################
##################################################################################################################################################################################################################################################################################################################
#########################################################################################################################################################
#########################################################################################################################################################



function Con_Reng_Ordenes($sede,$fact_num,$reng_num)
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $sql = "SELECT fact_num,reng_num from reng_ord where fact_num='10$fact_num' and reng_num='$reng_num'";





            $consulta = sqlsrv_query($conn, $sql);

            $r=0;
            while ($row = sqlsrv_fetch_array($consulta)) {

                $Reng_Factura[$r]['fact_num'] = $row['fact_num'] ;
                $Reng_Factura[$r]['reng_num'] = $row['reng_num'] ;

                $r++;
            }
            $res = $Reng_Factura;

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}



#########################################################################################################################################################
#########################################################################################################################################################
#########################################################################################################################################################
#########################################################################################################################################################
#########################################################################################################################################################
#########################################################################################################################################################





function Up_Factura_Ordenes($sede,$fecha,$fact_num,$status1,$status2)
{


    $database = Database($sede);
    $cliente = Cliente($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "PREVIA_A", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            if ($cliente =='S04' or $cliente =='S03' 
                or $cliente =='S02' or $cliente =='S01'  ) {

                $sql = "UPDATE not_ent SET campo7='IMPORTADO' WHERE co_cli='$cliente' AND FEC_EMIS='$fecha' AND fact_num='$fact_num' AND anulada=0";
                $documento="Nota de Entrega $fact_num";        
            }else{

                $sql = "UPDATE factura SET campo7='IMPORTADO' WHERE co_cli='$cliente' AND FEC_EMIS='$fecha' AND fact_num='$fact_num' AND anulada=0";
                $documento="Factura $fact_num"; 
            }

            if ($status1==true && $status2==true) {
                $consulta = sqlsrv_query($conn, $sql);

                if ($consulta == null) {

                    return "No se Pudo Importar la $documento";
                    
                }else {
                    return "Fue importada con Éxito la $documento a la Tienda $sede";
                }

                
            } else {
                return "No se Pudo Importar la $documento";
            }
            
 
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


