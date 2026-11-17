<?php

class bd {

    private $servidor = "localhost";
    private $basedatos = "bd_ferreteria_prueba";
    private $usuario = "root";
    private $clave = "";
    private $puerto = "3306";

    public function conexion() {
        try {

            $dsn = "mysql:host=" . $this->servidor . 
                   ";dbname=" . $this->basedatos . 
                   ";port=" . $this->puerto .
                   ";charset=utf8mb4";

            $conexion = new PDO($dsn, $this->usuario, $this->clave);

        

            return $conexion;

        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}

?>
