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

        $hashGuardado = $usuario["passwordHash"];
        $accesoConcedido = false;

        // 1. Si la contraseña en la BD empieza con "$2y$", está encriptada
        if (strpos($hashGuardado, '$2y$') === 0) {
            if (password_verify($password, $hashGuardado)) {
                $accesoConcedido = true;
            }
        } 
        // 2. Si no, asumimos que es texto plano (como "admin123")
        else {
            if ($password === $hashGuardado) {
                $accesoConcedido = true;
            }
        }

        // 3. Validar si falló el acceso
        if (!$accesoConcedido) {
            return "Contraseña incorrecta";
        }

        // 4. Crear las sesiones correctamente (usamos username en lugar de trabajadorNombre)
        $_SESSION["usuario"] = $usuario["username"]; 
        $_SESSION["idUsuario"] = $usuario["idUsuario"];
        $_SESSION["idTrabajador"] = $usuario["idTrabajador"]; // Opcional, pero útil

        return true;
    }

    public function cerrarSesion()
    {
        session_unset();
        session_destroy();
        header("Location: ../Vista/login_vista.php");
        exit();
    }
}
?>