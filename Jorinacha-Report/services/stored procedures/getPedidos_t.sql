
CREATE PROCEDURE getPedidos_t
@co_cli as VARCHAR(100) ,
@co_art as VARCHAR(100) 

AS
BEGIN 
select SUM(reng_ped.total_art) AS  total_art
from pedidos
JOIN reng_ped ON pedidos.fact_num=reng_ped.fact_num
where pedidos.anulada=0 AND pedidos.status = 0 AND pedidos.co_cli=@co_cli AND reng_ped.co_art=@co_art

END;

