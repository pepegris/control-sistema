USE [VALENA_A]
GO
/****** Object:  StoredProcedure [dbo].[isti_c_afecha_articulos]    Script Date: 28/6/2021 12:51:42 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER OFF
GO
ALTER PROCEDURE [dbo].[isti_c_afecha_articulos] @ptipo varchar(1), @part_ini varchar(30) , @part_fin varchar(30),
@palm_ini varchar(6) , @palm_fin varchar(6), @pfecha datetime AS
/* Este fue modificado el 22-08-2003*/
/* Modificado el 18-11-2003 Por devoluciones Proveedor*/
/**Modificado AD: 28-06-2004*/
/**Modificado JNP: 22-09-2005*/
Select b.co_art, b.co_alma, (b.total_art* (case when b.total_uni=0 then 1 else b.total_uni end) ) * -1 as total,
b.stotal_art*-1 as Stotal, 'AJUS' as tipo, c.ajue_num as numero, c.fecha as fecha, 'S' as tipo_tra,
Space(10) as cli_prov, c.anulada as anulada, c.tasa, c.moneda, c.co_us_in, c.fe_us_mo, b.reng_num, b.cost_unit,
b.ult_cos_om as cost_unit_om, b.cos_pro_un as cos_pro_un, b.cos_pro_om as cos_pro_om, c.co_sucu, b.uni_venta, b.nro_lote, b.fec_lote, case when b.total_uni=0 then 1 else b.total_uni end as total_uni
Into #pptemporal1
From tipo_aju a , reng_aju b, ajuste c
Where b.tipo = a.co_tipo and a.tipo_trans = 'S'
And b.ajue_num = c.ajue_num
And b.co_art between @part_ini and @part_fin
And b.co_alma between @palm_ini and @palm_fin
And c.fecha <= @pfecha
And c.anulada = 0
Union ALL
Select b.co_art, b.co_alma, (b.total_art* (case when b.total_uni=0 then 1 else b.total_uni end) )*case when a.tipo_trans = 'E' then 1 else 0 end as total,
b.Stotal_art as Stotal,
Case when a.tipo_trans = 'E' then 'AJUE' else 'AJUC' end as tipo, c.ajue_num as numero, c.fecha as fecha, 'E' as tipo_tra,
Space(10) as cli_prov, c.anulada as anulada, c.tasa, c.moneda, c.co_us_in, c.fe_us_mo, b.reng_num, b.cost_unit,
b.cost_unit/c.tasa as cost_unit_om, b.cost_unit as cos_pro_un, b.cost_unit/c.tasa as cos_pro_om, c.co_sucu,b.uni_venta, b.nro_lote, b.fec_lote, case when b.total_uni=0 then 1 else b.total_uni end as total_uni
From tipo_aju a , reng_aju b, ajuste c
Where b.tipo = a.co_tipo and a.tipo_trans <> 'S' and b.ajue_num = c.ajue_num and
b.co_art between @part_ini and @part_fin and
b.co_alma between @palm_ini and @palm_fin and
c.anulada = 0 and
c.fecha <= @pfecha
Union ALL
Select b.co_art, a.alm_orig AS co_alma, (b.total_art* (case when b.total_uni=0 then 1 else b.total_uni end) ) *-1 as total,
b.Stotal_art*-1 as Stotal, 'TRAS' as tipo, a.tras_num as numero, a.fecha as fecha, 'S' as tipo_tra,
Space(10) as cli_prov, a.anulada as anulada, 1 as tasa, 'BS' as moneda, a.co_us_in, a.fe_us_mo, b.reng_num, b.ult_cos_un as cost_unit,
b.ult_cos_om as cost_unit_om,  b.cos_pro_un as cos_pro_un,  b.cos_pro_om as cos_pro_om, a.co_sucu, b.uni_venta, b.nro_lote, b.fec_lote, case when b.total_uni=0 then 1 else b.total_uni end as total_uni
From tras_alm a, reng_tra b
Where b.tras_num = a.tras_num AND b.co_art between @part_ini and @part_fin and
a.alm_orig between @palm_ini and @palm_fin and
a.anulada = 0 and
a.fecha <= @pfecha 
Union ALL
Select b.co_art, a.alm_dest AS co_alma, (b.total_art* (case when b.total_uni=0 then 1 else b.total_uni end) ) as total,
b.Stotal_art  as Stotal, 'TRAE' as tipo, a.tras_num as numero, a.fec_conf as fecha, 'E' as tipo_tra,
Space(10) as cli_prov, a.anulada as anulada, 1 as tasa, 'BS' as moneda, a.co_us_in, a.fe_us_mo, b.reng_num, b.ult_cos_un as cost_unit,
b.ult_cos_om as cost_unit_om,  b.cos_pro_un as cos_pro_un,  b.cos_pro_om as cos_pro_om, a.co_sucu, b.uni_venta, b.nro_lote, b.fec_lote, case when b.total_uni=0 then 1 else b.total_uni end as total_uni
From tras_alm a, reng_tra b
Where b.tras_num = a.tras_num AND a.confirma = 1 and
b.co_art between @part_ini and @part_fin and
a.alm_dest between @palm_ini and @palm_fin and
a.fec_conf <= @pfecha and
a.anulada = 0
Union ALL
Select b.co_art, '9999  ' AS co_alma, (b.total_art* (case when b.total_uni=0 then 1 else b.total_uni end) ) as total,
b.Stotal_art as Stotal, 'TRAE' as tipo, a.tras_num as numero, a.fecha as fecha, 'E' as tipo_tra,
Space(10) as cli_prov, a.anulada as anulada, 1 as tasa, 'BS' as moneda, a.co_us_in, a.fe_us_mo, b.reng_num, b.ult_cos_un as cost_unit,
b.ult_cos_om as cost_unit_om,  b.cos_pro_un as cos_pro_un,  b.cos_pro_om as cos_pro_om, a.co_sucu,b.uni_venta, b.nro_lote, b.fec_lote, case when b.total_uni=0 then 1 else b.total_uni end as total_uni
From tras_alm a, reng_tra b
Where b.tras_num = a.tras_num AND a.confirma = 0 and
b.co_art between @part_ini and @part_fin and
a.alm_dest between @palm_ini and @palm_fin and
a.fecha <= @pfecha and
a.anulada = 0
Union ALL
Select b.co_art, b.co_alma, (b.total_art* (case when b.total_uni=0 then 1 else b.total_uni end) ) * -1 as total,
b.Stotal_art*-1 as Stotal, 'FACT' as tipo, a.fact_num as numero, a.fec_emis as fecha, 'S' as tipo_tra,
a.co_cli as cli_prov, a.anulada, a.tasa, a.moneda, a.co_us_in, a.fe_us_mo, b.reng_num, b.ult_cos_un as cost_unit,
b.ult_cos_om as cost_unit_om, b.cos_pro_un as cos_pro_un, b.cos_pro_om as cos_pro_om, a.co_sucu,b.uni_venta, b.nro_lote, b.fec_lote, case when b.total_uni=0 then 1 else b.total_uni end as total_uni
From factura a, reng_fac b
Where b.fact_num = a.fact_num and b.tipo_doc <> 'E' and
b.co_art between @part_ini and @part_fin and
b.co_alma between @palm_ini and @palm_fin and
a.fec_emis <= @pfecha and
a.anulada = 0
Union ALL
Select a.co_art, a.co_alma, (a.total_art* (case when a.total_uni=0 then 1 else a.total_uni end) ) * -1 as total,
a.Stotal_art* -1 as Stotal, 'NENT'  AS tipo, b.fact_num as numero, b.fec_emis as fecha, 'S' as tipo_tra,
b.co_cli as cli_prov, b.anulada as anulada, b.tasa, b.moneda, b.co_us_in, b.fe_us_mo, a.reng_num, a.ult_cos_un as cost_unit,
a.ult_cos_om as cost_unit_om, a.cos_pro_un as cos_pro_un, a.cos_pro_om as cos_pro_om, b.co_sucu, a.uni_venta, a.nro_lote, a.fec_lote, case when a.total_uni=0 then 1 else a.total_uni end as total_uni
From reng_nde a, not_ent b
Where a.fact_num = b.fact_num and
a.co_art between @part_ini and @part_fin and
a.co_alma between @palm_ini and @palm_fin and
b.fec_emis <= @pfecha and
b.anulada = 0
Union ALL
Select a.co_art, a.co_alma, (a.total_art* (case when a.total_uni=0 then 1 else a.total_uni end)  ) as total,
a.Stotal_art as Stotal, 'NREC'  as tipo, b.fact_num as numero, b.fec_emis as fecha, 'E' as tipo_tra,
b.co_cli as cli_prov, b.anulada as anulada, b.tasa, b.moneda, b.co_us_in, b.fe_us_mo, a.reng_num, a.reng_neto/a.total_art as cost_unit,
(a.reng_neto/a.total_art)/b.tasa as cost_unit_om, a.reng_neto/a.total_art as cos_pro_un, (a.reng_neto/a.total_art)/b.tasa as cos_pro_om, b.co_sucu, a.uni_venta, a.nro_lote, a.fec_lote, case when a.total_uni=0 then 1 else a.total_uni end as total_uni
From reng_ndr a, not_rec b
Where a.fact_num = b.fact_num and
a.co_art between @part_ini and @part_fin and
a.co_alma between @palm_ini and @palm_fin and
b.fec_emis <= @pfecha and
b.anulada = 0
Union ALL
Select b.co_art, b.co_alma, (b.total_art* (case when b.total_uni=0 then 1 else b.total_uni end) ) as total,
b.Stotal_art as Stotal, 'COMP'  as tipo, a.fact_num as numero, a.fec_emis as fecha, 'E' as tipo_tra,
a.co_cli as cli_prov, a.anulada, a.tasa, a.moneda, a.co_us_in, a.fe_us_mo, b.reng_num, 
case when b.reng_neto=0 then 0 else (b.reng_neto*case when a.tot_bruto=0 then 1 else ((a.tot_neto-a.iva-a.mon_ilc)/a.tot_bruto) end*
case when a.imp_num <> 0 then (1+(b.porc_gas/100)) else 1 end +(b.reng_neto*case when a.tot_bruto=0 then 0 else (a.monto_adi/a.tot_bruto) end))/b.total_art end As cost_unit,
case when b.reng_neto=0 then 0 else ((b.reng_neto*case when a.tot_bruto=0 then 1 else ((a.tot_neto-a.iva-a.mon_ilc)/a.tot_bruto) end*
case when a.imp_num <> 0 then (1+(b.porc_gas/100)) else 1 end +(b.reng_neto*case when a.tot_bruto=0 then 0 else (a.monto_adi/a.tot_bruto) end))/b.total_art)/a.tasa end as cost_unit_om,
case when b.reng_neto=0 then 0 else (b.reng_neto*case when a.tot_bruto=0 then 1 else ((a.tot_neto-a.iva-a.mon_ilc)/a.tot_bruto) end*
case when a.imp_num <> 0 then (1+(b.porc_gas/100)) else 1 end +(b.reng_neto*case when a.tot_bruto=0 then 0 else (a.monto_adi/a.tot_bruto) end))/b.total_art end as cos_pro_un,
case when b.reng_neto=0 then 0 else ((b.reng_neto*case when a.tot_bruto=0 then 1 else ((a.tot_neto-a.iva-a.mon_ilc)/a.tot_bruto) end*
case when a.imp_num <> 0 then (1+(b.porc_gas/100)) else 1 end +(b.reng_neto*case when a.tot_bruto=0 then 0 else (a.monto_adi/a.tot_bruto) end))/b.total_art)/a.tasa end as cos_pro_om,
a.co_sucu, b.uni_venta, b.nro_lote, b.fec_lote, case when b.total_uni=0 then 1 else b.total_uni end as total_uni
From compras a,reng_com b
Where b.fact_num = a.fact_num and b.tipo_doc<>'R' and
b.co_art between @part_ini and @part_fin and
b.co_alma between @palm_ini and @palm_fin and
a.fec_emis <= @pfecha and
a.anulada = 0
Union ALL
Select a.co_art, a.co_alma, (a.total_art* (case when a.total_uni=0 then 1 else a.total_uni end) ) as total,
a.Stotal_art as Stotal, 'DCLI'  as tipo, b.fact_num as numero, b.fec_emis as fecha, 'E' as tipo_tra,
b.co_cli as cli_prov,  b.anulada as anulada, b.tasa, b.moneda, b.co_us_in, b.fe_us_mo, a.reng_num, a.ult_cos_un as cost_unit,
a.ult_cos_om as cost_unit_om, a.cos_pro_un as cos_pro_un, a.cos_pro_om as cos_pro_om, b.co_sucu, a.uni_venta, a.nro_lote, a.fec_lote, case when a.total_uni=0 then 1 else a.total_uni end as total_uni
From reng_dvc a, dev_cli b
Where a.fact_num = b.fact_num and
a.co_art between @part_ini and @part_fin and
a.co_alma between @palm_ini and @palm_fin and
b.fec_emis <= @pfecha and
b.anulada = 0
Union ALL
Select a.co_art, a.co_alma, (a.total_art* (case when a.total_uni=0 then 1 else a.total_uni end) ) *-1 as total,
a.Stotal_art*-1 as Stotal,'DPRO'  as tipo, b.fact_num as numero, b.fec_emis as fecha, 'S' as tipo_tra,
b.co_cli as cli_prov, b.anulada as anulada, b.tasa, b.moneda, b.co_us_in, b.fe_us_mo, a.reng_num, 
case when a.reng_neto=0 then 0 else (a.reng_neto*case when b.tot_bruto=0 then 1 else (b.tot_neto-b.iva-b.mon_ilc)/b.tot_bruto end) /a.total_art end As cost_unit,  
a.ult_cos_om as cost_unit_om, a.cos_pro_un as cos_pro_un, a.cos_pro_om as cos_pro_om, b.co_sucu, a.uni_venta, a.nro_lote, a.fec_lote, case when a.total_uni=0 then 1 else a.total_uni end as total_uni
From reng_dvp a, dev_pro b
Where a.fact_num = b.fact_num and
a.co_art between @part_ini and @part_fin and
a.co_alma between @palm_ini and @palm_fin and
b.fec_emis <= @pfecha and
b.anulada = 0
Union ALL
Select a.co_art,a.co_alma, a.total_art as total,
a.Stotal_art as Stotal, 'GCOM' as tipo, a.gene_num as numero, a.fecha as fecha, 'E' as tipo_tra,
Space(10) as cli_prov, 0 as anulada, a.tasa, a.moneda, a.co_us_in, a.fe_us_mo, 1 as reng_num,
Sum(b.costo_uni*b.total_art)/a.total_art     as cost_unit,
Sum(b.ult_cos_om*b.total_art)/a.total_art  as cost_unit_om,
Sum(b.cos_pro_un*b.total_art)/a.total_art  as cos_pro_un,
Sum(b.cos_pro_om*b.total_art)/a.total_art as cos_pro_om, a. co_sucu, space(1) as uni_venta,  space(1) as nro_lote, getdate() as fec_lote, 1 as total_unit
From gene_kit a , reng_gen b
Where  a.gene_num = b.gene_num and
a.co_art between @part_ini and @part_fin and
a.co_alma between @palm_ini and @palm_fin and
a.fecha <= @pfecha
Group BY  a.co_art, a.gene_num, a.fecha,  a.tasa, a.moneda,
a.co_us_in, a.fe_us_mo, a.co_alma, a.total_art,a.Stotal_art, a. co_sucu
Union ALL
Select a.co_art, a.co_alma, (a.total_art* (case when a.total_uni=0 then 1 else a.total_uni end) ) *-1 as total,
a.Stotal_art* -1 as Stotal, 'RGEN' as tipo, b.gene_num as numero, b.fecha as fecha, 'S' as tipo_tra,
Space(10) as cli_prov, 0 as anulada, b.tasa, b.moneda, b.co_us_in, b.fe_us_mo, a.reng_num, a.costo_uni as cost_unit,
a.ult_cos_om as cost_unit_om, a.cos_pro_un as cos_pro_un, a.cos_pro_om as cos_pro_om, b.co_sucu, a.uni_venta, a.nro_lote, a.fec_lote, case when a.total_uni=0 then 1 else a.total_uni end as total_uni
From reng_gen a, gene_kit b
Where a.gene_num = b.gene_num and
a.co_art between @part_ini and @part_fin and
a.co_alma between @palm_ini and @palm_fin and
b.fecha <= @pfecha
Select #pptemporal1.co_art,sum(total) as stock,#pptemporal1.co_alma
from #pptemporal1 inner join art on #pptemporal1.co_art=art.co_art 
group by #pptemporal1.co_art,#pptemporal1.co_alma
--  having sum(total)>=1
order by #pptemporal1.co_art 
--select * from #pptemporal1
DROP TABLE #pptemporal1