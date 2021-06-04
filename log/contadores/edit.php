<?php
require '../includes/conexion_control.php';
 $tienda=''; 
 $con_fecha=''; 
 $con_des=''; 
 

if  (isset($_GET['id'])) {
  $id = $_GET['id'];
  $query = "SELECT * FROM contador WHERE id=$id";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_array($result);
    $tienda=$row['tienda']; 
    $con_fecha=$row['con_fecha']; 
    $con_des=$row['con_des']; 
    
    
  }
}

if (isset($_POST['update'])) {
  $id = $_GET['id'];
    $tienda=$_POST['tienda']; 
    $con_fecha=$_POST['con_fecha']; 
    $con_des=$_POST['con_des']; 
     
  

  $query = "UPDATE contador set tienda = '$tienda', con_fecha = '$con_fecha', con_des = '$con_des' WHERE id=$id";
  mysqli_query($conn, $query);
  $_SESSION['message'] = 'Edit Updated Successfully';
  $_SESSION['message_type'] = 'warning *EDITANDO*';
  header('Location: articulos.php');
}

?>
<?php  
      include '../includes/menu.php'; ?>
<div class="container p-4">
  <div class="row">
    <div class="col-md-4 mx-auto">
      <div class="card card-body">
      <h3>Editando Contador Nº <?=$id?></h3>
      <form action="edit.php?id=<?php echo $_GET['id']; ?>" method="POST">

        <div class="form-group">
          <input type="text" name="tienda"  class="form-control" value="<?php echo $tienda; ?>" placeholder="Update Title">
        </div>

        <div class="form-group">
        <input type="date" name="con_fecha"   class="form-control" value="<?php echo $con_fecha;?>" placeholder="Update Title">
        </div>

        <div class="form-group">
        <textarea name="con_des" id="" class="form-control" cols="5" rows="3"><?php echo $con_des;?></textarea>
        </div>
        
        <button class="btn btn-primary" name="update">
          Update
</button>
      </form>
      </div>
    </div>
  </div>
</div>
<?php /* include('php/footer.php'); */ ?>
