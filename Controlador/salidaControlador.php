<?php 
require_once("../Modelo/Salida.php");

class salidaControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Salida();
    }

    public function obtenerColumnasSalida() {
        return $this->modelo->obtenerColumnasSalida();
    }

    public function obtenerColumnasDetalle() {
        return $this->modelo->obtenerColumnasDetalle();
    }

    public function listarSalidasConDetalle() {
        return $this->modelo->listarSalidasConDetalle();
    }

    public function obtenerSalidaPorId($id) {
        return $this->modelo->obtenerSalidaConDetallePorId($id);
    }

   
    public function agregar($fecha, $idTrabajador, $motivo, $detalles) {
        $detallesFiltrados = [];
        foreach ($detalles as $d) {
            if (!empty($d["idProducto"]) && !empty($d["cantidad"])) {
                $detallesFiltrados[] = $d;
            }
        }

        return $this->modelo->registrarSalidaConDetalle(
            $fecha,
            $idTrabajador,
            $motivo,
            $detallesFiltrados
        );
    }

    public function editar($idSalida, $motivo, $detalles) {
        $detallesFiltrados = [];
        foreach ($detalles as $d) {
            if (!empty($d["idProducto"]) && !empty($d["cantidad"])) {
                $detallesFiltrados[] = $d;
            }
        }

        return $this->modelo->editarSalidaConDetalle(
            $idSalida,
            $motivo,
            $detallesFiltrados
        );
    }
}
