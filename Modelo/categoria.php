<?php
require_once("../Configuraciones/bd.php");

class Categoria {

    private $pdo;

    public function __construct() {
        $this->pdo = (new BD())->conexion();
    }

    // LISTAR
    public function listar() {
        $sql = $this->pdo->query("SELECT * FROM categoria");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // COLUMNAS
    public function obtenerColumnas() {
        $sql = $this->pdo->query("SHOW COLUMNS FROM categoria");
        return $sql->fetchAll(PDO::FETCH_COLUMN);
    }

    // AGREGAR
    public function agregar($nombre, $descripcion, $idEstado) {
        $sql = $this->pdo->prepare("
            INSERT INTO categoria (nombre, descripcion, idEstado)
            VALUES (?, ?, ?)
        ");
        return $sql->execute([$nombre, $descripcion, $idEstado]);
    }

    // OBTENER UNO
    public function obtenerUno($id) {
        $sql = $this->pdo->prepare("SELECT * FROM categoria WHERE idCategoria = ?");
        $sql->execute([$id]);
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    // ACTUALIZAR
    public function actualizar($id, $nombre, $descripcion, $idEstado) {
        $sql = $this->pdo->prepare("
            UPDATE categoria SET nombre=?, descripcion=?, idEstado=?
            WHERE idCategoria=?
        ");
        return $sql->execute([$nombre, $descripcion, $idEstado, $id]);
    }

    // BUSCAR
    public function buscar($texto) {
        $sql = $this->pdo->prepare("
            SELECT * FROM categoria
            WHERE nombre LIKE ? OR descripcion LIKE ?
        ");
        $texto = "%$texto%";
        $sql->execute([$texto, $texto]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
