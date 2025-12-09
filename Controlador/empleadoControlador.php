
<?php
require_once  ("../Modelo/Empleado.php");   

class empleadoControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Empleado();
    }

    public function listar() {
        return $this->modelo->listar();
    }

    public function obtenerColumnas() {
        return $this->modelo->obtenerColumnas();
    }
    
    public function guardar($nombres, $apellidos, $dni, $telefono, $email, $tipo) {
        return $this->modelo->agregar($nombres, $apellidos, $dni, $telefono, $email, $tipo);
    }

    public function editar($id) {
        return $this->modelo->obtenerUno($id);
    }

    public function actualizar($idEmpleado, $idPersona, $nombres, $apellidos, $dni, $telefono, $email, $tipo, $idEstado) {
        return $this->modelo->actualizar($idEmpleado, $idPersona, $nombres, $apellidos, $dni, $telefono, $email, $tipo, $idEstado);
    }

    public function cambiarEstado($id, $estado) {
        return $this->modelo->cambiarEstado($id, $estado);
    }

    public function buscar($texto) {
        return $this->modelo->buscar($texto);
    }
}



?>