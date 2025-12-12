<?php
require_once("login_controlador.php");

$ctrl = new loginControlador();
$ctrl->cerrarSesion();
// header("Location: ../Vista/login_vista.php");
 // Esto destruirá la sesión y redirigirá al login
?>
