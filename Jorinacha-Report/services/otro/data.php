<?php

/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA*/


$sedes_ar = array(
    "Previa Shop",
    "Sucursal Caracas I",
    "Sucursal Caracas II",
    "Comercial Corina I",
    "Comercial Corina II",
    "Comercial Punto Fijo",
    "Comercial Matur",
    "Comercial Valena",
    "Comercial Trina",
    "Sucursal Cagua" ,
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
        "Comercial Corina I" => 'CORINA21',
        "Comercial Corina II" => 'CORI2_21',
        "Comercial Punto Fijo" => 'PUFIJO21',
        "Comercial Matur" => 'MATURA21',
        "Comercial Valena" => 'VALENA21',
        "Comercial Trina" => 'TRAINA21',
        "Sucursal Cagua" => 'CAGUA',
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
        "Comercial Corina I" => 'CORINA1',
        "Comercial Corina II" => 'CORINA2',
        "Comercial Punto Fijo" => 'PUFIJO',
        "Comercial Matur" => 'MATUR',
        "Comercial Valena" => 'VALENA',
        "Comercial Trina" => 'TRINA',
        "Sucursal Cagua" => 'CAGUA',
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
        "Comercial Corina I"    =>     'T18',
        "Comercial Corina II"    =>     'T22',
        "Comercial Punto Fijo"    =>     'T13',
        "Comercial Matur"    =>     'T07',
        "Comercial Valena"    =>     'T10',
        "Comercial Trina"    =>     'T16',
        "Sucursal Cagua" => 'S03',
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
