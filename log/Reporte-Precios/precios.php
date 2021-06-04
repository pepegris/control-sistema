<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
include '../includes/cabecera.php';
?>

    <h3>Actualizar precio de articulo</h3>
  <form action="precios_resultado.php" method="post" enctype="multipart/form-data">



<label for="art">Articulo</label>
<select class="art" name='art' id="art" required="required" >
        
        <option>ANALUZ</option>
        <option>BKR</option>
        <option>BRADOCK</option>
        <option>BRYTNEY</option>
        <option>CHONTEL</option>
        <option>FREE WAY</option>
        <option>JEAN GUITTON</option>
        <option>NATY-K</option>
        <option>NO PARKING</option>
        <option>PREVIA</option>
        <option>QILOO</option>
        <option>RADBOK</option>
        <option>YALIVEN</option>
        <option>YURI´S BOUTIQUE</option>
        <option>TODOS</option>
      </select>
<label for="escala">Escalas</label>
<select class="escala" name='escala' id="escala">
        <option>Todas</option>
        <option>22-27</option>
        <option>27-32</option>
        <option>28-34</option>
        <option>35-40</option>
        <option>36-41</option>
        <option>39-44</option>
        <option>40-45</option>
      </select>

<input type="submit" value="Enviar">


</form>



<h3>Actualizar precio de articulo en especifico</h3>
  <form action="precios_resultado.php" method="post" enctype="multipart/form-data">



    <label for="art">Articulo</label>
    <input type="text" name="art" id="art"autofocus="autofocus" required="required"  >
    

<input type="submit" value="Enviar">


</form>


 


</form>
    
</body>
</html>