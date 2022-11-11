<?php


$estilo1='normal';
$estilo2='normal';
$estilo3='normal';
$estilo4='normal';
$estilo5='normal';


if ($total_prec_vta5 >=1) {
  $estilo1='bold';
}else {
  $estilo1='normal';
}

if ($total_prec_vta3 >=1) {
  $estilo2='bold';
}else {
  $estilo2='normal';
}

if ($stock_act >=1) {
  $estilo3='bold';
}else {
  $estilo3='normal';
}

if ($total_vendido >=1) {
  $estilo4='bold';
}else {
  $estilo4='normal';
}

if ($$cant_bultos >=1) {
  $estilo5='bold';
}else {
  $estilo5='normal';
}

