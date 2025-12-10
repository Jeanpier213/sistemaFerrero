<?php
require_once("../configuraciones/bd.php");

class Usuario
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = (new bd())->conexion();
    }

    public function buscarPorUsername($username)
    {
        $sql = "SELECT *
                FROM usuario 
                WHERE username = ? LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$username]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
