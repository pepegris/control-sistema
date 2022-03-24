<?php
require '../includes/conexion_control.php';

 

if  (isset($_GET['id'])) {
  $id = $_GET['id'];
  $query1 = "SELECT * FROM sedes WHERE id=$id";
  $result = mysqli_query($conn, $query1);

  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_array($result);

                
                $sedes_nom=$row['sedes_nom'];
                $rif=$row['rif'];
                $numero=$row['numero'];
                $estado_sede = $row['estado_sede'];

    
    
  }
}

if (isset($_POST['update'])) {
  $id = $_GET['id'];

    $sedes_nom=$res['sedes_nom'];
    $rif=$res['rif'];
    $numero=$res['numero'];
    $estado_sede = $res['estado_sede'];
  

  $query2 = "UPDATE sedes set sedes_nom = '$sedes_nom',estado_sede='$estado_sede',rif='$rif', numero = '$numero'  WHERE id=$id";
  mysqli_query($conn, $query2);
  $_SESSION['message'] = 'Edit Updated Successfully';
  $_SESSION['message_type'] = 'warning *EDITANDO*';
  header('Location: datos.php');
}

?>

<?php

require_once '../includes/cabecera.php';


?>

<div class="container p-4">
  <div class="row">
    <div class="col-md-4 mx-auto">
      <div class="card card-body">
      <h3>Editando Sede Nº <?=$id?></h3>
      <form action="edit.php?id=<?php echo $_GET['id']; ?>" method="POST">

        <div class="form-group">
          <input type="text" name="sedes_nom"  class="form-control" value="<?php echo $sedes_nom; ?>" required>
        </div>
        <div class="form-group">
          <input type="text" name="rif"  class="form-control" value="<?php echo $rif; ?>" required>
        </div>

        <div class="form-group">
          <input type="text" name="numero"  class="form-control" value="<?php echo $numero; ?>" required>
        </div>

        <label for="estado" >Estado Actual: </label>
    <select name="estado" id="" class="form-control">
      <option value="<?php echo $estado_sede; ?>"><?php echo $estado_sede; ?></option>
      <option value="activo">activo</option>
      <option value="inactivo">inactivo</option>
    </select>
          
        
        <button class="btn btn-primary" name="update">
          Update
</button>
      </form>
      </div>
    </div>
  </div>
</div>
<?php /* include('php/footer.php'); */ ?>
