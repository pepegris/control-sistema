<?php
class Tiendas
{
    private $servername = "localhost";
    private $database = "control_sistema";
    private $username = "root";
    private $password = "";
    private $conn;
    private $conn2 = mysqli_connect($this->servername, $this->username, $this->password, $this->database);

    public function __construct()
    {

        $res=$this->conn2;

        if (!$res) {
    
            die("Connection failed: " . mysqli_connect_error());
        }


/*         $inf = "mysql:host=$this->servername;dbname=$this->database;charset=utf8"; 
        try {

            $this->conn= new PDO($inf, $this->username, $this->password );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "CONEXION";
        } catch (PDOException $err) {

            return "Falla de Conexion $err->getMessage()";

        } */
        
    }

/*     public function getTiendas()
    {

        try {

            $conexion=$this->Conexion();
            $res = $conexion->query("SELECT sedes_nom FROM sedes WHERE    estado_sede <> 'inactivo'");
            return $res;

        } catch (PDOException $err) {
            return "Falla de Conexion $err->getMessage()";
        }
    } */
}



?>
