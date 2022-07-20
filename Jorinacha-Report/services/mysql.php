<?php 
echo "incio";
class Mysql{

    private $servername = "localhost";
    private $database = "control_sistema";
    private $username = "root";
    private $password = "";
    // Create connection
    private $conna = mysqli_connect($servername, $username, $password, $database); 

    function conexion(){

        try {

            $conn = mysqli_connect($this->servername, $this->username, $this->password, $this->database);
            if (!$conn) {
    
                die("Connection failed: " . mysqli_connect_error());
            }
            
        } catch (mysqli_sql_exception $e) {
            echo "$e";
        }



    }



}

/* $conexion= new Mysql();
$this->conexion->conexion(); */
echo "fin";
?>