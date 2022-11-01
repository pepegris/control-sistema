CREATE PROCEDURE getArt
@sede as VARCHAR (100),
@co_art as VARCHAR(100) ,
@co_lin as VARCHAR(100)

AS
BEGIN
IF @sede = 'Previa Shop'
BEGIN
SELECT  LTRIM(RTRIM(art.co_art)) as  co_art ,LTRIM(RTRIM(sub_lin.subl_des)) as  co_subl,LTRIM(RTRIM(cat_art.cat_des)) as  co_cat,
prec_vta3,prec_vta5,st_almac.stock_act , LTRIM(RTRIM(colores.des_col)) as co_color, LTRIM(RTRIM(lin_art.lin_des)) as co_lin,art.ubicacion
from st_almac 
JOIN art on st_almac.co_art=art.co_art
JOIN lin_art on art.co_lin = lin_art.co_lin
JOIN sub_lin on art.co_subl = sub_lin.co_subl
JOIN cat_art on art.co_cat=cat_art.co_cat
JOIN colores on art.co_color=colores.co_col
where art.co_lin=@co_lin and st_almac.co_alma='BOLE' AND art.prec_vta5 >=1
order by art.co_subl   desc
END

ELSE
BEGIN
SELECT  LTRIM(RTRIM(co_art)) as  co_art  ,LTRIM(RTRIM(co_subl)) as  co_subl  ,LTRIM(RTRIM(co_cat)) as  co_cat  ,
co_color , co_lin , stock_act , prec_vta1 , prec_vta2 , prec_vta3 ,prec_vta4 ,prec_vta5 ,ubicacion
from art  where co_lin= @co_lin AND prec_vta5 >= 1 AND co_art=@co_art
END
END


