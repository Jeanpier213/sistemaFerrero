<?php
require_once("../Controlador/productoControlador.php");

$control = new ProductoControlador();

// BUSCAR
$texto = $_POST["buscar"] ?? "";
if (isset($_POST["buscar"])) {
    $lista = $control->buscar($_POST["buscar"]);
} else {
    $lista = $control->listar();
}

// GUARDAR (Estructura idéntica a tu ejemplo)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre"])) {

    $nombre = $_POST["nombre"];
    $unidadMedida = $_POST["unidadMedida"];
    $tipo = $_POST["tipo"];
    $cantidadTotal = $_POST["cantidadTotal"];
    $fechaCaducidad = $_POST["fechaCaducidad"];
    $precioReferencial = $_POST["precioReferencial"];
    $precioVenta = $_POST["precioVenta"];
    $umbralMinimo = $_POST["umbralMinimo"];
    $idCategoria = $_POST["idCategoria"];
    
    // Por defecto al crear es 1 (Activo), o si lo mandas en hidden
    $idEstado = 1; 

    if ($control->guardar($nombre, $unidadMedida, $tipo, $cantidadTotal, $fechaCaducidad, $precioReferencial, $precioVenta, $umbralMinimo, $idCategoria, $idEstado)) {
        header("Location: menu.php?page=productos&success=1");  
        
        exit;
    }
}
?>

<h1><b>Productos</b></h1>

<input type="text" id="buscar" placeholder="Buscar producto..." autocomplete="off">
<br><br>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">Producto guardado correctamente</p>
<?php endif; ?>

<div>
    <form action="menu.php?page=productos" method="POST">
        
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br><br>

        <label>Unidad:</label>
        <input type="text" name="unidadMedida"><br><br>

        <label>Tipo:</label>
        <input type="text" name="tipo"><br><br>

        <label>Stock:</label>
        <input type="number" name="cantidadTotal"><br><br>

        <label>Precio Venta:</label>
        <input type="number" step="0.01" name="precioVenta" required><br><br>

        <label>ID Categoría:</label>
        <input type="number" name="idCategoria" required><br><br>

        <input type="hidden" name="fechaCaducidad">
        <input type="hidden" name="precioReferencial">
        <input type="hidden" name="umbralMinimo">

        <button type="submit">Agregar</button>
    </form>
</div>

<br>

<div>
    <table id="tablaProductos" border="1">
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
                        <a href="menu.php?page=editar_producto&id=<?= $fila['idProducto'] ?>">
                            <?= $fila['idProducto'] ?>
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
let tabla = document.getElementById("tablaProductos");
input.onkeyup = function() {
    let texto = input.value.toLowerCase();
    let filas = tabla.getElementsByTagName("tr");
    for (let i = 1; i < filas.length; i++) { 
        let filaTexto = filas[i].innerText.toLowerCase();
        if (filaTexto.indexOf(texto) > -1) filas[i].style.display = ""; else filas[i].style.display = "none";
    }
};
</script>