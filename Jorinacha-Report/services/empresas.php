<?php



$sedes_ar = array(
    "Previa Shop",
    "Sucursal Caracas I",
    "Sucursal Cagua",
    "Sucursal Maturin",
    "Sucursal Coro1",
    "Sucursal Coro2",
    "Sucursal Coro3",
    "Sucursal PtoFijo1",
    "Sucursal PtoFijo2",
    "Sucursal Ojeda",
    "Sucursal Valle",
    "Sucursal Guigue",
    "Sucursal Puerto",
    "Sucursal Acarigua",


    "Comercial Higue",
    "Comercial Valena",
    "Comercial Apura",


);


function Meses($mes)
{

    $bd = array(
        "01" => 'Enero',
        "02" => 'Febrero',
        "03" => 'Marzo',
        "04" => 'Abril',
        "05" => 'Mayo',
        "06" => 'Junio',
        "07" => 'Julio',
        "08" => 'Agosto',
        "09" => 'Septiembre',
        "10" => 'Octubre',
        "11" => 'Noviembre',
        "12" => 'Diciembre',

    );

    return $bd[$mes];
}


function Vpn($sede)
{

    $bd = array(
        "Previa Shop" => 'PREVIA_A',
        "Sucursal Caracas I" => '26.163.49.171',
        "Sucursal Cagua" => '26.179.47.19',
        "Sucursal Maturin" => '26.174.83.102',
        "Sucursal Coro1" => '26.40.138.208',
        "Sucursal Coro2" => '26.10.12.209',
        "Sucursal Coro3" => '26.20.32.25',
        "Sucursal PtoFijo1" => '26.248.191.230',
        "Sucursal PtoFijo2" => '26.229.9.68',
        "Sucursal Ojeda" => '26.173.204.113',
        "Sucursal Valle" => '26.214.82.1',
        "Sucursal Guigue" => '26.160.180.159',
        "Sucursal Puerto" => '26.80.3.26',
        "Sucursal Acarigua" => '26.35.57.24',


        "Comercial Higue" => '26.248.157.188',
        "Comercial Valena" => '26.42.16.172',
        "Comercial Apura" => '26.77.57.145',


    );

    return $bd[$sede];
}

function Database2($sede)
{

    $bd = array(
        "Previa Shop" => 'PREVIA_A',

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
        "Sucursal Guigue" => 'GUIGUE',
        "Sucursal Puerto" => 'PUERTO',
        "Sucursal Acarigua" => 'ACARIGUA',


        "Comercial Acari" => 'ACARI',
        "Comercial Puecruz" => 'PUECRU21',
        "Comercial Vallepa" => 'VALLEP21',
        "Comercial Higue" => 'HIGUE21',
        "Comercial Valena" => 'VALENA21',
        "Comercial Ojena" => 'OJENA21',
        "Comercial Punto Fijo" => 'PUFIJO21',
        "Comercial Trina" => 'TRAINA21',
        "Comercial Apura" => 'APURA21',
        "Comercial Corina I" => 'CORINA1',
        "Comercial Nachari" => 'NACHAR21',
        "Comercial Corina II" => 'CORINA2',
        "Comercial Catica II" => 'CATICA2',

        "Comercial Merina" => 'MERINA',
        "Comercial Merina3" => 'MERINA3',
        "Comercial Kagu" => 'KAGU',
        "Comercial Matur" => 'MATUR',


    );

    return $bd[$sede];
}
function Database($sede)
{
    // Esta funcion se mantiene para compatibilidad con codigo viejo
    $bd = array(
        "Previa Shop" => 'PREVIA_A',
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
        "Sucursal Guigue" => 'GUIGUE',
         "Sucursal Puerto" => 'PUERTO',
        "Sucursal Acarigua" => 'ACARIGUA',
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
        "Comercial Kagu" => 'KAGU',
        "Comercial Matur" => 'MATUR',
    );
    
    // Si la sede viene del codigo viejo, la encuentra arriba.
    if(isset($bd[$sede])) return $bd[$sede];

    // Si la sede viene del CODIGO NUEVO (el array de abajo), buscamos su DB
    global $lista_replicas;
    if(isset($lista_replicas[$sede])) return $lista_replicas[$sede]['db'];

    return '';
}

