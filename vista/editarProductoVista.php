<?php
require_once("../Controlador/ProductoControlador.php");

$control = new ProductoControlador();


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST["idProducto"];
    
  
    $nombre = $_POST["nombre"];
    $unidad = $_POST["unidadMedida"];
    $tipo = $_POST["tipo"];
    $cantidad = $_POST["cantidadTotal"];
    $fecha = $_POST["fechaCaducidad"];
    $pRef = $_POST["precioReferencial"];
    $pVenta = $_POST["precioVenta"];
    $umbral = $_POST["umbralMinimo"];
    $idCat = $_POST["idCategoria"];
    $idEst = $_POST["idEstado"];

    if ($control->actualizar($id, $nombre, $unidad, $tipo, $cantidad, $fecha, $pRef, $pVenta, $umbral, $idCat, $idEst)) {
        header("Location: menu.php?page=productos&success=1");
        exit;

    }
}


$p = $control->editar($_GET["id"]);
?>

<h2>Editar Producto</h2>

<form action="menu.php?page=editar_producto" method="POST">

    <input type="hidden" name="idProducto" value="<?= $p['idProducto'] ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= $p['nombre'] ?>" required><br><br>

    <label>Unidad:</label>
    <input type="text" name="unidadMedida" value="<?= $p['unidadMedida'] ?>"><br><br>

    <label>Tipo:</label>
    <input type="text" name="tipo" value="<?= $p['tipo'] ?>"><br><br>

    <label>Stock:</label>
    <input type="number" name="cantidadTotal" value="<?= $p['cantidadTotal'] ?>"><br><br>

    <label>Precio Venta:</label>
    <input type="number" step="0.01" name="precioVenta" value="<?= $p['precioVenta'] ?>" required><br><br>

    <label>ID Categoría:</label>
    <input type="number" name="idCategoria" value="<?= $p['idCategoria'] ?>" required><br><br>

    <label>Estado:</label>
    <select name="idEstado">
        <option value="1" <?= $p['idEstado'] == 1 ? 'selected' : '' ?>>Activo</option>
        <option value="0" <?= $p['idEstado'] == 0 ? 'selected' : '' ?>>Inactivo</option>
    </select>
    <br><br>

    <input type="hidden" name="fechaCaducidad" value="<?= $p['fechaCaducidad'] ?>">
    <input type="hidden" name="precioReferencial" value="<?= $p['precioReferencial'] ?>">
    <input type="hidden" name="umbralMinimo" value="<?= $p['umbralMinimo'] ?>">

    <button type="submit">Actualizar</button>

</form>