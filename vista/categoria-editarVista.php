<?php
require_once("../Controlador/CategoriaControlador.php");

$control = new CategoriaControlador();

// SI ENVÍA FORMULARIO
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST["idCategoria"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $idEstado = $_POST["idEstado"];

    if ($control->actualizar($id, $nombre, $descripcion, $idEstado)) {
   
        header("Location: menu.php?page=categorias&success=1");
        exit();
    }
}

// SI ES GET
$categoria = $control->editar($_GET["id"]);
?>

<h2>Editar Categoría</h2>

<form action="menu.php?page=editar_categorias" method="POST">

    <input type="hidden" name="idCategoria" value="<?= $categoria['idCategoria'] ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= $categoria['nombre'] ?>" required><br><br>

    <label>Descripción:</label>
    <textarea name="descripcion"><?= $categoria['descripcion'] ?></textarea><br><br>

    <label>ID Estado:</label>
    <input type="number" name="idEstado" value="<?= $categoria['idEstado'] ?>" required><br><br>

    <button type="submit">Actualizar</button>

</form>
