<head>
    <title>Login</title>
    <link rel="stylesheet" href="../style/login_estilos.css">
</head>
<body>

    <div class="contenedor-metalico">
        <div class="cabecera-cobre">
            <h2>Acceso al sistema de Ferreteria Santos</h2>
        </div>

        <?php if (!empty($mensajeError)): ?>
            <div class="alerta-error">
                <?php echo $mensajeError; ?>
            </div>
        <?php endif; ?>

        <form action="../Controlador/login_controlador.php" method="post" class="formulario-metal">

            <div class="grupo-input">
                <label for="user">Usuario</label>
                <input type="text" id="user" name="txtUsuario" placeholder="usuario" required>
            </div>

            <div class="grupo-input">
                <label for="pass">Clave</label>
                <input type="password" id="pass" name="txtClave" placeholder="contraseña">
            </div>

            <button name="conectar" type="submit" class="boton-cobre">
                <span class="brillo"></span>
                CONECTAR
            </button>
        </form>
            <div class="cabecera-cobre">
                <a href="../vista/usuarioVista.php" style="color: black; text-decoration: underline;">
                    Regístrate
            </a>
      </div>
    </div>


</body>

</html>





