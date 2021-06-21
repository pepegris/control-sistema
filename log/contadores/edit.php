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
    $impresora1=$row['impresora1'];
    $impresora2=$row['impresora2'];
      $impresora3=$row['impresora3'];
      $serial_imp1=$row['serial_imp1'];
      $serial_imp2=$row['serial_imp2'];
      $serial_imp3=$row['serial_imp3'];
      $inicial1=$row['inicial1'];
      $inicial2=$row['inicial2'];
      $inicial3=$row['inicial3'];
      $con_fecha=$row['con_fecha']; 
      $con_des=$row['con_des'];

   
    
    
  }
}

if (isset($_POST['update'])) {
  $id = $_GET['id'];
    $tienda=$_POST['tienda']; 
    $con_fecha=$_POST['con_fecha']; 
    $con_des=$_POST['con_des']; 

      $impresora1=$_POST['impresora1'];
      $impresora2=$_POST['impresora2'];
      $impresora3=$_POST['impresora3'];
      $serial_imp1=$_POST['serial_imp1'];
      $serial_imp2=$_POST['serial_imp2'];
      $serial_imp3=$_POST['serial_imp3'];

      $inicial1=$_POST['inicial1'];
      $inicial2=$_POST['inicial2'];
      $inicial3=$_POST['inicial3'];


     if ($impresora2 == null ) {

      $query = "UPDATE contador set tienda = '$tienda', inicial1=$inicial1, con_fecha = '$con_fecha', con_des = '$con_des' WHERE id=$id";

     } elseif ($impresora3 == null) {
      

        $query = "UPDATE contador set tienda = '$tienda', inicial1=$inicial1, inicial2=$inicial2,  con_fecha = '$con_fecha', con_des = '$con_des' WHERE id=$id";
  
      
     }else {
      $query = "UPDATE contador set tienda = '$tienda', inicial1=$inicial1, inicial2=$inicial2, inicial3=$inicial3, con_fecha = '$con_fecha', con_des = '$con_des' WHERE id=$id";
     }
      

     
  

  
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
      <h3>Editando Contador de <?=$tienda?></h3>
      <form action="edit.php?id=<?php echo $_GET['id']; ?>" method="POST">

        <?php
        if ($impresora2 == null) {

          echo "
          <div class='form-group'>
          <input type='text' name='tienda'  class='form-control' value='$tienda' placeholder='Update Title'>
        </div>

                      <div class='form-group'>
                        <label for='impresora1' class='form-label mt-2'>Impresora</label> 
                        <input type='text' name='impresora1'  style='width: 130px;' value='$impresora1' readonly   >
                        <br>  <label for='serial_imp1' class='form-label mt-2'>Serial</label> 
                        <input type='text' name='serial_imp1'  style='width: 100px;' value='$serial_imp1' readonly   >
                        <br> <label for='inicial1' class='form-label mt-2'>Contador </label>
                        <input type='number'  style='width: 100px;' name='inicial1'value='$inicial1'  id='' required>
                      </div>
                    
        <div class='form-group'>
        <input type='date' name='con_fecha'   class='form-control' value='$con_fecha' placeholder='Update Title'>
        </div>

        <div class='form-group'>
        <textarea name='con_des' id='' class='form-control' cols='5' rows='3'>$con_des</textarea>
        </div>";

          

        }elseif ($impresora3 == null) {

          echo "
          <div class='form-group'>
          <input type='text' name='tienda'  class='form-control' value='$tienda' placeholder='Update Title'>
        </div>

                      <div class='form-group'>
                        <label for='impresora1' class='form-label mt-2'>Impresora</label> 
                         <input type='text' name='impresora1'  style='width: 130px;' value='$impresora1' readonly   >
                        <br>  <label for='serial_imp1' class='form-label mt-2'>Serial</label> 
                        <input type='text' name='serial_imp1'  style='width: 100px;' value='$serial_imp1' readonly   >
                        <br> <label for='inicial1' class='form-label mt-2'>Contador </label>
                        <input type='number'  style='width: 100px;' name='inicial1'value='$inicial1'  id='' required>
                      </div>
                    

                      <div class='form-group'>
                        <label for='impresora2' class='form-label mt-2'>Impresora</label> 
                        <input type='text' name='impresora2'  style='width: 130px;' value='$impresora2' readonly   >
                        <br><label for='serial_imp2' class='form-label mt-2'>Serial</label> 
                        <input type='text' name='serial_imp2'  style='width: 100px;' value='$serial_imp2' readonly   >
                        <br><label for='inicial2' class='form-label mt-2'>Contador </label>
                        <input type='number'  style='width: 100px;' name='inicial2' value='$inicial2'  id='' required>
                      </div>
                     
                      
        

        <div class='form-group'>
        <input type='date' name='con_fecha'   class='form-control' value='$con_fecha' placeholder='Update Title'>
        </div>

        <div class='form-group'>
        <textarea name='con_des' id='' class='form-control' cols='5' rows='3'>$con_des</textarea>
        </div>";
          
        }
        else {

          

        echo "
          <div class='form-group'>
          <input type='text' name='tienda'  class='form-control' value='$tienda' placeholder='Update Title'>
        </div>

                      <div class='form-group'>
                        <label for='impresora1' class='form-label mt-2'>Impresora</label> 
                         <input type='text' name='impresora1'  style='width: 130px;' value='$impresora1' readonly   >
                        <br>  <label for='serial_imp1' class='form-label mt-2'>Serial</label> 
                        <input type='text' name='serial_imp1'  style='width: 100px;' value='$serial_imp1' readonly   >
                        <br> <label for='inicial1' class='form-label mt-2'>Contador </label>
                        <input type='number'  style='width: 100px;' name='inicial1'value='$inicial1'  id='' required>
                      </div>
                    

                      <div class='form-group'>
                        <label for='impresora2' class='form-label mt-2'>Impresora</label> 
                        <input type='text' name='impresora2'  style='width: 130px;' value='$impresora2' readonly   >
                        <br><label for='serial_imp2' class='form-label mt-2'>Serial</label> 
                        <input type='text' name='serial_imp2'  style='width: 100px;' value='$serial_imp2' readonly   >
                        <br><label for='inicial2' class='form-label mt-2'>Contador </label>
                        <input type='number'  style='width: 100px;' name='inicial2' value='$inicial2'  id='' required>
                      </div>
                     
                      <div class='form-group'>
                        <label for='impresora3' class='form-label mt-2'>Impresora</label> 
                        <input type='text' name='impresora3'  style='width: 130px;' value='$impresora3' readonly   >
                        <br> <label for='serial_imp3' class='form-label mt-2'>Serial</label> 
                        <input type='text' name='serial_imp3'  style='width: 100px;' value='$serial_imp3' readonly   >
                        <br><label for='inicial3' class='form-label mt-2'>Contador </label>
                        <input type='number'  style='width: 100px;' name='inicial3' value='$inicial3'  id='' required>
                      </div>
        

        <div class='form-group'>
        <input type='date' name='con_fecha'   class='form-control' value='$con_fecha' placeholder='Update Title'>
        </div>

        <div class='form-group'>
        <textarea name='con_des' id='' class='form-control' cols='5' rows='3'>$con_des</textarea>
        </div>";
        }

        
        
        ?>
        
       <center>
       <button class="btn btn-primary" name="update">
          Update
</button></center>
      </form>
      </div>
    </div>
  </div>
</div>
<?php /* include('php/footer.php'); */ ?>
