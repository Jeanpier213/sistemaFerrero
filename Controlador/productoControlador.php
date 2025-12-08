<?php
require_once __DIR__ . '/../Modelo/Producto.php';

class ProductoControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Producto();
    }

    public function listar() {
        return $this->modelo->listar();
    }

    public function obtenerColumnas() {
        return $this->modelo->obtenerColumnas();
    }

    public function guardar($nombre, $unidad, $tipo, $cantidad, $fecha, $precioRef, $precioVenta, $umbral, $idCategoria, $idEstado) {
        return $this->modelo->agregar($nombre, $unidad, $tipo, $cantidad, $fecha, $precioRef, $precioVenta, $umbral, $idCategoria, $idEstado);
    }

    public function editar($id) {
        return $this->modelo->obtenerUno($id);
    }

    public function actualizar($id, $nombre, $unidad, $tipo, $cantidad, $fecha, $precioRef, $precioVenta, $umbral, $idCategoria, $idEstado) {
        return $this->modelo->actualizar($id, $nombre, $unidad, $tipo, $cantidad, $fecha, $precioRef, $precioVenta, $umbral, $idCategoria, $idEstado);
    }

    // --- NUEVO: CAMBIAR ESTADO ---
    public function cambiarEstado($id, $nuevoEstado) {
        return $this->modelo->cambiarEstado($id, $nuevoEstado);
    }

    public function buscar($texto) {
        return $this->modelo->buscar($texto);
    }
}
?>
