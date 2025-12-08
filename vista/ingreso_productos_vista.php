<head>
    <title>Ingreso de Productos</title>
    <link rel="stylesheet" href="../style/ingreso_productos_estilos.css">
    <script>
            function editarProductoSeleccionado() {
                var selects = document.getElementsByName("prods_id[]");
                
                if (selects.length > 0) {
                    var idProducto = selects[0].value;
                    
                    if (idProducto) {
                        window.location.href = '../Vista/productoVista.php?id=' + idProducto;
                    } else {
                        alert("Por favor, seleccione un producto válido primero.");
                    }
                }
            }
    </script>
</head>
<body>
    <div class="main-container">
        <div class="header-bar">
            <h1>Ingreso de Productos</h1>
            <form method="POST" style="display:inline;">
                <button name="logout" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>

        <?php if($mensaje): ?>
            <div class="alert <?php echo $tipoMensaje; ?>"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST" action="ingreso_productos_controlador.php">
            <div class="panel-datos">
                <div class="campo">
                    <label>Proveedor:</label>
                    <select name="proveedor" required>
                        <?php foreach($proveedores as $p): ?>
                            <option value="<?php echo $p['idProveedor']; ?>"><?php echo $p['razonSocial']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="campo">
                    <label>Trabajador:</label>
                    <select name="trabajador" required>
                        <?php foreach($trabajadores as $t): ?>
                            <option value="<?php echo $t['idEmpleado']; ?>"><?php echo $t['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <table class="tabla-productos">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Compra</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="tabla-body">
                    <tr>
                        <td>
                            <select name="prods_id[]" required>
                                <?php foreach($listaProductos as $prod): ?>
                                    <option value="<?php echo $prod['idProducto']; ?>">
                                        <?php echo $prod['nombre']." (Ref: S/".$prod['precioReferencial'].")"; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" name="prods_cant[]" value="1" min="1" required></td>
                        <td><input type="number" name="prods_precio[]" step="0.01" required></td>
                        <td><button type="button" class="btn-del" onclick="eliminarFila(this)">X</button></td>
                    </tr>
                </tbody>
            </table>

            <div class="acciones">
                <button type="button" class="btn-add" onclick="editarProductoSeleccionado()" style="btn-add">
                    Editar Productos
                </button>
                
                <button type="submit" name="guardar" class="btn-save">Registrar Ingreso</button>
            </div>
        </form>
    </div>
</body>

</html>


