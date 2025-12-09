<?php
require_once __DIR__ . '/../Modelo/Proveedor.php';

class ProveedorControlador {
    private $modelo;

    public function __construct() { $this->modelo = new Proveedor(); }

    public function listar() { return $this->modelo->listar(); }

    public function guardar($nombres, $apellidos, $dni, $telefono, $email, $razonSocial, $ruc, $direccion, $tipoProd) {
        return $this->modelo->agregar($nombres, $apellidos, $dni, $telefono, $email, $razonSocial, $ruc, $direccion, $tipoProd);
    }

    public function editar($id) { return $this->modelo->obtenerUno($id); }

    public function actualizar($idProv, $idPers, $nom, $ape, $dni, $tel, $email, $razon, $ruc, $dir, $tipo, $est) {
        return $this->modelo->actualizar($idProv, $idPers, $nom, $ape, $dni, $tel, $email, $razon, $ruc, $dir, $tipo, $est);
    }

    public function cambiarEstado($id, $estado) { return $this->modelo->cambiarEstado($id, $estado); }
    
    public function buscar($texto) { return $this->modelo->buscar($texto); }
}
?>