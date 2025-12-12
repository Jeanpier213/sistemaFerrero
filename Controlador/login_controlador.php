<?php
session_start();
require_once("../Modelo/login.php");

class loginControlador
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new Usuario();
    }

    // Método que recibe usuario y contraseña como parámetros
    public function iniciarSesion($username, $password)
    {
        if (!$username || !$password) {
            return "Completa los campos";
        }

        $usuario = $this->modelo->buscarPorUsername($username);

        if (!$usuario) {
            return "Usuario no encontrado";
        }

        if ($usuario["idEstado"] == 0) {
            return "Usuario desactivado";
        }

        if (!password_verify($password, $usuario["passwordHash"])) {
            return "Contraseña incorrecta";
        }

        // Guardar sesión
        $_SESSION["usuario"] = $usuario["trabajadorNombre"];
        $_SESSION["idUsuario"] = $usuario["idUsuario"];

        return true;
    }

    // Método para cerrar sesión
    public function cerrarSesion()
    {
        session_unset();
        session_destroy();
        header("Location: ../Vista/login_vista.php");
        exit();
    }
}
?>
