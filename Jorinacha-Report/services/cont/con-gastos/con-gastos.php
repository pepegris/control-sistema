<?php



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
        "Previa Shop" => 'C_PREVIA',
        "Comercial Merina" => 'C_MERINA1',
        "Comercial Merina III" => 'C_MERINA3',
        "Comercial Corina I" => 'C_CORINA1',
        "Comercial Corina II" => 'C_CORINA2',
        "Comercial Punto Fijo" => 'C_PUFIJO',
        "Comercial Matur" => 'C_MATUR',
        "Comercial Valena" => 'C_VALENA',
        "Comercial Trina" => 'C_TRINA',
        "Comercial Kagu" => 'C_KAGU',
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


function getOrd_pago($sede,  $fecha1, $fecha2)
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);


            $sql = "SELECT
            ord_pago.ord_num, ord_pago.mov_num , ord_pago.status , 
            ord_pago.forma_pag , ord_pago.monto , ord_pago.cheque , 
            ord_pago.cod_cta , bancos.des_ban ,
            ord_pago.cta_egre , cta_ingr.descrip ,
            ord_pago.tipo_imp,ord_pago.cod_caja , ord_pago.descrip 
            from ord_pago
            JOIN cta_ingr ON ord_pago.cta_egre=cta_ingr.co_ingr
            JOIN cuentas ON ord_pago.cod_cta=cuentas.cod_cta
            JOIN bancos ON cuentas.co_banco=bancos.co_ban
            where ord_pago.anulada=0   AND ord_pago.fecha between '$fecha1' and '$fecha2'";

            $consulta = sqlsrv_query($conn, $sql);


            if ($consulta != null) {

                while ($row = sqlsrv_fetch_array($consulta)) {

                    $reng_tip[] = $row;
                }
                $res = $reng_tip;

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
