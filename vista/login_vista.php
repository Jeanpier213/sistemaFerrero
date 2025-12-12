<?php
require_once("../Controlador/login_controlador.php");


$ctrl = new loginControlador();
$resultado = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

  
    $resultado = $ctrl->iniciarSesion($username, $password);

    if ($resultado === true) {
        header("Location: ../Template/menu.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Ferretería</title>
    <link rel="stylesheet" href="../style/login.css">
</head>
<body>

<div class="login-box">

    <h2>Iniciar Sesión</h2>

 
    <?php if ($resultado !== "" && $resultado !== true): ?>
        <div class="error"><?= htmlspecialchars($resultado) ?></div>
    <?php endif; ?>

    <form action="" method="POST">

        <div class="form-group">
            <label>Usuario:</label>
            <input class="form-control" type="text" name="username"   required>
        </div>

        <div class="form-group">
            <label>Contraseña:</label>
            <input class="form-control" type="password" name="password" required>
        </div>

        <button type="submit">Ingresar</button>
    </form>

</div>

</body>
</html>
