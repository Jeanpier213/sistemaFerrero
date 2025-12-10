<?php 
require_once("../Modelo/Salida.php");

class salidaControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Salida();
    }

    public function obtenerColumnasSalida() { return $this->modelo->obtenerColumnasSalida(); }
    public function obtenerColumnasDetalle() { return $this->modelo->obtenerColumnasDetalle(); }
    public function listarSalidasConDetalle() { return $this->modelo->listarSalidasConDetalle(); }
    public function obtenerSalidaPorId($id) { return $this->modelo->obtenerSalidaConDetallePorId($id); }

    public function registrar() {

        // Si proviene de la vista de edición:
        if (isset($_POST["accion"]) && $_POST["accion"] === "editar") {

            $idSalida = $_POST["idSalida"];
            $motivo   = $_POST["motivo"];
            $detalles = $_POST["detalle"];

            $filtrados = [];
            foreach ($detalles as $d) {
                if (!empty($d["idProducto"]) && !empty($d["cantidad"])) {
                    $filtrados[] = $d;
                }
            }

            $this->modelo->editarSalidaConDetalle($idSalida, $motivo, $filtrados);

            header("Location: ../Template/menu.php?page=salidas");
            exit();
        }

        // REGISTRO NORMAL
        $fecha       = $_POST["fecha"] ?? null;
        $idTrabajador = $_POST["idTrabajador"] ?? null;
        $motivo       = $_POST["motivo"] ?? null;
        $detalles     = $_POST["detalle"] ?? [];

        if (!$fecha || !$idTrabajador) {
            die("ERROR: Fecha y trabajador son obligatorios");
        }

        $detallesFiltrados = [];
        foreach ($detalles as $d) {
            if (!empty($d["idProducto"]) && !empty($d["cantidad"])) {
                $detallesFiltrados[] = $d;
            }
        }

        $this->modelo->registrarSalidaConDetalle($fecha, $idTrabajador, $motivo, $detallesFiltrados);

        header("Location: ../Template/menu.php?page=salidas");
        exit();
    }
}

// AUTO-EJECUCIÓN
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ctrl = new salidaControlador();
    $ctrl->registrar();
}
?>
