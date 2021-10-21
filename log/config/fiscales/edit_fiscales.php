<?php

require_once '../includes/log.php';
require '../includes/conexion_control.php';
$tienda='';
$fis_marca1='';
$fis_marca2='';
$fis_marca3='';
$fis_marca4='';

$fis_modelo1='';
$fis_modelo2='';
$fis_modelo3='';
$fis_modelo4='';

$fis_serial1='';
$fis_serial2='';
$fis_serial3='';
$fis_serial4='';

$fis_nregistro1='';
$fis_nregistro2='';
$fis_nregistro3='';
$fis_nregistro4='';

$estado1='';
$estado2='';
$estado3='';
$estado4='';

$imagen='';
$fis_fecha='';
 

if  (isset($_GET['id'])) {
  $id = $_GET['id'];
  $query = "SELECT * FROM servidor_auditoria WHERE id=$id";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_array($result);
      $tienda=$row['tienda'] ;
      $fis_marca1=$row['fis_marca1'];
      $fis_marca2=$row['fis_marca2'];
      $fis_marca3=$row['fis_marca3'];
      $fis_marca4=$row['fis_marca4'];

      $fis_modelo1=$row['fis_modelo1'];
      $fis_modelo2=$row['fis_modelo2'];
      $fis_modelo3=$row['fis_modelo3'];
      $fis_modelo4=$row['fis_modelo4'];

      $fis_serial1=$row['fis_serial1'];
      $fis_serial2=$row['fis_serial2'];
      $fis_serial3=$row['fis_serial3'];
      $fis_serial4=$row['fis_serial4'];

      $fis_nregistro1=$row['fis_nregistro1'];
      $fis_nregistro2=$row['fis_nregistro2'];
      $fis_nregistro3=$row['fis_nregistro3'];
      $fis_nregistro4=$row['fis_nregistro4'];

      $estado1=$row['estado1'];
      $estado2=$row['estado2'];
      $estado3=$row['estado3'];
      $estado4=$row['estado4'];

      $imagen=$row['fis_img'];
      $fis_fecha=$row['fis_fecha'];
            $nombre_imagen = $_FILES['imagen']['name'];
    
    
  }
}

if (isset($_POST['update'])) {

  $id = $_GET['id'];
  $fis_marca1=$_POST['fis_marca1'];
  $fis_marca2=$_POST['fis_marca2'];
  $fis_marca3=$_POST['fis_marca3'];
  $fis_marca4=$_POST['fis_marca4'];

  $fis_modelo1=$_POST['fis_modelo1'];
  $fis_modelo2=$_POST['fis_modelo2'];
  $fis_modelo3=$_POST['fis_modelo3'];
  $fis_modelo4=$_POST['fis_modelo4'];

  $fis_serial1=$_POST['fis_serial1'];
  $fis_serial2=$_POST['fis_serial2'];
  $fis_serial3=$_POST['fis_serial3'];
  $fis_serial4=$_POST['fis_serial4'];

  $fis_nregistro1=$_POST['fis_nregistro1'];
  $fis_nregistro2=$_POST['fis_nregistro2'];
  $fis_nregistro3=$_POST['fis_nregistro3'];
  $fis_nregistro4=$_POST['fis_nregistro4'];

  $estado1=$_POST['estado1'];
  $estado2=$_POST['estado2'];
  $estado3=$_POST['estado3'];
  $estado4=$_POST['estado4'];

  $imagen=$_POST['fis_img'];
  $fis_fecha=$_POST['fis_fecha'];
    // DATOS DE IMAGEN
    $nombre_imagen = $_FILES['imagen']['name'];
    $tipo_imagen   = $_FILES['imagen']['type'];
    $tam_imagen    = $_FILES['imagen']['size'];

      if ($nombre_imagen=='') {

        //GUARDANDO SIN IMAGEN

        $query = "UPDATE servidor_auditoria SET serv_mac='$serv_mac', serv_proc='$serv_proc', serv_ram='$serv_ram', serv_disc='$serv_disc', serv_vid='$serv_vid', serv_red='$serv_red', serv_des='$serv_des', serv_fecha='$serv_fecha'  WHERE id=$id";
        mysqli_query($conn, $query);
        $_SESSION['message'] = 'Edit Updated Successfully';
        $_SESSION['message_type'] = 'warning *EDITANDO*';
        header('Location: ../fiscales.php');
            

        
      }else {

        //GUARDANDO CON IMAGEN
        //VALIDANDO SI REALMENTE ES UNA IMGEN

        if ( $tipo_imagen=="image/jpeg" or $tipo_imagen=="image/jpg" or $tipo_imagen=="image/png" or $tipo_imagen=="image/gif" )  {
          
        $nombre_imagen = $_FILES['imagen']['name'];


        //ruta del destino del servidor
        $carpeta = $_SERVER['DOCUMENT_ROOT'] . '/php/control/log/uploads/img/servidores/';



        //almacenando nombre y direccion de la imagen

        $imagen=$serv_fecha.'_'.$tienda.'_'.$nombre_imagen;

        

        //mover imagen a directorio temporal

        move_uploaded_file($_FILES['imagen']['tmp_name'],$carpeta.$imagen);

        $query = "UPDATE servidor_auditoria SET serv_mac='$serv_mac', serv_proc='$serv_proc', serv_ram='$serv_ram', serv_disc='$serv_disc', serv_vid='$serv_vid', serv_red='$serv_red', serv_des='$serv_des',serv_img='$imagen', serv_fecha='$serv_fecha'  WHERE id=$id";
        mysqli_query($conn, $query);
        $_SESSION['message'] = 'Edit Updated Successfully';
        $_SESSION['message_type'] = 'warning *EDITANDO*';
        header('Location: ../fiscales.php');
        }
          
        }
  

  
}

