<?php
require_once("../Controlador/ProveedorControlador.php");

$control = new ProveedorControlador();


// GUARDAR
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["razonSocial"])) {

    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $dni = $_POST["dni"]; 
    $telefono = $_POST["telefono"];
    $email = $_POST["email"]; 
    
    $razonSocial = $_POST["razonSocial"];
    $ruc = $_POST["ruc"];
    $direccion = $_POST["direccion"];
    $tipoProductos = $_POST["tipoProductos"];

    if ($control->guardar($nombres, $apellidos, $dni, $telefono, $email, $razonSocial, $ruc, $direccion, $tipoProductos)) {
        header("Location: menu.php?page=proveedor&success=1");
        exit; 
    }
    
}
?>

<h1><b>Proveedores</b></h1>

<input type="text" id="buscar" placeholder="Buscar proveedor..." autocomplete="off">
<br><br>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">Proveedor guardado correctamente</p>
<?php endif; ?>

<div>
    <form action="menu.php?page=proveedor" method="POST">
        
        <h3>Datos Empresa</h3>
        <label>Razón Social:</label>
        <input type="text" name="razonSocial" required><br><br>

        <label>RUC:</label>
        <input type="text" name="ruc" required><br><br>

        <label>Dirección:</label>
        <input type="text" name="direccion"><br><br>

        <label>Rubro:</label>
        <input type="text" name="tipoProductos"><br><br>

        <h3>Datos Contacto</h3>
        <label>Nombres:</label>
        <input type="text" name="nombres" required><br><br>

        <label>Apellidos:</label>
        <input type="text" name="apellidos" required><br><br>

        <label>DNI:</label>
        <input type="text" name="dni" required><br><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono"><br><br>
        
        <label>Email:</label>
        <input type="email" name="email"><br><br>

        <button type="submit">Agregar</button>
    </form>
</div>

<br>

<div>
    <table id="tablaProv" border="1">
        <?php if (!empty($lista)): ?>
            <tr>
                <?php 
                $columnas = array_keys($lista[0]); 
                foreach ($columnas as $col): 
                    if ($col != 'idEstado') { 
                ?>
                    <th><?= $col ?></th>
                <?php 
                    } 
                endforeach; 
                ?>
                <th>Estado</th>
            </tr>

            <?php foreach ($lista as $fila): ?>
                <tr>
                    <td>
                        <a href="menu.php?page=editar_proveedor&id=<?= $fila['idProveedor'] ?>">
                            <?= $fila['idProveedor'] ?>
                        </a>
                    </td>

                    <?php 
                        foreach (array_slice($fila, 1, null, true) as $key => $valor): 
                            if ($key != 'idEstado'):
                    ?>
                        <td><?= $valor ?></td>
                    <?php 
                            endif;
                        endforeach; 
                    ?>

                    <td><?= ($fila['idEstado'] == 1) ? 'Activo' : 'Inactivo' ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>

<script>
let input = document.getElementById("buscar");
let tabla = document.getElementById("tablaProv");
input.onkeyup = function() {
    let texto = input.value.toLowerCase();
    let filas = tabla.getElementsByTagName("tr");
    for (let i = 1; i < filas.length; i++) { 
        let filaTexto = filas[i].innerText.toLowerCase();
        if (filaTexto.indexOf(texto) > -1) filas[i].style.display = ""; else filas[i].style.display = "none";
    }
};
</script>