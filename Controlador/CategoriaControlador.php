<?php
require_once("../Modelo/Categoria.php");

class CategoriaControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Categoria();
    }

    public function listar() {
        return $this->modelo->listar();
    }

    public function obtenerColumnas() {
        return $this->modelo->obtenerColumnas();
    }

    public function guardar($nombre, $descripcion, $idEstado) {
        return $this->modelo->agregar($nombre, $descripcion, $idEstado);
    }

    public function editar($id) {
        return $this->modelo->obtenerUno($id);
    }

    public function actualizar($id, $nombre, $descripcion, $idEstado) {
        return $this->modelo->actualizar($id, $nombre, $descripcion, $idEstado);
    }

    public function buscar($texto) {
        return $this->modelo->buscar($texto);
    }
}
