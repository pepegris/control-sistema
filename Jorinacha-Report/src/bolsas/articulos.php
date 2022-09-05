<?php
require '../../includes/log.php';
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

?>
<style>
    table{
        font-size: 25px;
        border: black solid;
        
        
    }
    form {
        border:black ;
        border-radius: 10px;  
        padding-top:10px;
        background-color:  rgba(29, 27, 27, 0.205);
        box-shadow: 2px 2px 5px #999;
    }
    form button {
        margin: 5px 0 5px 0;
    }
    form .form-group{
      margin-top:5px;
    }
    table td {
      color:black;
    }
   
    
</style>
<body>
 


<div class="container mt-2">
  <div class="row">
    <!-- CARGAR ARTICULO -->
    
    <div class="row">
    <div class="col">
    <h2>Registrar Articulos</h2>
      <div class="card-body">

        <form action="articulo_post.php" method="POST" >
        <center>
          <div class="form-group">
            <input type="text" style="width: 95%;" name="art_des" placeholder="Descripción" class="form-control" autofocus required>
          </div>
          <div class="form-group">
            <input type="text"style="width: 95%;" name="co_art" placeholder="Código" class="form-control" required>
          </div>
          <div class="form-group">
            <input type="number" style="width: 95%;"  name="ref_art" placeholder="Referencia" class="form-control" required>
          </div>
          <div class="form-group">
            <input type="number" style="width: 95%;" name="stock" placeholder="Cantidad" class="form-control" required>
          </div>
      
         <button type="submit" class="btn btn-primary">
            Save
          </button></center>
        </form>
      </div>
     </div>

     
     <div class="col">
     <h2>Buscar Articulo</h2>
      <div class="card-body">

        <form action="articulo_get.php" method="POST" >
        <center>
          <div class="form-group">
            <input type="text" style="width: 95%;" name="art_des" placeholder="Descripción" class="form-control" autofocus>
          </div>
       
          
      
         <button type="submit" class="btn btn-primary">
            Search
          </button></center>
        </form>
      </div>
     </div>
    

    </div>

    <hr>
  
   
      <table class="table table-bordered table-hover" id="tblData">
        <thead >
          <tr class="table-secondary">
            
            <td>Descripción</td>
            <td>Código</td>
            <td>Precio</td>
            <td>Referencia</td>
            <td>Cantidad</td>
            <td>Accion</td>
          </tr>
        </thead>
        <tbody>


          <tr>
           
            <td>a></td>
            <td>a</td>
            <td>Bs</td>
            <td><</td>
            <td><</td>
            <td>
              <a href='edit.php?id=<?php echo $campo1?>' class='btn btn-info'>
                <i class='fas fa-marker'></i>
              </a>
              <a href='delete.php?id=<?php echo $campo1?>' class='btn btn-danger'>
                <i class='far fa-trash-alt'></i>
              </a>
            </td>
          </tr>




        </tbody>
      </table>




      
    



  </div>
</div>
    




<?php include '../../includes/footer.php'; ?>









