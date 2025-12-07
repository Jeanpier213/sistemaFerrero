<?php
require_once  ("../Modelo/categoria.php");

class CategoriaControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Categoria();
    }

    // 🔹 LISTAR
    public function listar() {
        return $this->modelo->listar();
    }

    // 🔹 COLUMNAS
    public function obtenerColumnas() {
        return $this->modelo->obtenerColumnas();
    }

    // 🔹 AGREGAR
    public function guardar($nombre, $descripcion, $idEstado) {
        return $this->modelo->agregar($nombre, $descripcion, $idEstado);
    }

    // 🔹 OBTENER UNO (EDITAR)
    public function editar($id) {
        return $this->modelo->obtenerUno($id);
    }

    // 🔹 ACTUALIZAR
    public function actualizar($id, $nombre, $descripcion, $idEstado) {
        return $this->modelo->actualizar($id, $nombre, $descripcion, $idEstado);
    }

    // 🔹 BUSCAR
    public function buscar($texto) {
        return $this->modelo->buscar($texto);
    }
}
