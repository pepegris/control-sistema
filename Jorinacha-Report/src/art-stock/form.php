<?php 
require '../../includes/log.php';
include '../../includes/header.php';
?>

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
  width: 62%;
}
form .fieldset{
  margin-left: 35px;
}
form .fieldset .form-group input{
  width: 80%;
  color:black;
}
form .fieldset .form-group span{
  color: red;
}
    
</style>

<div id="body">
<form action="tasa_post.php" method="POST" >
  
    <div class="fieldset">
    <br>
    <center><legend>Ingresar la Tasa</legend></center>
  
    <div class="form-group">
      <label for="tasa" class="form-label ">Tasa</label>
      <input   type="number" name="tasa" class="form-control" id="tasa" step="any" placeholder="Tasa" required>
    </div>
    
    <br>
    <center><button type="submit" class="btn btn-primary">Ingresar</button></center>
    <br>
    </div>
</form>
</div>




<?php include '../../includes/footer.php'; ?>