<?php
require_once __DIR__ . '/../Controlador/ProductoControlador.php';
$control = new ProductoControlador();

// 1. CAMBIAR ESTADO
if (isset($_POST['accion']) && $_POST['accion'] === 'cambiar_estado') {
    if ($control->cambiarEstado($_POST['idProducto'], $_POST['nuevo_estado'])) {
        echo "<script>window.location.href='menu.php?page=productos&msg=estado';</script>"; exit;
    }
}

// 2. GUARDAR NUEVO
if (isset($_POST['accion']) && $_POST['accion'] === 'guardar') {
    if ($control->guardar(
        $_POST["nombre"], $_POST["unidadMedida"], $_POST["tipo"],
        $_POST["cantidadTotal"], $_POST["fechaCaducidad"], $_POST["precioReferencial"],
        $_POST["precioVenta"], $_POST["umbralMinimo"], $_POST["idCategoria"], 1
    )) {
        echo "<script>window.location.href='menu.php?page=productos&success=1';</script>"; exit;
    }
}

// 3. LISTAR
$texto = $_POST["buscar"] ?? "";
$productos = isset($_POST["buscar"]) ? $control->buscar($texto) : $control->listar();
?>

<div class="container-fluid">
    <h2 class="text-primary mb-4 border-bottom pb-2">Gestión de Productos</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">Producto guardado.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">Nuevo Producto</div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="accion" value="guardar">
                <div class="row g-2">
                    <div class="col-md-4"><input type="text" name="nombre" class="form-control" placeholder="Nombre" required></div>
                    <div class="col-md-2"><input type="text" name="unidadMedida" class="form-control" placeholder="Unidad"></div>
                    <div class="col-md-2"><input type="text" name="tipo" class="form-control" placeholder="Tipo"></div>
                    <div class="col-md-2"><input type="number" name="cantidadTotal" class="form-control" placeholder="Stock"></div>
                    <div class="col-md-2"><input type="number" step="0.01" name="precioVenta" class="form-control" placeholder="Precio Venta" required></div>
                    <div class="col-md-2"><input type="number" name="idCategoria" class="form-control" placeholder="ID Cat" required></div>
                    <div class="col-md-2"><input type="date" name="fechaCaducidad" class="form-control"></div>
                    <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Guardar</button></div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4"><input type="text" id="buscar" class="form-control" placeholder="🔍 Buscar..."></div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table id="tablaProductos" class="table table-hover table-bordered mb-0">
                <thead class="table-dark text-center">
                    <tr><th>ID</th><th>Producto</th><th>Tipo</th><th>Stock</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                <?php foreach ($productos as $p): $esActivo = ($p['idEstado'] == 1); ?>
                    <tr>
                        <td class="text-center fw-bold"><?= $p['idProducto'] ?></td>
                        <td><?= htmlspecialchars($p['nombre']) ?><br><small class="text-muted"><?= htmlspecialchars($p['unidadMedida']) ?></small></td>
                        <td><?= htmlspecialchars($p['tipo']) ?></td>
                        <td class="text-center"><?= $p['cantidadTotal'] ?></td>
                        <td class="text-end pe-3">S/ <?= number_format($p['precioVenta'], 2) ?></td>
                        <td class="text-center"><span class="badge <?= $esActivo?'bg-success':'bg-secondary' ?>"><?= $esActivo?'Activo':'Inactivo' ?></span></td>
                        <td class="text-center">
                            
                            <a href="menu.php?page=editar&id=<?= $p['idProducto'] ?>" class="btn btn-outline-primary btn-sm">✏️</a>
                            
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="accion" value="cambiar_estado">
                                <input type="hidden" name="idProducto" value="<?= $p['idProducto'] ?>">
                                <input type="hidden" name="nuevo_estado" value="<?= $esActivo ? '0' : '1' ?>">
                                <button class="btn btn-outline-<?= $esActivo?'danger':'success' ?> btn-sm"><?= $esActivo?'🚫':'✅' ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
document.getElementById("buscar").onkeyup = function() {
    let val = this.value.toLowerCase();
    let filas = document.getElementById("tablaProductos").getElementsByTagName("tr");
    for(let i=1; i<filas.length; i++){
        filas[i].style.display = filas[i].innerText.toLowerCase().includes(val) ? "" : "none";
    }
}
</script>
