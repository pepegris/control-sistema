<?php
// config_replicas.php
// COMPATIBILIDAD:
// 'db'          => Base de datos REMOTA (Usada por Reinicio de Réplica y VPN)
// 'publicacion' => Nombre de Publicación (Usada por Reinicio de Réplica)
// 'db_local'    => Base de datos LOCAL en SQL2K8 (Usada solo para Crear Artículos si falla VPN)

$lista_replicas = [
    'ACARIGUA'     => ['ip' => '26.35.57.24',           'db' => 'ACARIGUA', 'publicacion' => 'ACARIGUA', 'db_local' => 'ACARIGUA'],
    'APURE'        => ['ip' => '26.77.57.145',          'db' => 'APURA',    'publicacion' => 'APURA',    'db_local' => 'APURA'],
    'CAGUA'        => ['ip' => '26.179.47.19\KAGUPREV', 'db' => 'CAGUA',    'publicacion' => 'CAGUA',    'db_local' => 'CAGUA'],
    'CARACAS 1'    => ['ip' => '26.163.49.171',         'db' => 'CARACAS1', 'publicacion' => 'CARACAS1', 'db_local' => 'CARACAS1'],
    'CORO 1'       => ['ip' => '26.40.138.208',         'db' => 'CORO1',    'publicacion' => 'CORO1',    'db_local' => 'CORO1'],
    'CORO 2'       => ['ip' => '26.10.12.209',          'db' => 'CORO2',    'publicacion' => 'CORO2',    'db_local' => 'CORO2'],
    'CORO 3'       => ['ip' => '26.20.32.25\CORINA2',   'db' => 'CORO3',    'publicacion' => 'CORO3',    'db_local' => 'CORO3'],
    
    // GUIGUE: Remoto es GUIGE_A, Local es GUIGUE
    'GUIGUE'       => ['ip' => '26.160.180.159\CATICA2','db' => 'GUIGE_A',  'publicacion' => 'GUIGUE',   'db_local' => 'GUIGUE'],
    
    'HIGUEROTE'    => ['ip' => '26.185.48.247',         'db' => 'HIGUE',    'publicacion' => 'HIGUE',    'db_local' => 'HIGUE'],
    'MATURIN'      => ['ip' => '26.25.243.193',         'db' => 'MATURIN',  'publicacion' => 'MATURIN',  'db_local' => 'MATURIN'],
    'OJEDA'        => ['ip' => '26.173.204.113\OJENA',  'db' => 'OJEDA',    'publicacion' => 'OJEDA',    'db_local' => 'OJEDA'],
    'PUNTO FIJO 1' => ['ip' => '26.248.191.230\PUFIJO', 'db' => 'PTOFIJO1', 'publicacion' => 'PTOFIJO1', 'db_local' => 'PTOFIJO1'],
    'PUNTO FIJO 2' => ['ip' => '26.229.9.68\NACHARI',   'db' => 'PTOFIJO2', 'publicacion' => 'PTOFIJO2', 'db_local' => 'PTOFIJO2'],
    
    // PUERTO: Remoto es PUERTOX, Local es PUERTO
    'PUERTO'       => ['ip' => '26.80.3.26',            'db' => 'PUERTOX',  'publicacion' => 'PUERTO',   'db_local' => 'PUERTO'],
    
    // VALENCIA: Remoto es VALE_A21, Local es VALENA
    'VALENCIA'     => ['ip' => '26.42.16.172\VALENA',   'db' => 'VALE_A21', 'publicacion' => 'VALENA',   'db_local' => 'VALENA'],
    
    'VALLE PASCUA' => ['ip' => '26.214.82.1\VALLE',     'db' => 'VAPASCUA', 'publicacion' => 'VAPASCUA', 'db_local' => 'VAPASCUA']
];
?>