function Cliente($sede)
{
    $bd = array(
        // --- NOMBRES VIEJOS (Mantener para compatibilidad) ---
        "Sucursal Caracas I"    => 'S01',
        "Sucursal Caracas II"   => 'S02',
        "Sucursal Cagua"        => 'S03',
        "Sucursal Maturin"      => 'S04',
        "Sucursal Coro1"        => 'S05',
        "Sucursal Coro2"        => 'S06',
        "Sucursal Coro3"        => 'S07',
        "Sucursal PtoFijo1"     => 'S08',
        "Sucursal PtoFijo2"     => 'S09',
        "Sucursal Ojeda"        => 'S10',
        "Sucursal Valle"        => 'S11',
        "Sucursal Guigue"       => 'S12',
        "Sucursal Puerto"       => 'S13',
        "Sucursal Acarigua"     => 'S14',
        
        "Comercial Acari"       => 'T04',
        "Comercial Puecruz"     => 'T05',
        "Comercial Vallepa"     => 'T06',
        "Comercial Higue"       => 'T09',
        "Comercial Valena"      => 'T10',
        "Comercial Ojena"       => 'T12',
        "Comercial Punto Fijo"  => 'T13',
        "Comercial Trina"       => 'T16',
        "Comercial Apura"       => 'T17',
        "Comercial Corina I"    => 'T18',
        "Comercial Nachari"     => 'T19',
        "Comercial Corina II"   => 'T22',
        "Comercial Catica II"   => 'T24',

        // --- NOMBRES NUEVOS (Mapeo para el nuevo array $lista_replicas) ---
        "ACARIGUA"     => 'S14',
        "APURE"        => 'T17',
        "CAGUA"        => 'S03',
        "CARACAS 1"    => 'S01',
        "CORO 1"       => 'S05',
        "CORO 2"       => 'S06',
        "CORO 3"       => 'S07',
        "GUIGUE"       => 'S12',
        "HIGUEROTE"    => 'T09',
        "MATURIN"      => 'S04',
        "OJEDA"        => 'S10',
        "PUNTO FIJO 1" => 'S08',
        "PUNTO FIJO 2" => 'S09',
        "PUERTO"       => 'S13',
        "VALENCIA"     => 'T10',
        "VALLE PASCUA" => 'S11'
    );

    if (isset($bd[$sede])) {
        return $bd[$sede];
    }
    return '';
}


// COMPATIBILIDAD:
// 'db'          => Base de datos REMOTA (Usada por Reinicio de Réplica y VPN)
// 'publicacion' => Nombre de Publicación (Usada por Reinicio de Réplica)
// 'db_local'    => Base de datos LOCAL en SQL2K8 (Usada solo para Crear Artículos si falla VPN)


$lista_replicas = [
    'ACARIGUA'     => ['ip' => '26.35.57.24',           'db' => 'ACARIGUA', 'db_local' => 'ACARIGUA'],
    'APURE'        => ['ip' => '26.77.57.145',          'db' => 'APURA',    'db_local' => 'APURA'],
    'CAGUA'        => ['ip' => '26.179.47.19\KAGUPREV', 'db' => 'CAGUA',    'db_local' => 'CAGUA'],
    'CARACAS 1'    => ['ip' => '26.163.49.171',         'db' => 'CARACAS1', 'db_local' => 'CARACAS1'],
    'CORO 1'       => ['ip' => '26.40.138.208',         'db' => 'CORO1',    'db_local' => 'CORO1'],
    'CORO 2'       => ['ip' => '26.10.12.209',          'db' => 'CORO2',    'db_local' => 'CORO2'],
    'CORO 3'       => ['ip' => '26.20.32.25\CORINA2',   'db' => 'CORO3',    'db_local' => 'CORO3'],
    'GUIGUE'       => ['ip' => '26.160.180.159\CATICA2','db' => 'GUIGE_A',  'db_local' => 'GUIGUE'],
    'HIGUEROTE'    => ['ip' => '26.185.48.247',         'db' => 'HIGUE',    'db_local' => 'HIGUE'],
    'MATURIN'      => ['ip' => '26.25.243.193',         'db' => 'MATURIN',  'db_local' => 'MATURIN'],
    'OJEDA'        => ['ip' => '26.173.204.113\OJENA',  'db' => 'OJEDA',    'db_local' => 'OJEDA'],
    'PUNTO FIJO 1' => ['ip' => '26.248.191.230\PUFIJO', 'db' => 'PTOFIJO1', 'db_local' => 'PTOFIJO1'],
    'PUNTO FIJO 2' => ['ip' => '26.229.9.68\NACHARI',   'db' => 'PTOFIJO2', 'db_local' => 'PTOFIJO2'],
    'PUERTO'       => ['ip' => '26.80.3.26',            'db' => 'PUERTOX',  'db_local' => 'PUERTO'],
    'VALENCIA'     => ['ip' => '26.42.16.172\VALENA',   'db' => 'VALE_A21', 'db_local' => 'VALENA'],
    'VALLE PASCUA' => ['ip' => '26.214.82.1\VALLE',     'db' => 'VAPASCUA', 'db_local' => 'VAPASCUA']
];
