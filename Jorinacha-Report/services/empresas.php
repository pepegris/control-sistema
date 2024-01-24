<?php



$sedes_ar = array(
    "Previa Shop",
    "Sucursal Caracas I",
    "Sucursal Caracas II",
    "Sucursal Cagua",

    "Sucursal Maturin",


    "Comercial Acari" ,
    "Comercial Puecruz" ,
    "Comercial Vallepa" ,
    "Comercial Higue" ,
    "Comercial Valena" ,
    "Comercial Ojena" ,
    "Comercial Punto Fijo" ,
    "Comercial Trina" ,
    "Comercial Apura" ,
    "Comercial Corina I" ,
    "Comercial Nachari" ,
    "Comercial Corina II" ,
    "Comercial Catica II" ,
);



function Database2($sede)
{

    $bd = array(
        "Previa Shop" => 'PREVIA_A',
        "Sucursal Caracas I" => 'CARACAS1',
        "Sucursal Caracas II" => 'CARACAS2',
        "Sucursal Cagua" => 'CAGUA',

        "Sucursal Maturin" => 'MATURIN',


        "Comercial Acari" => 'ACARI21',
        "Comercial Puecruz" => 'PUECRU21',
        "Comercial Vallepa" => 'VALLEP21',
        "Comercial Higue" => 'HIGUE21',
        "Comercial Valena" => 'VALENA21',
        "Comercial Ojena" => 'OJENA21',
        "Comercial Punto Fijo" => 'PUFIJO21',
        "Comercial Trina" => 'TRAINA21',
        "Comercial Apura" => 'APURA21',
        "Comercial Corina I" => 'CORINA21',
        "Comercial Nachari" => 'NACHAR21',
        "Comercial Corina II" => 'CORI2_21',
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
        "Sucursal Maturin" => 'MATURIN',

        
        "Comercial Acari" => 'ACARI',
        "Comercial Puecruz" => 'PUECRUZ',
        "Comercial Vallepa" => 'VALLEPA',
        "Comercial Higue" => 'HIGUE',
        "Comercial Valena" => 'VALENA',
        "Comercial Ojena" => 'OJENA',
        "Comercial Punto Fijo" => 'PUFIJO',
        "Comercial Trina" => 'TRINA',
        "Comercial Apura" => 'APURA',
        "Comercial Corina I" => 'CORINA1',    
        "Comercial Nachari" => 'NACHARI',
        "Comercial Corina II" => 'CORINA2',
        "Comercial Catica II" => 'CATICA2',

        "Comercial Merina" => 'MERINA',
        "Comercial Merina3" => 'MERINA3',
        "Comerical Kagu" => 'KAGU',
        "Comercial Matur" => 'MATUR',

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

