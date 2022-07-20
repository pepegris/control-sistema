<?php
class Tiendas
{
    private $servername = "localhost";
    private $database = "control_sistema";
    private $username = "root";
    private $password = "";
    private $conn2 = mysqli_connect($this->servername, $this->username, $this->password, $this->database);

    private function Conexion()
    {

        try {

            $conn= new PDO("mysql:host=$this->servername;dbname=$this->database", $this->username, $this->password );
            return $conn;
            /* $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); */

        } catch (PDOException $err) {
            return "Falla de Conexion $err->getMessage()";
        }
    }

    public function getTiendas()
    {

        try {

            $conexion=$this->Conexion();
            $res = $conexion->query("SELECT sedes_nom FROM sedes WHERE    estado_sede <> 'inactivo'");
            return $res;

        } catch (PDOException $err) {
            return "Falla de Conexion $err->getMessage()";
        }
    }
}
