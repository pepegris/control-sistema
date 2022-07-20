<?php 
echo "incio";

class Mysql{

    private $servername = "localhost";
    private $database = "control_sistema";
    private $username = "root";
    private $password = "";
    // Create connection
    private $conna = mysqli_connect($servername, $username, $password, $database); 

    public function __construct()
    {
        try {

            $conn = mysqli_connect($this->servername, $this->username, $this->password, $this->database);
            if (!$conn) {
    
                die("Connection failed: " . mysqli_connect_error());
            }
            
        } catch (Exception $e) {
            echo "$e";
        }

        
    }




}
namespace Conn;
/* $conexion= new Mysql();
$this->conexion->conexion(); */
echo "fin";
?>