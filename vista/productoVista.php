<?php
require_once '../Controlador/productoControlador.php';
require_once '../Controlador/CategoriaControlador.php'; // Para obtener los nombres de las categorías

$controladorProd = new ProductoControlador();
$controladorCat = new CategoriaControlador();

// Obtener listas
$productos = $controladorProd->listar() ?? [];
// Asumimos que tu controlador de categorías tiene un método listar()
$categorias = method_exists($controladorCat, 'listar') ? $controladorCat->listar() : [];

<<<<<<< HEAD
// Función auxiliar para buscar el nombre de la categoría por su ID
function obtenerNombreCategoria($idCategoria, $listaCategorias) {
    foreach ($listaCategorias as $cat) {
        $id = $cat['idCategoria'] ?? $cat['id'] ?? '';
        $nombre = $cat['nombre'] ?? $cat['descripcion'] ?? 'Categoría ' . $idCategoria;
        if ($id == $idCategoria) return $nombre;
=======

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
    
    
    $idEstado = 1; 

    if ($control->guardar($nombre, $unidadMedida, $tipo, $cantidadTotal, $fechaCaducidad, $precioReferencial, $precioVenta, $umbralMinimo, $idCategoria, $idEstado)) {
        header("Location: menu.php?page=productos&success=1");  
        
        exit;
>>>>>>> 6330b431f5a3e7aa076485058d7a82635ff7fce9
    }
    return 'Categoría ' . $idCategoria;
}

// PROCESAR GUARDAR NUEVO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_guardar'])) {
    $controladorProd->guardar(
        $_POST['nombre'], $_POST['unidadMedida'], $_POST['tipo'], 
        $_POST['cantidadTotal'], $_POST['fechaCaducidad'] ?? null, 
        $_POST['precioReferencial'] ?? 0, $_POST['precioVenta'], 
        $_POST['umbralMinimo'] ?? 10, $_POST['idCategoria'], 1 // 1 = Activo por defecto
    );
    echo "<script>window.location.href='menu.php?page=productos&success=1';</script>";
    exit;
}

// PROCESAR EDITAR
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_editar'])) {
    $controladorProd->actualizar(
        $_POST['idProducto'], $_POST['nombre'], $_POST['unidadMedida'], $_POST['tipo'], 
        $_POST['cantidadTotal'], $_POST['fechaCaducidad'] ?? null, 
        $_POST['precioReferencial'] ?? 0, $_POST['precioVenta'], 
        $_POST['umbralMinimo'] ?? 10, $_POST['idCategoria'], $_POST['idEstado']
    );
    echo "<script>window.location.href='menu.php?page=productos&success=2';</script>";
    exit;
}
?>

<div class="container-fluid pt-4 px-4 pb-5">
    
    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                <i class="bi bi-box-seam-fill fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Gestión de Productos</h4>
                <span class="text-muted small">Catálogo e inventario</span>
            </div>
        </div>
    </div>

    <!-- ALERTAS -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> 
            <?= $_GET['success'] == '1' ? 'Producto registrado exitosamente.' : 'Producto actualizado exitosamente.' ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- TARJETA DE TABLA -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <!-- BUSCADOR Y BOTÓN NUEVO (En la misma línea) -->
            <div class="row mb-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="inputBuscar" class="form-control bg-light border-start-0" placeholder="Buscar producto por nombre o tipo...">
                    </div>
                </div>
                <div class="col-md-7 text-end">
                    <button class="btn btn-primary shadow-sm rounded-pill px-4 py-2 fw-medium d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalNuevo">
                        <i class="bi bi-plus-lg fs-5"></i> Nuevo Producto
                    </button>
                </div>
            </div>

            <!-- TABLA -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaProductos">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Nombre</th>
                            <th>Unidad</th>
                            <th>Categoría</th>
                            <th class="text-center">Stock</th>
                            <th>Precio Venta</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($productos)): ?>
                            <?php foreach ($productos as $p): ?>
                                <?php 
                                    $nombreCategoria = obtenerNombreCategoria($p['idCategoria'], $categorias);
                                    $esCritico = $p['cantidadTotal'] <= $p['umbralMinimo'];
                                    $estadoInactivo = $p['idEstado'] == 2; 
                                ?>
                                <tr class="<?= $estadoInactivo ? 'opacity-50' : '' ?>">
                                    <td class="ps-3 fw-medium text-muted"><?= $p['idProducto'] ?></td>
                                    <td class="fw-bold text-dark"><?= htmlspecialchars($p['nombre']) ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['unidadMedida']) ?></span></td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2 py-1">
                                            <?= htmlspecialchars($nombreCategoria) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?= $esCritico ? 'bg-danger' : 'bg-success' ?> rounded-pill px-3 py-2 fs-6">
                                            <?= $p['cantidadTotal'] ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">S/ <?= number_format($p['precioVenta'], 2) ?></td>
                                    <td class="text-center">
                                        <?php if ($estadoInactivo): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-2 py-1">Inactivo</span>
                                        <?php else: ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-1">Activo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center pe-3">
                                        <!-- BOTÓN EDITAR -->
                                        <button class="btn btn-sm btn-outline-primary rounded-circle" 
                                                data-bs-toggle="modal" data-bs-target="#modalEditar"
                                                onclick="cargarDatosEdicion(
                                                    '<?= $p['idProducto'] ?>', '<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($p['unidadMedida'], ENT_QUOTES) ?>', '<?= htmlspecialchars($p['tipo'], ENT_QUOTES) ?>', 
                                                    '<?= $p['cantidadTotal'] ?>', '<?= $p['precioVenta'] ?>', 
                                                    '<?= $p['idCategoria'] ?>', '<?= $p['idEstado'] ?>'
                                                )">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> No hay productos registrados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL: REGISTRAR NUEVO PRODUCTO -->
