<?php
require_once __DIR__ . '/../Modelo/usuarioModelo.php';

class UsuarioControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Usuario();
    }

    public function registrar($idTrabajador, $username, $password) {
        // 1. Verificar si el nombre de usuario ya existe
        if ($this->modelo->existeUsuario($username)) {
            return "user_existe";
        }

        // 2. Verificar si el ID de trabajador es válido (existe en la tabla empleado)
        if (!$this->modelo->existeTrabajador($idTrabajador)) {
            return "trabajador_no_existe";
        }

        // 3. Intentar registrar
        if ($this->modelo->registrar($idTrabajador, $username, $password)) {
            return "exito";
        } else {
            return "error";
        }
    }
}
?>
