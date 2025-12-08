<head>
    <title>Login</title>
    <link rel="stylesheet" href="../Styles/login_estilos.css">
</head>
<body>

    <div class="contenedor-metalico">
        <div class="cabecera-cobre">
            <h2>Acceso a Base de Datos</h2>
        </div>

        <?php if (!empty($mensajeError)): ?>
            <div class="alerta-error">
                <?php echo $mensajeError; ?>
            </div>
        <?php endif; ?>

        <form action="login_controlador.php" method="post" class="formulario-metal">
            
            <div class="grupo-input">
                <label for="server">Servidor</label>
                <input type="text" id="server" name="txtServidor" placeholder="servidor" required>
            </div>

            <div class="grupo-input">
                <label for="user">Usuario</label>
                <input type="text" id="user" name="txtUsuario" placeholder="usuario" required>
            </div>

            <div class="grupo-input">
                <label for="pass">Clave</label>
                <input type="password" id="pass" name="txtClave" placeholder="contraseña">
            </div>

            <div class="grupo-input">
                <label for="db">Base de Datos</label>
                <input type="text" id="db" name="txtBD" placeholder="Base de Datos" required>
            </div>

            <button name="conectar" type="submit" class="boton-cobre">
                <span class="brillo"></span>
                CONECTAR
            </button>
        </form>
    </div>

</body>
</html>