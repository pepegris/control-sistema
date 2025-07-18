<?php



$sedes_ar = array(
    "Previa Shop",
    "Inv Jorinacha",
    "Sucursal Caracas I",
    "Sucursal Caracas II",
    "Sucursal Cagua",
    "Sucursal Maturin",
    "Sucursal Coro1" ,
    "Sucursal Coro2" ,
    "Sucursal Coro3" ,
    "Sucursal PtoFijo1" ,
    "Sucursal PtoFijo2" ,
    "Sucursal Ojeda" ,
    "Sucursal Valle" ,
    "Sucursal GuiGue",
    
    


    "Comercial Punto Fijo",
    "Comercial Valena",
    "Comercial Nachari",
    "Comercial Higue",
    "Comercial Apura",
    "Comercial Vallepa",
    "Comercial Ojena",
    "Comercial Puecruz",
    "Comercial Acari",
    "Comercial Catica II",
);

$consultas = array(
    "Ordenes de Pago",
    "Documentos de Compras",
    "Movimiento de Caja",
    "Movimiento de Banco",

);




function Database2($sede)
{

    $bd = array(
        "Previa Shop" => 'C_PREVIA',
        "Inv Jorinacha" => 'C_JORINA',
        "Sucursal Caracas I" => 'C_CARACAS1',
        "Sucursal Caracas II" => 'C_CARACAS2',
        "Sucursal Cagua" => 'C_CAGUA',
        "Sucursal Maturin" => 'C_MATURIN',
        "Sucursal Coro1" => 'C_CORO1',
        "Sucursal Coro2" => 'C_CORO2',
        "Sucursal Coro3" => 'C_CORO3',
        "Sucursal PtoFijo1" => 'C_PTOFIJO1',
        "Sucursal PtoFijo2" => 'C_PTOFIJO2',
        "Sucursal Ojeda" => 'C_OJEDA',
        "Sucursal Valle" => 'C_VAPASCUA',
        "Sucursal GuiGue"=> 'C_GUIGUE',


        "Comercial Corina I" => 'C_CORINA1',
        "Comercial Corina II" => 'C_CORINA2',
        "Comercial Punto Fijo" => 'C_PUFIJO',
        "Comercial Valena" => 'C_VALENA',
        "Comercial Trina" => 'C_TRINA',
        "Comercial Nachari" => 'C_NACHARI',
        "Comercial Higue" => 'C_HIGUE',
        "Comercial Apura" => 'C_APURA',
        "Comercial Vallepa" => 'C_VALLEPA',
        "Comercial Ojena" => 'C_OJENA',
        "Comercial Puecruz" => 'C_PUECRUZ',
        "Comercial Acari" => 'C_ACARI',
        "Comercial Catica II" => 'C_CATICA2',
    );

    return $bd[$sede];
}


