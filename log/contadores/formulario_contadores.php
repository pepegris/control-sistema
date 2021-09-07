<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulario/formulario.css">
    <link rel="stylesheet" href="../css/formulario/bootstrap.min.css">
    <title>Formulario Contadores</title>
</head>
<body>
<?php 

include '../includes/menu.php';
include 'includes/icono.php';
    
   
if (isset($_POST)) {

  

  $sede=$_POST['sede'];
  



  
  

  ?>


<div id="body">


<form action="uploads_contadores.php" method="POST" enctype="multipart/form-data"  >
  
    <div class="fieldset">
    <br>
    <center><h3>Registrar Contador de <?=$sede?></h3></center>
    
    <div class="form-group">
              <?php  

        require '../includes/conexion_control.php';

        $sql = "SELECT * FROM sedes WHERE sedes_nom = '$sede'  ";
        $consulta = mysqli_query($conn,$sql);


          while ($res=mysqli_fetch_array($consulta)) {
            
            $sede_cont=$res['sedes_nom'];
            $imp1=$res ['impresora1'];
            $serial1=$res ['serial_imp1'];

            $imp2=$res ['impresora2'];
            $serial2=$res ['serial_imp2'];

            $imp3=$res ['impresora3'];
            $serial3=$res ['serial_imp3'];

            $imp4=$res ['impresora4'];
            $serial4=$res ['serial_imp4'];
            
            //SEDES CON UNA SOLA IMPRESORA
                    if ( $imp2 == '') {
                      
                      
                      echo "
                      <div class='form-group'>
                        <label for='sede' class='form-label mt-2'>Sede</label> 
                        <input type='text' name='sede' value='$sede_cont' readonly  class='form-control'  >
                      </div>

                      <div class='form-group'>
                        <label for='impresora1' class='form-label mt-2'>Impresora</label> 
                        <input type='text' name='impresora1' value='$imp1' readonly  style='width: 130px;'  >
                     
                        <label for='serial1' class='form-label mt-2'>Serial</label> 
                        <input type='text' name='serial1'  value='$serial1' style='width: 100px;' readonly    >
                        <label for='inicial1' class='form-label mt-2'>Contador </label>
                        <input type='number'  style='width: 200px;'  name='inicial1'  id='' required>
    
                      </div>
                      
                      
                      
                      

                      <div class='form-group'>
                        <label for='con_fecha' class='form-label mt-2'>Fecha</label> 
                        <input type='date' name='con_fecha' id='' class='form-control'  required>
                      </div>


                    
                      <div class='form-group'>
                        <label for='imagen' class='form-label mt-2'>Contador Imagen</label>
                        <input type='file' class='form-control' name='imagen' size='100' id='' required>
                      </div>

                      <div class='form-group'>
                        <label for='imagen2' class='form-label mt-2'>Contador Imagen</label>
                        <input type='file' class='form-control' name='imagen2' size='100' id='' required>
                      </div>
                      
                      <div class='form-group'>
                        <label for='con_des' class='form-label mt-2'>Comentario</label>
                        <textarea name='con_des' id='' class='form-control'cols='15' rows='3' ></textarea>
                      </div>";
                        
                      

                      
                      
               //SEDES CON MAS DE UNA IMPRESORA     
                      }elseif ($imp3 == '') {
                        
                        echo "
                        <div class='form-group'>
                          <label for='sede' class='form-label mt-2'>Sede</label> 
                          <input type='text' name='sede'  value='$sede_cont' class='form-control' readonly   >
                        </div>
  
                        <div class='form-group'>
                          <label for='impresora1' class='form-label mt-2'>Impresora</label> 
                          <input type='text' name='impresora1'  style='width: 130px;' value='$imp1' readonly   >
                          <label for='serial1' class='form-label mt-2'>Serial</label> 
                          <input type='text' name='serial1'  style='width: 100px;' value='$serial1' readonly   >
                          <label for='inicial1' class='form-label mt-2'>Contador </label>
                          <input type='number'  style='width: 200px;' name='inicial1'  id='' required>
                        </div>
                      
  
                        <div class='form-group'>
                          <label for='impresora2' class='form-label mt-2'>Impresora</label> 
                          <input type='text' name='impresora2'  style='width: 130px;' value='$imp2' readonly   >
                          <label for='serial2' class='form-label mt-2'>Serial</label> 
                          <input type='text' name='serial2'  style='width: 100px;' value='$serial2' readonly   >
                          <label for='inicial2' class='form-label mt-2'>Contador </label>
                          <input type='number'  style='width: 200px;' name='inicial2'  id='' required>
                        </div>
                       
  
            
                        
                        <div class='form-group'>
                          <label for='con_fecha' class='form-label mt-2'>Fecha</label> 
                          <input type='date' name='con_fecha' id='' class='form-control'  required>
                        </div>
                        
                        
  
                      
                        <div class='form-group'>
                          <label for='imagen' class='form-label mt-2'>Contador Imagen</label>
                          <input type='file' class='form-control' name='imagen' size='100' id='' required>
                          <label for='imagen2' class='form-label mt-2'>Contador Imagen 2</label>
                          <input type='file' class='form-control' name='imagen2' size='100' id='' required>
                          <label for='imagen3' class='form-label mt-2'>Contador Imagen 3</label>
                          <input type='file' class='form-control' name='imagen3' size='100' id=''>
                        </div>

                       
  
  
                        <div class='form-group'>
                          <label for='con_des' class='form-label mt-2'>Comentario</label>
                          <textarea name='con_des' id='' class='form-control'cols='15' rows='3' ></textarea>
                        </div>";
                      }
                       //SEDES CON MAS DE UNA IMPRESORA     
                      else {

                        echo "
                      <div class='form-group'>
                        <label for='sede' class='form-label mt-2'>Sede</label> 
                        <input type='text' name='sede'  value='$sede_cont' class='form-control' readonly   >
                      </div>

                      <div class='form-group'>
                        <label for='impresora1' class='form-label mt-2'>Impresora</label> 
                        <input type='text' name='impresora1'  style='width: 130px;' value='$imp1' readonly   >
                        <label for='serial1' class='form-label mt-2'>Serial</label> 
                        <input type='text' name='serial1'  style='width: 100px;' value='$serial1' readonly   >
                        <label for='inicial1' class='form-label mt-2'>Contador </label>
                        <input type='number'  style='width: 200px;' name='inicial1'  id='' required>
                      </div>
                    

                      <div class='form-group'>
                        <label for='impresora2' class='form-label mt-2'>Impresora</label> 
                        <input type='text' name='impresora2'  style='width: 130px;' value='$imp2' readonly   >
                        <label for='serial2' class='form-label mt-2'>Serial</label> 
                        <input type='text' name='serial2'  style='width: 100px;' value='$serial2' readonly   >
                        <label for='inicial2' class='form-label mt-2'>Contador </label>
                        <input type='number'  style='width: 200px;' name='inicial2'  id='' required>
                      </div>
                     
                      <div class='form-group'>
                        <label for='impresora3' class='form-label mt-2'>Impresora</label> 
                        <input type='text' name='impresora3'  style='width: 130px;' value='$imp3' readonly   >
                        <label for='serial3' class='form-label mt-2'>Serial</label> 
                        <input type='text' name='serial3'  style='width: 100px;' value='$serial3' readonly   >
                        <label for='inicial3' class='form-label mt-2'>Contador </label>
                        <input type='number'  style='width: 200px;' name='inicial3'  id='' required>
                      </div>
                    

          
                      
                      <div class='form-group'>
                        <label for='con_fecha' class='form-label mt-2'>Fecha</label> 
                        <input type='date' name='con_fecha' id='' class='form-control'  required>
                      </div>
                      
                      

                    
                      <div class='form-group'>
                      <label for='imagen' class='form-label mt-2'>Contador Imagen</label>
                      <input type='file' class='form-control' name='imagen' size='100' id='' required>
                      <label for='imagen2' class='form-label mt-2'>Contador Imagen 2</label>
                      <input type='file' class='form-control' name='imagen2' size='100' id='' required>
                      <label for='imagen3' class='form-label mt-2'>Contador Imagen 3</label>
                      <input type='file' class='form-control' name='imagen3' size='100' id='' required>
                    </div>


                      <div class='form-group'>
                        <label for='con_des' class='form-label mt-2'>Comentario</label>
                        <textarea name='con_des' id='' class='form-control'cols='15' rows='3' ></textarea>
                      </div>";


              }}?>

    </div>

    

    


    
   
   
    
   
 
    <br>
   
    
    </div>
    <center><button type="submit" class="btn btn-success">Save</button></center>
    <br>
    
    
</form>

</div>


    
  
<?php } ?>


    
</body>
</html>