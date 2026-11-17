<?php
require_once __DIR__ . '/../Modelo/Salida.php';

class salidaControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Salida();
    }

    public function obtenerTrabajadores() {
        return $this->modelo->obtenerTrabajadores();
    }

    public function obtenerProductos() {
        return $this->modelo->obtenerProductos();
    }

    public function listarSalidas() {
        return $this->modelo->listarSalidasAgrupadas();
    }

    public function obtenerDetallesPorSalida($idSalida) {
        return $this->modelo->obtenerDetallesPorSalida($idSalida);
    }

    public function guardarSalida($fecha, $idTrabajador, $motivo, $productosIds, $cantidades) {
        $detalles = $this->prepararDetalles($productosIds, $cantidades);
        if (empty($detalles)) return false;

        return $this->modelo->registrarSalidaConDetalle($fecha, $idTrabajador, $motivo, $detalles);
    }

    public function actualizarSalida($idSalida, $fecha, $idTrabajador, $motivo, $productosIds, $cantidades) {
        $detalles = $this->prepararDetalles($productosIds, $cantidades);
        if (empty($detalles)) return false;

        return $this->modelo->editarSalidaConDetalle($idSalida, $fecha, $idTrabajador, $motivo, $detalles);
    }

    private function prepararDetalles($productosIds, $cantidades) {
        $detalles = [];
        if (is_array($productosIds)) {
            for ($i = 0; $i < count($productosIds); $i++) {
                if (!empty($productosIds[$i]) && isset($cantidades[$i]) && $cantidades[$i] > 0) {
                    $detalles[] = [
                        'idProducto' => $productosIds[$i],
                        'cantidad'   => floatval($cantidades[$i])
                    ];
                }
            }
        }
        return $detalles;
    }
}
?>