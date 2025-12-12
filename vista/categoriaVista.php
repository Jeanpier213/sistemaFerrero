<?php
require_once("../Controlador/CategoriaControlador.php");

$control = new CategoriaControlador();

// Listar todas las categorías
$categorias = $control->listar();

// Columnas de la tabla
$columnas = $control->obtenerColumnas();

// Guardar nueva categoría
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre"])) {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $idEstado = $_POST["idEstado"];

    if ($control->guardar($nombre, $descripcion, $idEstado)) {
        header("Location: menu.php?page=categorias&success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Categorías</title>
</head>
<body>

<h1><b>Categorías</b></h1>

<!-- Input de búsqueda -->
<input type="text" id="buscar" placeholder="Buscar categoría..." autocomplete="off">
<br><br>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">Categoría guardada correctamente</p>
<?php endif; ?>

<!-- Formulario para agregar categoría -->
<div>
<form action="menu.php?page=categorias" method="POST">
    <label>Nombre:</label>
    <input type="text" name="nombre" required><br><br>

    <label>Descripción:</label>
    <textarea name="descripcion"></textarea><br><br>

    <label>ID Estado:</label>
    <input type="number" name="idEstado" required><br><br>

    <button type="submit">Agregar</button>
</form>
</div>

<!-- Tabla de categorías -->
<div>
<table id="tablaProductos" border="1">
    <tr>
        <?php foreach ($columnas as $col): ?>
            <th><?= $col ?></th>
        <?php endforeach; ?>
    </tr>

    <?php foreach ($categorias as $c): ?>
        <tr>
            <td>
                <a href="menu.php?page=editar_categorias&id=<?= $c['idCategoria'] ?>">
                    <?= $c['idCategoria'] ?>
                </a>
            </td>

            <?php foreach (array_slice($columnas, 1) as $col): ?>
                <td><?= $c[$col] ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>
</div>

<!-- Script para filtrar tabla en tiempo real -->
<script>
let input = document.getElementById("buscar");
let tabla = document.getElementById("tablaProductos");

input.onkeyup = function() {
    let texto = input.value.toLowerCase();
    let filas = tabla.getElementsByTagName("tr");

    for (let i = 1; i < filas.length; i++) { 
        let filaTexto = filas[i].innerText.toLowerCase();
        if (filaTexto.indexOf(texto) > -1) {
            filas[i].style.display = "";
        } else {
            filas[i].style.display = "none";
        }
    }
};
</script>

</body>
</html>
