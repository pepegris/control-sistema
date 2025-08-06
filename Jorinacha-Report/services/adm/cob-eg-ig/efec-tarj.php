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
    "Sucursal Puerto",
    
    


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
        "Sucursal Puerto"=> 'C_PUERTO',


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
        "Sucursal Puerto"=> 'PUERTO',




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
        "Sucursal Puerto"=> 'S13',



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