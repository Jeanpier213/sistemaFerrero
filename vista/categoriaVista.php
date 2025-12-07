<?php
require_once("../Controlador/CategoriaControlador.php");

$control = new CategoriaControlador();

// BUSCAR
if (isset($_POST["buscar"])) {
    $categorias = $control->buscar($_POST["buscar"]);
} else {
    $categorias = $control->listar();
}

// COLUMNAS
$columnas = $control->obtenerColumnas();

// GUARDAR
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre"])) {

    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $idEstado = $_POST["idEstado"];

    if ($control->guardar($nombre, $descripcion, $idEstado)) {
        header("Location: categoriaVista.php?success=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Categorías</title>
</head>
<body>

<h2>Buscar Categoría</h2>
<form method="POST">
    <input type="text" name="buscar" placeholder="Buscar...">
    <button type="submit">Buscar</button>
</form>
<br>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">Categoría guardada correctamente</p>
<?php endif; ?>

<h2>Agregar Categoría</h2>

<form action="categoriaVista.php" method="POST">

    <label>Nombre:</label>
    <input type="text" name="nombre" required><br><br>

    <label>Descripción:</label>
    <textarea name="descripcion"></textarea><br><br>

    <label>ID Estado:</label>
    <input type="number" name="idEstado" required><br><br>

    <button type="submit">Guardar Categoría</button>

</form>

<br><br>

<table border="1">
    <tr>
        <?php foreach ($columnas as $col): ?>
            <th><?= $col ?></th>
        <?php endforeach; ?>
    </tr>

    <?php foreach ($categorias as $c): ?>
        <tr>
            <td>
                <a href="categoria-editarVista.php?id=<?= $c['idCategoria'] ?>">
                    <?= $c['idCategoria'] ?>
                </a>
            </td>

            <?php foreach (array_slice($columnas, 1) as $col): ?>
                <td><?= $c[$col] ?></td>
            <?php endforeach; ?>

        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
