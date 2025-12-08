<?php
require_once __DIR__ . '/../Controlador/usuarioControlador.php';

$mensaje = "";
$tipoAlerta = "";

// SI SE ENVÍA EL FORMULARIO
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ctrl = new UsuarioControlador();
    
    $idTrabajador = $_POST['idTrabajador'];
    $username     = $_POST['username'];
    $password     = $_POST['password'];
    $confirmPass  = $_POST['confirm_password'];

    // Validar que las contraseñas sean iguales
    if ($password !== $confirmPass) {
        $mensaje = "Las contraseñas no coinciden.";
        $tipoAlerta = "danger";
    } else {
        // Llamamos al controlador
        $resultado = $ctrl->registrar($idTrabajador, $username, $password);

        if ($resultado === "exito") {
            // SI TODO SALE BIEN, REDIRIGIR AL LOGIN O MOSTRAR ÉXITO
            echo "<script>
                    alert('Usuario registrado correctamente. Ahora puede iniciar sesión.');
                    window.location.href = 'login_vista.php'; 
                  </script>";
            exit;
        } elseif ($resultado === "user_existe") {
            $mensaje = "El nombre de usuario '$username' ya está ocupado.";
            $tipoAlerta = "warning";
        } elseif ($resultado === "trabajador_no_existe") {
            $mensaje = "El ID de trabajador ingresado no existe en el sistema.";
            $tipoAlerta = "danger";
        } else {
            $mensaje = "Error al registrar. Verifique los datos o si el trabajador ya tiene usuario.";
            $tipoAlerta = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #002b55; /* Color azul oscuro institucional similar a tu login */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-registro {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .header-registro {
            background-color: #dc3545; /* Rojo (Trilce UCV style) */
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
    </style>
</head>
<body>

<div class="card card-registro bg-white">
    <div class="header-registro">
        Crear Nueva Cuenta
    </div>

    <div class="card-body p-4">
        
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-<?= $tipoAlerta ?> text-center p-2 mb-3">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <form action="usuarioVista.php" method="POST">
            
            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">ID Trabajador</label>
                <input type="number" name="idTrabajador" class="form-control" placeholder="Ingrese su código de empleado" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">Usuario</label>
                <input type="text" name="username" class="form-control" placeholder="Cree su usuario" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="******" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-secondary">Confirmar Contraseña</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="******" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">Registrarse</button>
            </div>
        </form>
    </div>

    <div class="card-footer text-center bg-light py-3">
        <small class="text-muted">¿Ya tienes cuenta?</small><br>
        <a href="login_vista.php" class="text-decoration-none fw-bold text-danger">Volver al Login</a>
    </div>
</div>

</body>
</html>
