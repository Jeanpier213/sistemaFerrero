<?php
require_once __DIR__ . '/../Controlador/ProductoControlador.php';
$control = new ProductoControlador();

$id = $_GET["id"] ?? $_POST["idProducto"] ?? null;

// ACTUALIZAR
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($control->actualizar($_POST["idProducto"], $_POST["nombre"], $_POST["unidadMedida"], $_POST["tipo"], $_POST["cantidadTotal"], $_POST["fechaCaducidad"], $_POST["precioReferencial"], $_POST["precioVenta"], $_POST["umbralMinimo"], $_POST["idCategoria"], $_POST["idEstado"])) {
        echo "<script>window.location.href = 'menu.php?page=productos&success=edit';</script>"; exit;
    }
}

// CARGAR DATOS
$p = ($id) ? $control->editar($id) : null;
if (!$p) { echo "Producto no encontrado."; return; }
?>

<h2>Editar Producto</h2>
<a href="menu.php?page=productos" class="btn btn-secondary mb-3">Volver</a>

<div class="form-container" style="max-width: 600px;">
    <form method="POST">
        <input type="hidden" name="idProducto" value="<?= $p['idProducto'] ?>">
        
        <div class="form-group mb-2"><label>Nombre</label><input type="text" name="nombre" class="form-control" value="<?= $p['nombre'] ?>" required></div>
        
        <div class="form-inline mb-2">
            <div class="form-group"><label>Unidad</label><input type="text" name="unidadMedida" class="form-control" value="<?= $p['unidadMedida'] ?>"></div>
            <div class="form-group"><label>Tipo</label><input type="text" name="tipo" class="form-control" value="<?= $p['tipo'] ?>"></div>
            <div class="form-group"><label>Stock</label><input type="number" name="cantidadTotal" class="form-control" value="<?= $p['cantidadTotal'] ?>"></div>
        </div>

        <div class="form-inline mb-2">
            <div class="form-group"><label>Precio Venta</label><input type="number" step="0.01" name="precioVenta" class="form-control" value="<?= $p['precioVenta'] ?>" required></div>
            <div class="form-group"><label>ID Categoría</label><input type="number" name="idCategoria" class="form-control" value="<?= $p['idCategoria'] ?>" required></div>
            <div class="form-group"><label>Estado (1=Act, 0=Inac)</label><input type="number" name="idEstado" class="form-control" value="<?= $p['idEstado'] ?>" required></div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
    </form>
</div>