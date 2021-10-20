<?php require '../includes/log.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/fondo.css">
    
    <title>Registrar</title>
</head>
<style>

#body{
    height: 100vh;
  
    
    display: flex;
    align-items: center;
    justify-content: center;
    

}
form{
  border-radius: 10px;  
  background-color:  rgba(29, 27, 27, 0.205);
  box-shadow: 2px 2px 5px #999;
  width: 42%;
}
form .fieldset{
  margin-left: 35px;
}
form .fieldset .form-group input{
  width: 60%;
}
form .fieldset .form-group span{
  color: red;
}
    
</style>
<?php


//require_once '../includes/header.php';
require_once '../includes/menu.php';

?>
<body>
 
<div id="body">
<form action="registrar_post.php" method="POST" >
  
    <div class="fieldset">
    <br>
    <center><legend>Registrar Usuario</legend></center>
  
    <div class="form-group">
      <label for="nombre" class="form-label mt-2">Nombre</label>
      <input   type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre" required>
    </div>
    
    <div class="form-group">
      <label for="telefono" class="form-label mt-2">Telofono</label>
      <input required name="telefono" type="text" class="form-control" id="nombre" placeholder="Telefono" required>
    </div>
    <div class="form-group">
      <label for="pass" class="form-label mt-2">Clave <span>{{comparando}}</span> </label>
      <input v-model="clave1" type="text" name="pass" class="form-control" placeholder="Clave" id="" required>
      <input v-model="clave2" type="text" name="pass" class="form-control" placeholder="Vuelve a Escribir la Clave" id="" required>
    </div>
    
   
 
    <br>
   
    <center><div id="boton" v-html="boton"></div></center>
    <br>
    
    </div>
</form>




<script src="../js/vue.js"></script>
<script src="../js/vue@2.6.12"></script>
<script src="../js/vue_clave.js"></script>

</body>
</html>









