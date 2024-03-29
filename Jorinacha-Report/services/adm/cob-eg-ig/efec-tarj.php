<?php



$sedes_ar = array(
    "Previa Shop",
    "Sucursal Caracas I",
    "Sucursal Caracas II",
    "Sucursal Cagua",
    /*"Sucursal Maturin",*/
    "Comercial Matur" ,
    
    "Comercial Corina I",
    "Comercial Corina II",
    "Comercial Punto Fijo",
    "Comercial Valena",
    "Comercial Trina",
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
        "Sucursal Caracas I" => 'CARACAS1',
        "Sucursal Caracas II" => 'CARACAS2',
        "Sucursal Cagua" => 'CAGUA',
       /* "Sucursal Maturin" => 'MATURIN',*/
        "Comercial Matur" => 'MATURA21',
        

        "Comercial Corina I" => 'CORINA21',
        "Comercial Corina II" => 'CORI2_21',
        "Comercial Punto Fijo" => 'PUFIJO21',
        "Comercial Valena" => 'VALENA21',
        "Comercial Trina" => 'TRAINA21',    
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
        "Sucursal Caracas I" => 'CARACAS1',
        "Sucursal Caracas II" => 'CARACAS2',
        "Sucursal Cagua" => 'CAGUA',
        /*"Sucursal Maturin" => 'MATURIN',*/
        "Comercial Matur" => 'MATUR',

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
        /*"Sucursal Maturin" => 'S04',*/
        "Comercial Matur"    =>     'T07',
        

        "Comercial Corina I"    =>     'T18',
        "Comercial Corina II"    =>     'T22',
        "Comercial Punto Fijo"    =>     'T13',
        "Comercial Valena"    =>     'T10',
        "Comercial Trina"    =>     'T16',
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

function getReng_tip($sede, $tip_cob, $fecha1, $fecha2)
{


    $database = Database($sede);


    if ($database) {
        try {

            $serverName = "172.16.1.39";
            $connectionInfo = array("Database" => "$database", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionInfo);




                if ($tip_cob == "todos") {
                    $sql = "SELECT reng_tip.tip_cob, reng_cob.doc_num, reng_tip.cob_num, 
                    reng_tip.movi, reng_tip.nombre_ban, reng_tip.mont_doc,reng_tip.fec_cheq  
                    from reng_tip
                    JOIN cobros ON reng_tip.cob_num=cobros.cob_num
                    JOIN reng_cob ON cobros.cob_num=reng_cob.cob_num
                    WHERE cobros.anulado = 0 and  reng_tip.fec_cheq BETWEEN '$fecha1' AND '$fecha2'
                    order by  reng_cob.doc_num  desc";
                } else {
                    $sql = "SELECT reng_tip.tip_cob, reng_cob.doc_num, reng_tip.cob_num, 
                    reng_tip.movi, reng_tip.nombre_ban, reng_tip.mont_doc,reng_tip.fec_cheq  
                    from reng_tip
                    JOIN cobros ON reng_tip.cob_num=cobros.cob_num
                    JOIN reng_cob ON cobros.cob_num=reng_cob.cob_num
                    WHERE cobros.anulado = 0 and  reng_tip.fec_cheq BETWEEN '$fecha1' AND '$fecha2' AND reng_tip.tip_cob='$tip_cob'
                    order by  reng_cob.doc_num desc";
                }




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

?>