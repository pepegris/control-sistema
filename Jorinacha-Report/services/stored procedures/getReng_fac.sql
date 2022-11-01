CREATE PROCEDURE getReng_fac

@co_art as VARCHAR(100) ,
@fecha1 as VARCHAR (100),
@fecha2 as VARCHAR (100)

AS
BEGIN
SELECT reng_fac.co_art,sum(reng_fac.total_art) as total_art 
from reng_fac INNER JOIN factura ON reng_fac.fact_num=factura.fact_num
where reng_fac.co_art=@co_art and reng_fac.fec_lote BETWEEN @fecha1  AND @fecha2 AND factura.anulada=0
GROUP BY reng_fac.co_art
END
