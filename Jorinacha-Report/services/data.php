<?php

/* OBTENER NOMBRE DE LA BASE DE DATO SELECCIONADA*/


$sedes = array(
    "Previa Shop",
    "Comercial Merina" ,
    "Comercial Merina III" ,
    "Comercial Corina I" ,
    "Comercial Corina II" ,
    "Comercial Punto Fijo" ,
    "Comercial Matur" ,
    "Comercial Valena" ,
    "Comercial Trina" ,
    "Comercial Kagu" ,
    "Comercial Nachari" ,
    "Comercial Higue" ,
    "Comercial Apura",
    "Comercial Vallepa",
    "Comercial Ojena",
    "Comercial Puecruz",
    "Comercial Acari",
    "Comercial Catica II",
);



function Database($sede)
{

$bd = array(
    "Previa Shop" => 'PREVIA_A',
    "Comercial Merina" => 'MERINA21',
    "Comercial Merina III" => 'MRIA3A21',
    "Comercial Corina I" => 'CORINA21',
    "Comercial Corina II" => 'CORI2_21',
    "Comercial Punto Fijo" => 'PUFIJO21',
    "Comercial Matur" => 'MATURA21',
    "Comercial Valena" => 'VALENA21',
    "Comercial Trina" => 'TRAINA21',
    "Comercial Kagu" => 'KAGUA21',
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

function Cliente($sede)
{

$bd = array(
    "Comercial Merina"    =>     'T20',
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
