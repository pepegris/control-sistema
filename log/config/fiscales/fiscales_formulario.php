<?php 

require_once '../includes/log.php';
if ( isset($_POST)) {

    $tienda = $_POST['tienda'];
    $fiscal_cantidad=$_POST['fiscal'];
    
    
    ?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='../css/formulario/formulario.css'>
    <link rel='stylesheet' href='../css/formulario/bootstrap.min.css'>

    
    <title>Cargar Fiscales</title>
</head>
<style>

</style>
<body>
<?php include '../includes/menu.php';
 
    
 ?>
 <br> <br> <br> <br>
<div id='body' >
<form action='fiscales_post.php' method='POST' enctype='multipart/form-data'  >
  
    <div class='fieldset'>
    <br>
    <center><legend>Registrar Fiscal</legend></center>

    


   <?php 

require '../includes/conexion_control.php';

$sql = "SELECT * FROM fiscal_auditoria WHERE tienda = '$tienda'  ";
$consulta = mysqli_query($conn,$sql);


  while ($row=mysqli_fetch_array($consulta)) {
    
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
    


            if ($fiscal_cantidad==1) {
                

                echo "

                <div class='form-group'>
                <label for='tienda' class='form-label mt-2'>Tienda</label>
                <input type='text' name='tienda' id='' class='form-control'  value='$tienda' readonly>
                </div>
                
                <div class='form-group'>
                <label for='serv_fecha' class='form-label mt-2'>Fecha</label>
                <input type='date' name='serv_fecha' id='' class='form-control'  required>
                </div>

                <div class='form-group'>
                <label for='imagen' class='form-label mt-2'>Cargar imagen</label>
                <input type='file' class='form-control' name='imagen' size='100' id=''>
                </div>

                <!-- FISCAL NUMERO ONE 1 -->
                <div class='form-group'>
                <label for='fis_marca1' class='form-label mt-2'>Marca Fiscal 1</label> 
                <input type='text' name='fis_marca1' value='$fis_marca1' id='' class='form-control'  required>
                </div>

                <div class='form-group'>
                <label for='fis_modelo1' class='form-label mt-2'>Modelo Fiscal 1</label> 
                <input type='text' name='fis_modelo1' value='$fis_modelo1' id='' class='form-control'  required>
                </div>

                <div class='form-group'>
                <label for='fis_serial1' class='form-label mt-2'>Serial Fiscal 1</label> 
                <input type='text' name='fis_serial1' value='$fis_serial1' id='' class='form-control'  required>
                </div>

                <div class='form-group'>
                <label for='fis_nregistro1' class='form-label mt-2'>Nº Registro Fiscal 1</label> 
                <input type='text' name='fis_nregistro1' value='$fis_nregistro1' id='' class='form-control'  required>
                </div>


                <div class='form-group'>
                <label for='estado1' class='form-label mt-2'>Estado Fiscal 1</label> 
                <select name='estado1' id=''>
                    <option value='OPERATIVA'>OPERATIVA</option>
                    <option value='AVERIADA'>AVERIADA</option>
                    <option value='INEXISTENTE'>INEXISTENTE</option>
                </select>
                </div>";
                

            }elseif ($fiscal_cantidad == 2) {
                
                               echo " <div class='form-group'>
                               <label for='tienda' class='form-label mt-2'>Tienda</label>
                               <input type='text' name='tienda' id='' class='form-control'  value='$tienda' readonly>
                               </div>

                               <div class='form-group'>
                               <label for='serv_fecha' class='form-label mt-2'>Fecha</label>
               
                               <input type='date' name='serv_fecha' id='' class='form-control'  required>
                               </div>
               
                               <div class='form-group'>
                               <label for='imagen' class='form-label mt-2'>Cargar imagen</label>
                               <input type='file' class='form-control' name='imagen' size='100' id=''>
                               </div>
               
                               <!-- FISCAL NUMERO ONE 1 -->
                               <div class='form-group'>
                               <label for='fis_marca1' class='form-label mt-2'>Marca Fiscal 1</label> 
                               <input type='text' name='fis_marca1' value='$fis_marca1' id='' class='form-control'  required>
                               </div>
               
                               <div class='form-group'>
                               <label for='fis_modelo1' class='form-label mt-2'>Modelo Fiscal 1</label> 
                               <input type='text' name='fis_modelo1' value='$fis_modelo1' id='' class='form-control'  required>
                               </div>
               
                               <div class='form-group'>
                               <label for='fis_serial1' class='form-label mt-2'>Serial Fiscal 1</label> 
                               <input type='text' name='fis_serial1' value='$fis_serial1' id='' class='form-control'  required>
                               </div>
               
                               <div class='form-group'>
                               <label for='fis_nregistro1' class='form-label mt-2'>Nº Registro Fiscal 1</label> 
                               <input type='text' name='fis_nregistro1' value='$fis_nregistro1' id='' class='form-control'  required>
                               </div>
               
               
                               <div class='form-group'>
                               <label for='estado1' class='form-label mt-2'>Estado Fiscal 1</label> 
                               <select name='estado1' id=''>
                                   <option value='OPERATIVA'>OPERATIVA</option>
                                   <option value='AVERIADA'>AVERIADA</option>
                                   <option value='INEXISTENTE'>INEXISTENTE</option>
                               </select>
                               </div>
               
                               <!-- FISCAL NUMERO TWOO 2-->
               
                               <div class='form-group'>
                               <label for='fis_marca2' class='form-label mt-2'>Marca Fiscal 2</label> 
                               <input type='text' name='fis_marca2' value='$fis_marca2' id='' class='form-control' required >
                               </div>
               
                               <div class='form-group'>
                               <label for='fis_modelo2' class='form-label mt-2'>Modelo Fiscal 2</label> 
                               <input type='text' name='fis_modelo2' value='$fis_modelo2' id='' class='form-control' required >
                               </div>
               
                               <div class='form-group'>
                               <label for='fis_serial2' class='form-label mt-2'>Serial Fiscal 2</label> 
                               <input type='text' name='fis_serial2' value='$fis_serial2' id='' class='form-control'  required>
                               </div>
               
                               <div class='form-group'>
                               <label for='fis_nregistro2' class='form-label mt-2'>Nº Registro Fiscal 2</label> 
                               <input type='text' name='fis_nregistro2' value='$fis_nregistro2' id='' class='form-control' required >
                               </div>
               
               
                               <div class='form-group'>
                               <label for='estado2' class='form-label mt-2'>Estado Fiscal 2</label> 
                               <select name='estado2' id=''>
                                   <option value='OPERATIVA'>OPERATIVA</option>
                                   <option value='AVERIADA'>AVERIADA</option>
                                   <option value='INEXISTENTE'>INEXISTENTE</option>
                               </select>
                               </div>";
                

            }elseif ($fiscal_cantidad == 3) {
                

                                    echo "<div class='form-group'>
                                    <label for='tienda' class='form-label mt-2'>Tienda</label>
                                    <input type='text' name='tienda' id='' class='form-control'  value='$tienda' readonly>
                                    </div>
                                    
                                    <div class='form-group'>
                                    <label for='serv_fecha' class='form-label mt-2'>Fecha</label>
                
                                    <input type='date' name='serv_fecha' id='' class='form-control'  required>
                                    </div>
                
                                    <div class='form-group'>
                                    <label for='imagen' class='form-label mt-2'>Cargar imagen</label>
                                    <input type='file' class='form-control' name='imagen' size='100' id=''>
                                    </div>
                
                                    <!-- FISCAL NUMERO ONE 1 -->
                                    <div class='form-group'>
                                    <label for='fis_marca1' class='form-label mt-2'>Marca Fiscal 1</label> 
                                    <input type='text' name='fis_marca1' value='$fis_marca1' id='' class='form-control'  required>
                                    </div>
                
                                    <div class='form-group'>
                                    <label for='fis_modelo1' class='form-label mt-2'>Modelo Fiscal 1</label> 
                                    <input type='text' name='fis_modelo1' value='$fis_modelo1' id='' class='form-control'  required>
                                    </div>
                
                                    <div class='form-group'>
                                    <label for='fis_serial1' class='form-label mt-2'>Serial Fiscal 1</label> 
                                    <input type='text' name='fis_serial1' value='$fis_serial1' id='' class='form-control'  required>
                                    </div>
                
                                    <div class='form-group'>
                                    <label for='fis_nregistro1' class='form-label mt-2'>Nº Registro Fiscal 1</label> 
                                    <input type='text' name='fis_nregistro1' value='$fis_nregistro1' id='' class='form-control'  required>
                                    </div>
                
                
                                    <div class='form-group'>
                                    <label for='estado1' class='form-label mt-2'>Estado Fiscal 1</label> 
                                    <select name='estado1' id=''>
                                        <option value='OPERATIVA'>OPERATIVA</option>
                                        <option value='AVERIADA'>AVERIADA</option>
                                        <option value='INEXISTENTE'>INEXISTENTE</option>
                                    </select>
                                    </div>
                
                                    <!-- FISCAL NUMERO TWOO 2-->
                
                                    <div class='form-group'>
                                    <label for='fis_marca2' class='form-label mt-2'>Marca Fiscal 2</label> 
                                    <input type='text' name='fis_marca2' value='$fis_marca2' id='' class='form-control'  >
                                    </div>
                
                                    <div class='form-group'>
                                    <label for='fis_modelo2' class='form-label mt-2'>Modelo Fiscal 2</label> 
                                    <input type='text' name='fis_modelo2' value='$fis_modelo2' id='' class='form-control'  >
                                    </div>
                
                                    <div class='form-group'>
                                    <label for='fis_serial2' class='form-label mt-2'>Serial Fiscal 2</label> 
                                    <input type='text' name='fis_serial2' value='$fis_serial2' id='' class='form-control'  >
                                    </div>
                
                                    <div class='form-group'>
                                    <label for='fis_nregistro2' class='form-label mt-2'>Nº Registro Fiscal 2</label> 
                                    <input type='text' name='fis_nregistro2' value='$fis_nregistro2' id='' class='form-control'  >
                                    </div>
                
                
                                    <div class='form-group'>
                                    <label for='estado2' class='form-label mt-2'>Estado Fiscal 2</label> 
                                    <select name='estado2' id=''>
                                        <option value='OPERATIVA'>OPERATIVA</option>
                                        <option value='AVERIADA'>AVERIADA</option>
                                        <option value='INEXISTENTE'>INEXISTENTE</option>
                                    </select>
                                    </div>
                                    <!-- FISCAL NUMERO TRHEE 3-->
                
                                    <div class='form-group'>
                                    <label for='fis_marca3' class='form-label mt-2'>Marca Fiscal 3</label> 
                                    <input type='text' name='fis_marca3' value='$fis_marca3' id='' class='form-control' required >
                                    </div>
                
                                    <div class='form-group'>
                                    <label for='fis_modelo3' class='form-label mt-2'>Modelo Fiscal 3</label> 
                                    <input type='text' name='fis_modelo3' value='$fis_modelo3' id='' class='form-control' required >
                                    </div>
                
                                    <div class='form-group'>
                                    <label for='fis_serial3' class='form-label mt-2'>Serial Fiscal 3</label> 
                                    <input type='text' name='fis_serial3' value='$fis_serial3'  id='' class='form-control' required  >
                                    </div>
                
                                    <div class='form-group'>
                                    <label for='fis_nregistro3' class='form-label mt-2'>Nº Registro Fiscal 3</label> 
                                    <input type='text' name='fis_nregistro3' value='$fis_nregistro3' id='' class='form-control' required >
                                    </div>
                
                
                                    <div class='form-group'>
                                    <label for='estado3' class='form-label mt-2'>Estado Fiscal 3</label> 
                                    <select name='estado3' id=''>
                                        <option value='OPERATIVA'>OPERATIVA</option>
                                        <option value='AVERIADA'>AVERIADA</option>
                                        <option value='INEXISTENTE'>INEXISTENTE</option>
                                    </select>
                                    </div>";
                   

            }elseif ($fiscal_cantidad == 4) {
                

                    echo "<div class='form-group'>
                    <label for='tienda' class='form-label mt-2'>Tienda</label>
                    <input type='text' name='tienda' id='' class='form-control'  value='$tienda' readonly>
                    </div>
                    
                    <div class='form-group'>
                    <label for='serv_fecha' class='form-label mt-2'>Fecha</label>

                    <input type='date' name='serv_fecha' id='' class='form-control'  required>
                    </div>

                    <div class='form-group'>
                    <label for='imagen' class='form-label mt-2'>Cargar imagen</label>
                    <input type='file' class='form-control' name='imagen' size='100' id=''>
                    </div>

                    <!-- FISCAL NUMERO ONE 1 -->
                    <div class='form-group'>
                    <label for='fis_marca1' class='form-label mt-2'>Marca Fiscal 1</label> 
                    <input type='text' name='fis_marca1' value='$fis_marca1' id='' class='form-control'  required>
                    </div>

                    <div class='form-group'>
                    <label for='fis_modelo1' class='form-label mt-2'>Modelo Fiscal 1</label> 
                    <input type='text' name='fis_modelo1' value='$fis_modelo1' id='' class='form-control'  required>
                    </div>

                    <div class='form-group'>
                    <label for='fis_serial1' class='form-label mt-2'>Serial Fiscal 1</label> 
                    <input type='text' name='fis_serial1' value='$fis_serial1' id='' class='form-control'  required>
                    </div>

                    <div class='form-group'>
                    <label for='fis_nregistro1' class='form-label mt-2'>Nº Registro Fiscal 1</label> 
                    <input type='text' name='fis_nregistro1' value='$fis_nregistro1' id='' class='form-control'  required>
                    </div>


                    <div class='form-group'>
                    <label for='estado1' class='form-label mt-2'>Estado Fiscal 1</label> 
                    <select name='estado1' id=''>
                        <option value='OPERATIVA'>OPERATIVA</option>
                        <option value='AVERIADA'>AVERIADA</option>
                        <option value='INEXISTENTE'>INEXISTENTE</option>
                    </select>
                    </div>

                    <!-- FISCAL NUMERO TWOO 2-->

                    <div class='form-group'>
                    <label for='fis_marca2' class='form-label mt-2'>Marca Fiscal 2</label> 
                    <input type='text' name='fis_marca2' value='$fis_marca2' id='' class='form-control' required >
                    </div>

                    <div class='form-group'>
                    <label for='fis_modelo2' class='form-label mt-2'>Modelo Fiscal 2</label> 
                    <input type='text' name='fis_modelo2' value='$fis_modelo2' id='' class='form-control' required >
                    </div>

                    <div class='form-group'>
                    <label for='fis_serial2' class='form-label mt-2'>Serial Fiscal 2</label> 
                    <input type='text' name='fis_serial2' value='$fis_serial2' id='' class='form-control'  required>
                    </div>

                    <div class='form-group'>
                    <label for='fis_nregistro2' class='form-label mt-2'>Nº Registro Fiscal 2</label> 
                    <input type='text' name='fis_nregistro2' value='$fis_nregistro2' id='' class='form-control' required >
                    </div>


                    <div class='form-group'>
                    <label for='estado2' class='form-label mt-2'>Estado Fiscal 2</label> 
                    <select name='estado2' id=''>
                        <option value='OPERATIVA'>OPERATIVA</option>
                        <option value='AVERIADA'>AVERIADA</option>
                        <option value='INEXISTENTE'>INEXISTENTE</option>
                    </select>
                    </div>
                    <!-- FISCAL NUMERO TRHEE 3-->

                    <div class='form-group'>
                    <label for='fis_marca3' class='form-label mt-2'>Marca Fiscal 3</label> 
                    <input type='text' name='fis_marca3' value='$fis_marca3' id='' class='form-control' required >
                    </div>

                    <div class='form-group'>
                    <label for='fis_modelo3' class='form-label mt-2'>Modelo Fiscal 3</label> 
                    <input type='text' name='fis_modelo3' value='$fis_modelo3' id='' class='form-control' required >
                    </div>

                    <div class='form-group'>
                    <label for='fis_serial3' class='form-label mt-2'>Serial Fiscal 3</label> 
                    <input type='text' name='fis_serial3' value='$fis_serial3'  id='' class='form-control' required >
                    </div>

                    <div class='form-group'>
                    <label for='fis_nregistro3' class='form-label mt-2'>Nº Registro Fiscal 3</label> 
                    <input type='text' name='fis_nregistro3' value='$fis_nregistro3' id='' class='form-control' required >
                    </div>


                    <div class='form-group'>
                    <label for='estado3' class='form-label mt-2'>Estado Fiscal 3</label> 
                    <select name='estado3' id=''>
                        <option value='OPERATIVA'>OPERATIVA</option>
                        <option value='AVERIADA'>AVERIADA</option>
                        <option value='INEXISTENTE'>INEXISTENTE</option>
                    </select>
                </div>
                    <!-- FISCAL NUMERO FOUR 4-->

                    <div class='form-group'>
                    <label for='fis_marca4' class='form-label mt-2'>Marca Fiscal 4</label> 
                    <input type='text' name='fis_marca4' value='$fis_marca4' id='' class='form-control' required >
                    </div>

                    <div class='form-group'>
                    <label for='fis_modelo4' class='form-label mt-2'>Modelo Fiscal 4</label> 
                    <input type='text' name='fis_modelo4' value='$fis_modelo4' id='' class='form-control' required >
                    </div>

                    <div class='form-group'>
                    <label for='fis_serial4' class='form-label mt-2'>Serial Fiscal 4</label> 
                    <input type='text' name='fis_serial4' value='$fis_serial4' id='' class='form-control' required  >
                    </div>

                    <div class='form-group'>
                    <label for='fis_nregistro4' class='form-label mt-2'>Nº Registro Fiscal 4</label> 
                    <input type='text' name='fis_nregistro4' value='$fis_nregistro4' id='' class='form-control' required >
                    </div>


                    <div class='form-group'>
                    <label for='estado4' class='form-label mt-2'>Estado Fiscal 4</label> 
                    <select name='estado4' id=''>
                        <option value='OPERATIVA'>OPERATIVA</option>
                        <option value='AVERIADA'>AVERIADA</option>
                        <option value='INEXISTENTE'>INEXISTENTE</option>
                    </select>
                    </div>";
            }}


   ?>


   
   
    
   
 
    <br>
   
    
    </div>
    <center><button type='submit' class='btn btn-primary'>Save</button></center>
    <br>
    
    
</form>


</div>




    
</body>
</html>
<?php }?>