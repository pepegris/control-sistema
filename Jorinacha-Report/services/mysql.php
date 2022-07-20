<?php 

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
            return $conn;
            
        } catch (mysqli_sql_exception $e) {
            echo "$e";
        }



    }



}


?>