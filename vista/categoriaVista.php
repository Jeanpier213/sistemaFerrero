<?php
require_once '../Controlador/CategoriaControlador.php';
$controladorCat = new CategoriaControlador();

// PROCESAR GUARDAR NUEVA CATEGORÍA
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_guardar'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $idEstado = $_POST['idEstado'] ?? 1;

    if ($controladorCat->guardar($nombre, $descripcion, $idEstado)) {
        echo "<script>window.location.href='menu.php?page=categorias&success=1';</script>";
        exit;
    }
}

// PROCESAR EDITAR CATEGORÍA
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_editar'])) {
    $idCategoria = $_POST['idCategoria'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $idEstado = $_POST['idEstado'];

    if (method_exists($controladorCat, 'actualizar')) {
        $controladorCat->actualizar($idCategoria, $nombre, $descripcion, $idEstado);
    }
    echo "<script>window.location.href='menu.php?page=categorias&success=2';</script>";
    exit;
}

$categorias = $controladorCat->listar() ?? [];
?>

<div class="container-fluid pt-4 px-4 pb-5">
    
    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                <i class="bi bi-tags-fill fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Gestión de Categorías</h4>
                <span class="text-muted small">Clasificación y catálogo de productos</span>
            </div>
        </div>
    </div>

    <!-- ALERTAS -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> 
            <?= $_GET['success'] == '1' ? 'Categoría registrada exitosamente.' : 'Categoría actualizada exitosamente.' ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- TARJETA DE TABLA -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            
            <!-- BUSCADOR Y BOTÓN NUEVO -->
            <div class="row mb-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="inputBuscar" class="form-control bg-light border-start-0" placeholder="Buscar categoría por nombre...">
                    </div>
                </div>
                <div class="col-md-7 text-end">
                    <button class="btn btn-primary shadow-sm rounded-pill px-4 py-2 fw-medium d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalNuevo">
                        <i class="bi bi-plus-lg fs-5"></i> Nueva Categoría
                    </button>
                </div>
            </div>

            <!-- TABLA -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaCategorias">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $c): ?>
                                <?php 
                                    $valEstado = $c['idEstado'] ?? $c['estado'] ?? 1;
                                    $esInactivo = ($valEstado == 0 || $valEstado == 2); 
                                ?>
                                <tr class="<?= $esInactivo ? 'opacity-50' : '' ?>">
                                    <td class="ps-3 fw-medium text-muted"><?= htmlspecialchars($c['idCategoria'] ?? '') ?></td>
                                    <td class="fw-bold text-dark"><?= htmlspecialchars($c['nombre'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($c['descripcion'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <?php if ($esInactivo): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-2 py-1">Inactivo</span>
                                        <?php else: ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-1">Activo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center pe-3">
                                        <!-- BOTÓN EDITAR -->
                                        <button class="btn btn-sm btn-outline-primary rounded-circle" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditar"
                                                onclick="cargarDatosEdicion(
                                                    '<?= $c['idCategoria'] ?? '' ?>', 
                                                    '<?= htmlspecialchars($c['nombre'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($c['descripcion'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= $valEstado ?>'
                                                )">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> No hay categorías registradas.
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
<!-- MODAL: REGISTRAR NUEVA CATEGORÍA -->
<!-- ============================================== -->
<div class="modal fade" id="modalNuevo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-folder-plus me-2"></i>Registrar Categoría</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Nombre</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej. Herramientas, Construcción..." required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3" placeholder="Breve descripción de la categoría..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Estado</label>
                            <select name="idEstado" class="form-select">
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn_guardar" class="btn btn-primary rounded-pill px-4">Agregar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL: EDITAR CATEGORÍA -->
<!-- ============================================== -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Categoría</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="idCategoria" id="e_idCategoria">
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Nombre</label>
                            <input type="text" name="nombre" id="e_nombre" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Descripción</label>
                            <textarea name="descripcion" id="e_descripcion" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
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
// Cargar datos en el modal de edición
function cargarDatosEdicion(idCategoria, nombre, descripcion, idEstado) {
    document.getElementById('e_idCategoria').value = idCategoria;
    document.getElementById('e_nombre').value = nombre;
    document.getElementById('e_descripcion').value = descripcion;
    document.getElementById('e_estado').value = idEstado;
}

// Buscador dinámico en tiempo real
document.getElementById('inputBuscar').addEventListener('keyup', function() {
    let valor = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tablaCategorias tbody tr');
    
    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(valor) ? '' : 'none';
    });
});
</script>