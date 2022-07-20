<?php 
require '../../includes/log.php';
include '../../includes/header.php';
?>



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