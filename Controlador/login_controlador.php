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

    public function iniciarSesion()
    {
        if (!isset($_POST["username"]) || !isset($_POST["password"])) {
            header("Location: ../Vistas/login.php?error=Completa los campos");
            exit();
        }

        $username = $_POST["username"];
        $password = $_POST["password"];

        $usuario = $this->modelo->buscarPorUsername($username);

        if (!$usuario) {
            header("Location: ../Vistas/login.php?error=Usuario no encontrado");
            exit();
        }

        if ($usuario["idEstado"] == 0) {
            header("Location: ../Vistas/login.php?error=Usuario desactivado");
            exit();
        }

        if (!password_verify($password, $usuario["passwordHash"])) {
            header("Location: ../Vistas/login.php?error=Contraseña incorrecta");
            exit();
        }

        // Guardar sesión
        $_SESSION["usuario"] = $usuario["trabajadorNombre"];
        $_SESSION["idUsuario"] = $usuario["idUsuario"];

        header("Location: ../Template/menu.php");
        exit();
    }
}

$ctrl = new loginControlador();
$ctrl->iniciarSesion();
?>
