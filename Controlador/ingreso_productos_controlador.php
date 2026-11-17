<?php
require_once __DIR__ . '/../Modelo/ingreso_productos_modelo.php';

class IngresoProductoControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new IngresoProducto();
    }

    public function obtenerProveedores() {
        return $this->modelo->obtenerProveedores();
    }

    public function obtenerTrabajadores() {
        return $this->modelo->obtenerTrabajadores();
    }

    public function obtenerProductos() {
        return $this->modelo->obtenerProductos();
    }

    public function guardarIngreso($idProveedor, $idTrabajador, $productosIds, $cantidades, $precios) {
        $detalles = $this->prepararDetalles($productosIds, $cantidades, $precios);
        if (empty($detalles)) return false;

        return $this->modelo->registrarIngreso($idProveedor, $idTrabajador, $detalles);
    }

    public function actualizarIngreso($idIngreso, $idProveedor, $idTrabajador, $productosIds, $cantidades, $precios) {
        $detalles = $this->prepararDetalles($productosIds, $cantidades, $precios);
        if (empty($detalles)) return false;

        return $this->modelo->actualizarIngreso($idIngreso, $idProveedor, $idTrabajador, $detalles);
    }

    public function obtenerDetallesPorIngreso($idIngreso) {
        return $this->modelo->obtenerDetallesPorIngreso($idIngreso);
    }

    public function listarIngresos() {
        return $this->modelo->listarIngresos();
    }

    private function prepararDetalles($productosIds, $cantidades, $precios) {
        $detalles = [];
        if (is_array($productosIds)) {
            for ($i = 0; $i < count($productosIds); $i++) {
                if (!empty($productosIds[$i]) && isset($cantidades[$i]) && $cantidades[$i] > 0) {
                    $detalles[] = [
                        'idProducto' => $productosIds[$i],
                        'cantidad'   => floatval($cantidades[$i]),
                        'precio'     => floatval($precios[$i] ?? 0)
                    ];
                }
            }
        }
        return $detalles;
    }
}
?>