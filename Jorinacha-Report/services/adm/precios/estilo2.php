<?php





if ($total_vendido >=1) {
  $estilo4='bold';
}else {
  $estilo4='normal';
}


$estilo1='normal';
$estilo2='normal';
$estilo3='normal';
$estilo4='normal';

if ($stock_act_tienda >=1) {
  $estilo1='bold';
}else {
  $estilo1='normal';
}

if ($total_prec_vta5_tienda >=1) {
  $estilo2='bold';
}else {
  $estilo2='normal';
}

if ($total_prec_vta1_tienda >=1) {
  $estilo3='bold';
}else {
  $estilo3='normal';
}

if ($total_prec_vta3_costo_tienda >=1) {
  $estilo4='bold';
}else {
  $estilo4='normal';
}
if ($vendido_tienda >=1) {
  $estilo5='bold';
}else {
  $estilo5='normal';
}

if ($pedido_tienda >=1) {
  $estilo6='bold';
  $estilo='green';
  $signo='+';
}else {
  $estilo6='normal';
  $estilo='white';
  $signo='';

}