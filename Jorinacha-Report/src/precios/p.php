<?php

require '../../services/adm/precios/precios.php';


$getPedidos_t= getPedidos_t($sedes_ar[1],  '5103624386772');
$pedido_tienda = $getPedidos_t;


var_dump($getPedidos_t);
var_dump($pedido_tienda);
var_dump(getPedidos_t($sedes_ar[1],  '5103624386772'));