function Database($sede)
{

    $bd = array(
        "Previa Shop" => 'PREVIA_A',
        "Inv Jorinacha" => 'JORINA',
        "Sucursal Caracas I" => 'CARACAS1',
        "Sucursal Caracas II" => 'CARACAS2',
        "Sucursal Cagua" => 'CAGUA',
        "Sucursal Maturin" => 'MATURIN',
        "Sucursal Coro1" => 'CORO1',
        "Sucursal Coro2" => 'CORO2',
        "Sucursal Coro3" => 'CORO3',
        "Sucursal PtoFijo1" => 'PTOFIJO1',
        "Sucursal PtoFijo2" => 'PTOFIJO2',
        "Sucursal Ojeda" => 'OJEDA',
        "Sucursal Valle" => 'VAPASCUA',
        "Sucursal GuiGue"=> 'GUIGUE',




        "Comercial Corina I" => 'CORINA1',
        "Comercial Corina II" => 'CORINA2',
        "Comercial Punto Fijo" => 'PUFIJO',
        "Comercial Valena" => 'VALENA',
        "Comercial Trina" => 'TRINA',
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
        "Sucursal Caracas I"    =>     'S01',
        "Sucursal Caracas II"    =>     'S02',
        "Sucursal Cagua" => 'S03',
        "Sucursal Maturin" => 'S04',
        "Sucursal Coro1" => 'S05',
        "Sucursal Coro2" => 'S06',
        "Sucursal Coro3" => 'S07',
        "Sucursal PtoFijo1" => 'S08',
        "Sucursal PtoFijo2" => 'S09',
        "Sucursal Ojeda" => 'S10',
        "Sucursal Valle" => 'S11',
        "Sucursal GuiGue"=> 'S12',



        "Comercial Acari"    =>     'T04',
        "Comercial Puecruz"    =>     'T05',
        "Comercial Vallepa"    =>     'T06',

        "Comercial Higue"    =>     'T09',
        "Comercial Valena"    =>     'T10',
        "Comercial Ojena"    =>     'T12',
        "Comercial Punto Fijo"    =>     'T13',
        "Comercial Trina"    =>     'T16',
        "Comercial Apura"    =>     'T17',
        "Comercial Corina I"    =>     'T18',
        "Comercial Nachari"    =>     'T19',
        "Comercial Corina II"    =>     'T22',
        "Comercial Catica II"    =>     'T24',

    );

    return $bd[$sede];
}


function getOrd_pago($sede,  $fecha1, $fecha2,$ord_num)
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($ord_num != 0) {

                $sql = "SELECT cta_egre,monto_a FROM reng_opg  WHERE ord_num = '$ord_num'";

            }else {

                $sql = "SELECT             
                ord_pago.ord_num, ord_pago.mov_num  , 
                ord_pago.cod_cta , 
                ord_pago.cta_egre , cta_ingr.descrip as cta_egre_descrip  ,
                ord_pago.tipo_imp,ord_pago.cod_caja , ord_pago.descrip ,
                ord_pago.monto , ord_pago.cheque , ord_pago.fecha
                FROM ord_pago
                JOIN benefici ON benefici.cod_ben = ord_pago.cod_ben
                JOIN cta_ingr ON ord_pago.cta_egre=cta_ingr.co_ingr
                WHERE ord_pago.fecha between '$fecha1' and '$fecha2'
                AND ord_pago.anulada=0
                AND cta_egre <>'878'";

            }




            /*$sql = "SELECT 
            ord_pago.ord_num, ord_pago.mov_num  , 
            ord_pago.cod_cta , bancos.des_ban ,
            ord_pago.cta_egre , cta_ingr.descrip as cta_egre_descrip  ,
            ord_pago.tipo_imp,ord_pago.cod_caja , ord_pago.descrip ,
            ord_pago.monto , ord_pago.cheque , ord_pago.fecha
            from ord_pago
            JOIN cta_ingr ON ord_pago.cta_egre=cta_ingr.co_ingr
            JOIN cuentas ON ord_pago.cod_cta=cuentas.cod_cta
            JOIN bancos ON cuentas.co_banco=bancos.co_ban
            where ord_pago.anulada=0   AND ord_pago.fecha between '$fecha1' and '$fecha2'";*/

            $consulta = sqlsrv_query($conn, $sql);


            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $ord_pago[] = $row;
                }
                $res = $ord_pago;
            } else {
                $res = 'N/A';
            }



            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}





