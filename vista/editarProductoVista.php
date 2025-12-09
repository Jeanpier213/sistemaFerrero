<?php
require_once __DIR__ . '/../Controlador/ProductoControlador.php';
$control = new ProductoControlador();

// 1. OBTENER ID
$id = $_GET["id"] ?? $_POST["idProducto"] ?? null;

// 2. GUARDAR CAMBIOS (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($control->actualizar(
        $_POST["idProducto"], $_POST["nombre"], $_POST["unidadMedida"], $_POST["tipo"],
        $_POST["cantidadTotal"], $_POST["fechaCaducidad"], $_POST["precioReferencial"],
        $_POST["precioVenta"], $_POST["umbralMinimo"], $_POST["idCategoria"], $_POST["idEstado"]
    )) {
        // Redirigir de vuelta a la lista DENTRO del menú
        echo "<script>window.location.href = 'menu.php?page=productos&success=updated';</script>";
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar.</div>";
    }
}

// 3. CARGAR DATOS
$p = ($id) ? $control->editar($id) : null;
if (!$p) { echo "<div class='alert alert-danger'>Producto no encontrado.</div>"; return; }
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h2 class="text-primary">Editar Producto</h2>
        
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="idProducto" value="<?= $p['idProducto'] ?>">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="fw-bold">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($p['nombre']) ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label>Unidad</label>
                        <input type="text" name="unidadMedida" class="form-control" value="<?= htmlspecialchars($p['unidadMedida']) ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Tipo</label>
                        <input type="text" name="tipo" class="form-control" value="<?= htmlspecialchars($p['tipo']) ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="fw-bold">Stock</label>
                        <input type="number" name="cantidadTotal" class="form-control" value="<?= $p['cantidadTotal'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Precio Ref.</label>
                        <input type="number" step="0.01" name="precioReferencial" class="form-control" value="<?= $p['precioReferencial'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold text-success">Precio Venta</label>
                        <input type="number" step="0.01" name="precioVenta" class="form-control border-success" value="<?= $p['precioVenta'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label>Umbral</label>
                        <input type="number" name="umbralMinimo" class="form-control" value="<?= $p['umbralMinimo'] ?>">
                    </div>

                    <div class="col-md-4">
                        <label>Caducidad</label>
                        <input type="date" name="fechaCaducidad" class="form-control" value="<?= $p['fechaCaducidad'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold">Categoría (ID)</label>
                        <input type="number" name="idCategoria" class="form-control" value="<?= $p['idCategoria'] ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold">Estado</label>
                        <select name="idEstado" class="form-select">
                            <option value="1" <?= $p['idEstado']==1?'selected':'' ?>>Activo</option>
                            <option value="0" <?= $p['idEstado']==0?'selected':'' ?>>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="menu.php?page=productos" class="btn btn-light me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
