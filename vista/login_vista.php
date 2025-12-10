<!DOCTYPE html>
<html>
<head>
    <title>Login - Ferretería</title>
    <link rel="stylesheet" href="../style/login.css">
</head>
<body>

<div class="login-box">

    <h2>Iniciar Sesión</h2>

    <?php if (isset($_GET["error"])): ?>
        <div class="error"><?= htmlspecialchars($_GET["error"]) ?></div>
    <?php endif; ?>

    <form action="../Controlador/login_controlador.php" method="POST">

        <div class="form-group">
            <label>Usuario:</label>
            <input class="form-control" type="text" name="username" autocomplete="off" required>
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
