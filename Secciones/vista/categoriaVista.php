<?php

require_once __DIR__ . "../Controlador/CategoriaControlador.php";

$control = new CategoriaControlador();

// Obtener columnas automáticamente
$columnas = $control->obtenerColumnas();

// Obtener categorías
$categorias = $control->listar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Categorías</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px; border: 1px solid black; }
    </style>
</head>
<body>

<h2>Listado de Categorías</h2>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">Registro guardado correctamente</p>
<?php endif; ?>

<h2>Agregar Categoría</h2>

<form action="categoria_guardar.php" method="POST">

    <label>Nombre:</label>
    <input type="text" name="nombre" required><br><br>

    <label>Descripción:</label>
    <textarea name="descripcion"></textarea><br><br>

    <label>ID Estado:</label>
    <input type="number" name="idEstado" required><br><br>

    <button type="submit">Guardar Categoría</button>

</form>

<br>

<table border="1">
    <tr>
        <?php foreach ($columnas as $col): ?>
            <th><?= $col ?></th>
        <?php endforeach; ?>
    </tr>

    <?php foreach ($categorias as $c): ?>
        <tr>
            <!-- Enlace para editar -->
            <td>
                <a href="categoria_editar.php?id=<?= $c['idCategoria'] ?>">
                    <?= $c['idCategoria'] ?>
                </a>
            </td>

            <!-- Imprime todas las columnas excepto la primera -->
            <?php foreach (array_slice($columnas, 1) as $col): ?>
                <td><?= $c[$col] ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
