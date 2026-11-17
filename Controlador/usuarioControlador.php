<?php
require_once __DIR__ . '/../Modelo/usuarioModelo.php';

class UsuarioControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Usuario();
    }

    public function listar() {
        return $this->modelo->listar();
    }

    public function registrar($idTrabajador, $username, $password) {
        if ($this->modelo->existeUsuario($username)) {
            return "user_existe";
        }

        if (!$this->modelo->existeTrabajador($idTrabajador)) {
            return "trabajador_no_existe";
        }

        if ($this->modelo->registrar($idTrabajador, $username, $password)) {
            return "exito";
        } else {
            return "error";
        }
    }

    public function actualizar($idUsuario, $username, $password, $idEstado) {
        return $this->modelo->actualizar($idUsuario, $username, $password, $idEstado);
    }
}
?>