<?php

require_once ("../Controlador/CategoriaControlador.php");


$control = new CategoriaControlador();
$categoria = $control->editar($_GET["id"]);
?>

<h2>Editar Categoría</h2>

<form action="categoria_actualizar.php" method="POST">

    <input type="hidden" name="idCategoria" value="<?= $categoria['idCategoria'] ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= $categoria['nombre'] ?>" required><br><br>

    <label>Descripción:</label>
    <textarea name="descripcion"><?= $categoria['descripcion'] ?></textarea><br><br>

    <label>ID Estado:</label>
    <input type="number" name="idEstado" value="<?= $categoria['idEstado'] ?>" required><br><br>

    <button type="submit">Actualizar</button>

</form>
