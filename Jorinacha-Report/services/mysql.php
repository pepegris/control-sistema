<?php
class Tiendas
{
    private $servername = "localhost";
    private $database = "control_sistema";
    private $username = "root";
    private $password = "";
    private $conn =mysqli_connect($this->servername, $this->username, $this->password, $this->database) ;

/*     public function __construct()
    {

        try {
            $this->conn= mysqli_connect($this->servername, $this->username, $this->password, $this->database);
        } catch (\Throwable $th) {
            return "Falla de Conexion $th";
        }
        
    } */

    public function getTiendas()
    {

        try {
            $sql = "SELECT sedes_nom FROM sedes WHERE    estado_sede <> 'inactivo'   ";
            $res = mysqli_query($this->conn, $sql);
            return $res;
        } catch (\Throwable $th) {
            return "Falla de Conexion $th";
        }
    }
}
