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

        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->database", $this->username, $this->password );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $err) {
            return "Falla de Conexion $err";
        }
    }

    public function getTiendas()
    {

        try {

            $sql = "SELECT sedes_nom FROM sedes WHERE    estado_sede <> 'inactivo'";
            $res = $this->conn->prepare($sql);
            $res->execute();
            /* $res = mysqli_query($this->conn, $sql); */
            return $res->fetchAll();
        } catch (PDOException $err) {
            return "Falla de Conexion $err";
        }
    }
}
