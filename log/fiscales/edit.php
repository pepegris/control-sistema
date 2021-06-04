<?php
require '../includes/conexion_control.php';
 $tienda=''; 
 $fis_fecha=''; 
 $fis_des=''; 
 

if  (isset($_GET['id'])) {
  $id = $_GET['id'];
  $query = "SELECT * FROM fiscal WHERE id=$id";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_array($result);
    $tienda=$row['tienda']; 
    $fis_fecha=$row['fis_fecha']; 
    $fis_des=$row['fis_des']; 
    
    
  }
}

if (isset($_POST['update'])) {
  $id = $_GET['id'];
    $tienda=$_POST['tienda']; 
    $fis_fecha=$_POST['fis_fecha']; 
    $fis_des=$_POST['fis_des']; 
     
  

  $query = "UPDATE fiscal set tienda = '$tienda', fis_fecha = '$fis_fecha', fis_des = '$fis_des' WHERE id=$id";
  mysqli_query($conn, $query);
  $_SESSION['message'] = 'Edit Updated Successfully';
  $_SESSION['message_type'] = 'warning *EDITANDO*';
  header('Location: buscador.php');
}

?>
<?php  
      include '../includes/menu.php'; ?>
<div class="container p-4">
  <div class="row">
    <div class="col-md-4 mx-auto">
      <div class="card card-body">
      <h3>Editando Fiscal Nº <?=$id?></h3>
      <form action="edit.php?id=<?php echo $_GET['id']; ?>" method="POST">

        <div class="form-group">
          <input type="text" name="tienda"  class="form-control" value="<?php echo $tienda; ?>" placeholder="Update Title">
        </div>

        <div class="form-group">
        <input type="date" name="fis_fecha"   class="form-control" value="<?php echo $fis_fecha;?>" placeholder="Update Title">
        </div>

        <div class="form-group">
        <textarea name="fis_des" id="" class="form-control" cols="5" rows="3"><?php echo $fis_des;?></textarea>
        </div>
        
        <button class="btn btn-primary" name="update">
          Update
</button>
      </form>
      </div>
    </div>
  </div>
</div>