?>

<div class="container p-4">
  <div class="row">
    <div class="col-md-4 mx-auto">
      <div class="card card-body">
      <h3>Editando Servidor <?=$tienda?></h3>
      <form action="edit_servidor.php?id=<?php echo $_GET['id']; ?>" method="POST">

        <div class="form-group">
        <label for="serv_mac" class="form-label mt-2">Mac del equipo</label>
          <input type="text" name="serv_mac"  class="form-control" value="<?php echo $serv_mac; ?>" placeholder="Update">
        </div>

        <div class="form-group">
        <label for="serv_proc" class="form-label mt-2">Procesador</label>
          <input type="text" name="serv_proc"  class="form-control" value="<?php echo $serv_proc; ?>" placeholder="Update">
        </div>

        <div class="form-group">
        <label for="serv_ram" class="form-label mt-2">Memoria Ram</label>
          <input type="text" name="serv_ram"  class="form-control" value="<?php echo $serv_ram; ?>" placeholder="Update">
        </div>

        <div class="form-group">
        <label for="serv_disc" class="form-label mt-2">Disco Duro</label>
          <input type="text" name="serv_disc"  class="form-control" value="<?php echo $serv_disc; ?>" placeholder="Update">
        </div>

        <div class="form-group">
        <label for="serv_vid" class="form-label mt-2">Tarjeta de Video</label>
          <input type="text" name="serv_vid"  class="form-control" value="<?php echo $serv_vid; ?>" placeholder="Update">
        </div>

        <div class="form-group">
        <label for="serv_red" class="form-label mt-2">Tarjeta de Red</label>
          <input type="text" name="serv_red"  class="form-control" value="<?php echo $serv_red; ?>" placeholder="Update">
        </div>

        <div class="form-group">
        <label for="serv_fecha" class="form-label mt-2">Fecha</label>
        <input type="date" name="serv_fecha"   class="form-control" value="<?php echo $serv_fecha;?>" placeholder="Update">
        </div>

        <div class="form-group">
        <label for="imagen" class="form-label mt-2">Cargar imagen</label>
        <input type="file" class="form-control" name="imagen" size="100" id="">
        </div>
   

        <div class="form-group">
        <label for="serv_des" class="form-label mt-2">Descripcion del servidor</label>
        <textarea name="serv_des" id="" class="form-control" cols="5" rows="3"><?php echo $serv_des;?></textarea>
        </div>
        
        <button class="btn btn-primary" name="update">
          Update
</button>
      </form>
      </div>
    </div>
  </div>
</div>