<!-- ============================================== -->
<div class="modal fade" id="modalNuevo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Registrar Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Unidad</label>
                            <input type="text" name="unidadMedida" class="form-control" required placeholder="Ej. Bolsa, Unidad">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tipo</label>
                            <input type="text" name="tipo" class="form-control" required placeholder="Ej. Material, Herramienta">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Stock Inicial</label>
                            <input type="number" name="cantidadTotal" class="form-control" required min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Precio de Venta (S/)</label>
                            <input type="number" step="0.01" name="precioVenta" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Categoría</label>
                            <select name="idCategoria" class="form-select" required>
                                <option value="" disabled selected>-- Selecciona una categoría --</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['idCategoria'] ?? $cat['id'] ?>">
                                        <?= htmlspecialchars($cat['nombre'] ?? $cat['descripcion'] ?? 'Cat '.$cat['idCategoria']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn_guardar" class="btn btn-primary rounded-pill px-4">Agregar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL: EDITAR PRODUCTO -->
<!-- ============================================== -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="idProducto" id="e_id">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Nombre</label>
                            <input type="text" name="nombre" id="e_nombre" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Unidad</label>
                            <input type="text" name="unidadMedida" id="e_unidad" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tipo</label>
                            <input type="text" name="tipo" id="e_tipo" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Stock</label>
                            <input type="number" name="cantidadTotal" id="e_stock" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Precio de Venta (S/)</label>
                            <input type="number" step="0.01" name="precioVenta" id="e_precio" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Categoría</label>
                            <select name="idCategoria" id="e_categoria" class="form-select" required>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['idCategoria'] ?? $cat['id'] ?>">
                                        <?= htmlspecialchars($cat['nombre'] ?? $cat['descripcion'] ?? 'Cat '.$cat['idCategoria']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Estado</label>
                            <select name="idEstado" id="e_estado" class="form-select">
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn_editar" class="btn btn-primary rounded-pill px-4">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script>
// Pasar datos de la tabla al modal de edición
function cargarDatosEdicion(id, nombre, unidad, tipo, stock, precio, idCat, idEstado) {
    document.getElementById('e_id').value = id;
    document.getElementById('e_nombre').value = nombre;
    document.getElementById('e_unidad').value = unidad;
    document.getElementById('e_tipo').value = tipo;
    document.getElementById('e_stock').value = stock;
    document.getElementById('e_precio').value = precio;
    document.getElementById('e_categoria').value = idCat;
    document.getElementById('e_estado').value = idEstado;
}

// Buscador dinámico sin recargar página
document.getElementById('inputBuscar').addEventListener('keyup', function() {
    let valor = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tablaProductos tbody tr');
    
    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(valor) ? '' : 'none';
    });
});
</script>