function getMov_caj($sede,  $fecha1, $fecha2)
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);



            if ($database == 'JORINA' ) {
                $sql = "SELECT 
                mov_caj.mov_num, mov_caj.descrip, 
                mov_caj.monto_d,  
                mov_caj.cta_egre , cta_ingr.descrip as cta_egre_descrip  , 
                mov_caj.fecha
                FROM mov_caj
                JOIN cta_ingr ON mov_caj.cta_egre=cta_ingr.co_ingr
                where  mov_caj.tipo_op= 'E' AND mov_caj.fecha between '$fecha1' and '$fecha2' AND mov_caj.anulado=0";
            }else {
                $sql = "SELECT 
                mov_caj.mov_num, mov_caj.descrip, 
                mov_caj.monto_d,  
                mov_caj.cta_egre , cta_ingr.descrip as cta_egre_descrip  , 
                mov_caj.fecha
                FROM mov_caj
                JOIN cta_ingr ON mov_caj.cta_egre=cta_ingr.co_ingr
                where cta_egre <>'045' AND cta_egre <>'878' and mov_caj.tipo_op= 'E' AND mov_caj.fecha between '$fecha1' and '$fecha2' AND mov_caj.anulado=0";
            }


            $consulta = sqlsrv_query($conn, $sql);


            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $mov_caj[] = $row;
                }
                $res = $mov_caj;
            } else {
                $res = 'N/A';
            }



            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}




function getMov_ban($sede,  $fecha1, $fecha2)
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($database == 'JORINA' ) {
                $sql = "SELECT 
                mov_ban.mov_num, 
                mov_ban.descrip, 
                mov_ban.monto_d,  
                mov_ban.cta_egre , 
                mov_ban.fecha,
                cta_ingr.descrip as cta_egre_descrip  
                FROM mov_ban
                JOIN cta_ingr ON mov_ban.cta_egre=cta_ingr.co_ingr
                WHERE   origen <>'OPA' AND mov_ban.fecha between '$fecha1' and '$fecha2'AND mov_ban.anulado=0";
            }else {
                $sql = "SELECT 
                mov_ban.mov_num, 
                mov_ban.descrip, 
                mov_ban.monto_d,  
                mov_ban.cta_egre , 
                mov_ban.fecha,
                cta_ingr.descrip as cta_egre_descrip  
                FROM mov_ban
                JOIN cta_ingr ON mov_ban.cta_egre=cta_ingr.co_ingr
                WHERE   origen <>'OPA' AND cta_egre <>'045' AND cta_egre <>'415' AND mov_ban.fecha between '$fecha1' and '$fecha2'AND mov_ban.anulado=0";
            }



            $consulta = sqlsrv_query($conn, $sql);


            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $mov_ban[] = $row;
                }
                $res = $mov_ban;
            } else {
                $res = 'N/A';
            }



            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


function getDocum_cp($sede,  $fecha1, $fecha2)
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $sql = "SELECT 
            docum_cp.nro_doc, docum_cp.nro_fact, docum_cp.monto_bru as monto_neto , docum_cp.n_control,
            docum_cp.co_cli , prov.prov_des , docum_cp.observa ,docum_cp.fec_emis ,cta_ingr.co_ingr ,cta_ingr.descrip AS co_ingr_prov
            from docum_cp
            JOIN prov ON docum_cp.co_cli = prov.co_prov
			JOIN cta_ingr ON prov.co_ingr = cta_ingr.co_ingr
            WHERE docum_cp.tipo_doc='FACT' AND docum_cp.fec_emis between '$fecha1' and '$fecha2' AND docum_cp.co_cli <>'002' AND  docum_cp.anulado=0";

            $consulta = sqlsrv_query($conn, $sql);


            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $docum_cp[] = $row;
                }
                $res = $docum_cp;
            } else {
                $res = 'N/A';
            }



            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}


function getCuenta_contable($sede, $num, $fecha1, $fecha2)
{


    $database = Database2($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $sql = "SELECT 
            comp_num,descri,
            scren_co.co_cue ,
            sccuenta.des_cue ,
            monto_h,
            monto_d,
            fec_emis
            from scren_co 
            JOIN sccuenta ON scren_co.co_cue = sccuenta.co_cue
            where docref='$num' and scren_co.fec_emis between '$fecha1' and '$fecha2'";

            $consulta = sqlsrv_query($conn, $sql);


            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $scren_co[] = $row;
                }
                $res = $scren_co;
            } else {
                $res = 'N/A';
            }

            return $res;
        } catch (\Throwable $th) {

            throw $th;
        }
    } else {

        return 0;
    }
}
