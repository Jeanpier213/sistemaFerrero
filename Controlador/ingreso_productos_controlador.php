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
        $detalles = [];
        
        for ($i = 0; $i < count($productosIds); $i++) {
            if ($cantidades[$i] > 0 && !empty($productosIds[$i])) {
                $detalles[] = [
                    'idProducto' => $productosIds[$i],
                    'cantidad'   => $cantidades[$i],
                    'precio'     => $precios[$i]
                ];
            }
        }

        if (empty($detalles)) {
            return false;
        }

        return $this->modelo->registrarIngreso($idProveedor, $idTrabajador, $detalles);
    }

    public function listarIngresos() {
        return $this->modelo->listarIngresos();
    }
}
?>

