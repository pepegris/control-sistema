<td colspan="9"><h3>Totales</h3></td>


<td><b>$<?= number_format($total_prec_vta5_todo, 0, ',', '.'); ?></b></td>
<td></td>
<td><b>Bs<?= number_format($total_prec_vta3_todo, 2, ',', '.'); ?></b></td>

<td><b><?= $total_stock_act_previa ?></td>
<td><b><?= $total_vendido_todo ?></td>



<?php

$h = 1;
for ($i = 0; $i < count($total_stock_act_tienda); $i++) {
  $vendido = $total_vendido_tienda[$sedes_ar[$h]];

?>
  <td><b><?= $total_stock_act_tienda[$sedes_ar[$h]] ?></b></td>
  <td><b><?= $total_pedido_tienda [$sedes_ar[$h]] ?></b></td>
  
  <td><b><?= $total_vendido_tienda [$sedes_ar[$h]] ?></b></td>
  
  <td></td>
  <td><b>$<?= number_format($total_prec_vta5_tienda_todo[$sedes_ar[$h]], 0, ',', '.');  ?></b></td>
  <td></td>
  <td><b>Bs<?= number_format($total_prec_vta1_tienda_todo[$sedes_ar[$h]], 2, ',', '.'); ?></b></td>
  <td></td>
  <td></td>
  <td><b>Bs<?=number_format( $total_prec_vta3_costo_tienda_todo[$sedes_ar[$h]]); ?></b></td>
  
  



<?php

  $h++;
}

?>