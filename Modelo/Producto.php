<?php 
 require_once("../Configuraciones/bd.php");



class Producto {
   
    private $pdo;
    public function __construct()
    {
      $this->pdo = (new BD())->conexion();
    }
    public function listar() {
        // Código para listar productos 
        $sql= $this->pdo->query("SELECT * FROM producto");
        return $sql->fetchAll(PDO::FETCH_ASSOC);

}

public function obtenerColumnas() {
    $sql = $this->pdo->query("SHOW COLUMNS FROM producto");
    return $sql->fetchAll(PDO::FETCH_COLUMN);   
}


}


?>