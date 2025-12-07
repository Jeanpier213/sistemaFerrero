<?php
session_start();

require_once __DIR__ . '/../Modelo/login_modelo.php';

$mensajeError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["conectar"])) {

    $servidor = trim($_POST["txtServidor"]);
    $usuario = trim($_POST["txtUsuario"]);
    $clave = $_POST["txtClave"];
    $bd = trim($_POST["txtBD"]);

    $modelo = new ConexionModelo();

    try {
        $conexion = $modelo->conectar($servidor, $usuario, $clave, $bd);

        $_SESSION["servidor"] = $servidor;
        $_SESSION["usuario"] = $usuario;
        $_SESSION["clave"] = $clave;
        $_SESSION["BD"] = $bd;

        header('Location: ../INDEX.php');
        exit();

    } catch (Exception $e) {
        $mensajeError = $e->getMessage();
    }
}

require_once __DIR__ . '/../Vista/login_vista.php';

?>
