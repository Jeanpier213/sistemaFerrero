<?php 
  require_once("../Modelo/Salida.php");

class salidaControlador {
    private $modelo;
    public function __construct()
    {
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
    public function registrarSalidaConDetalle($fecha, $idTrabajador, $motivo, $detallesArray) {
        return $this->modelo->registrarSalidaConDetalle($fecha, $idTrabajador, $motivo, $detallesArray);
    }
 public function editar($idSalida, $motivo, $detallesArray) {
        return $this->modelo->editarSalidaConDetalle($idSalida, $motivo, $detallesArray);
    }

    public function obtenerSalidaPorId($idSalida) {
        return $this->modelo->obtenerSalidaConDetallePorId($idSalida);
    }

    
    public function registrar() {

        $fecha = $_POST["fecha"];
        $idTrabajador = $_POST["idTrabajador"];
        $motivo = $_POST["motivo"];
        $detalles = $_POST["detalle"];

        // filtrar detalles vacíos
        $detallesFiltrados = [];
        foreach ($detalles as $d) {
            if (!empty($d["idProducto"]) && !empty($d["cantidad"])) {
                $detallesFiltrados[] = $d;
            }
        }

        // llamar al modelo
        $this->modelo->registrarSalidaConDetalle($fecha, $idTrabajador, $motivo, $detallesFiltrados);

        // redireccionar
        header("Location: ../Template/menu.php?page=salidas");
        exit();
    }
}

// AUTO EJECUCIÓN
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ctrl = new salidaControlador();
    $ctrl->registrar();
}   




?>