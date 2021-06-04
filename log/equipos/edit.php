<?php
require '../includes/conexion_control.php';
 $usuario=''; 
 $eq_fecha=''; 
 $eq_des=''; 
 $estado='';
 
 

if  (isset($_GET['id'])) {
  $id = $_GET['id'];
  $query1 = "SELECT * FROM equipos WHERE id=$id";
  $result = mysqli_query($conn, $query1);
  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_array($result);
    $usuario=$row['usuario']; 
    $eq_fecha=$row['eq_fecha']; 
    $eq_des=$row['eq_des']; 
    $estado=$row['estado'];
    $equipo=$row['equipo'];

    
    
  }
}

if (isset($_POST['update'])) {
  $id = $_GET['id'];
    $usuario=$_POST['usuario']; 
    $eq_fecha=$_POST['eq_fecha']; 
    $eq_des=$_POST['eq_des']; 
    $estado=$_POST['estado']; 
    $equipo=$_POST['equipo'];
  

  $query2 = "UPDATE equipos set usuario = '$usuario',estado='$estado',equipo='$equipo', eq_fecha = '$eq_fecha', eq_des = '$eq_des' WHERE id=$id";
  mysqli_query($conn, $query2);
  $_SESSION['message'] = 'Edit Updated Successfully';
  $_SESSION['message_type'] = 'warning *EDITANDO*';
  header('Location: equipo.php');
}

?>
<?php  
      include '../includes/menu.php'; ?>
<div class="container p-4">
  <div class="row">
    <div class="col-md-4 mx-auto">
      <div class="card card-body">
      <h3>Editando Equipo Nº <?=$id?></h3>
      <form action="edit.php?id=<?php echo $_GET['id']; ?>" method="POST">

        <div class="form-group">
          <input type="text" name="usuario"  class="form-control" value="<?php echo $usuario; ?>" required>
        </div>
        <div class="form-group">
          <input type="text" name="equipo"  class="form-control" value="<?php echo $equipo; ?>" required>
        </div>

        <label for="estado" >Estado Actual: </label>
    <select name="estado" id="" class="form-control">
    <option value="<?=$estado?>"><?=$estado?></option>
    <option value="Operativo">Operativo</option>
    <option value="Averiado">Averiado</option>
    <option value="Espera">Espera</option>
    </select>
          

        <div class="form-group">
        <input type="date" name="eq_fecha"   class="form-control" value="<?php echo $eq_fecha;?>" required>
        </div>

        <div class="form-group">
        <textarea name="eq_des" id="" class="form-control" cols="5" rows="3"><?php echo $eq_des;?></textarea>
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
