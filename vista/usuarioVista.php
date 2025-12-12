<?php
require_once("../Controlador/UsuarioControlador.php");

$control = new UsuarioControlador();
$mensaje = "";

// GUARDAR
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $idTrabajador = $_POST["idTrabajador"];
    $username     = $_POST["username"];
    $password     = $_POST["password"];
    $confirm      = $_POST["confirm_password"];

    if ($password === $confirm) {
        $resultado = $control->registrar($idTrabajador, $username, $password);

        if ($resultado === "exito") {
            // REDIRECCIÓN A TU LOGIN
            echo "<script>
                    alert('Usuario creado con éxito. Ahora inicia sesión.');
                    window.location.href='menu.php?page=usuarios'; 
                  </script>";
            exit;
        } elseif ($resultado === "user_existe") {
            $mensaje = "El usuario ya existe.";
        } elseif ($resultado === "trabajador_no_existe") {
            $mensaje = "El ID de trabajador no es válido.";
        } else {
            $mensaje = "Error al registrar.";
        }
    } else {
        $mensaje = "Las contraseñas no coinciden.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/estilo_usuario.css">
</head>
<body class="body-registro">

    <div class="contenedor-registro">
        
        <h1><b>Crear Cuenta</b></h1>
        <p class="subtitulo">Sistema de Ferretería</p>

        <?php if ($mensaje != ""): ?>
            <div class="alert alert-danger p-1 text-center" style="font-size: 0.9rem;">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">

            <label>ID Trabajador:</label>
            <input type="number" name="idTrabajador" placeholder="Código empleado" required>
            
            <label>Usuario:</label>
            <input type="text" name="username" placeholder="Nombre de usuario" required>

            <label>Contraseña:</label>
            <input type="password" name="password" placeholder="******" required>

            <label>Confirmar Contraseña:</label>
            <input type="password" name="confirm_password" placeholder="******" required>

            <br><br>

            <button type="submit" class="btn-guardar">Registrarse</button>
            
         

        </form>

    </div>

</body>
</html>