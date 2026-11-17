<?php
require_once '../Controlador/ClienteControlador.php';
$controlador = new ClienteControlador();

// Procesar el formulario de guardado nuevo
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_guardar_cliente'])) {
    $controlador->guardarCliente($_POST['tipoDoc'], $_POST['numeroDoc'], $_POST['nombres'], $_POST['direccion'], $_POST['telefono']);
    echo "<script>window.location.href='menu.php?page=clientes&success=1';</script>";
    exit;
}

// Procesar el formulario de edición
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_editar_cliente'])) {
    $controlador->actualizarCliente($_POST['idCliente'], $_POST['tipoDoc'], $_POST['numeroDoc'], $_POST['nombres'], $_POST['direccion'], $_POST['telefono']);
    echo "<script>window.location.href='menu.php?page=clientes&success=2';</script>";
    exit;
}

$clientes = $controlador->listarClientes() ?? [];
?>

<div class="container-fluid pt-4 px-4 pb-5">
    
    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                <i class="bi bi-people-fill fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Gestión de Clientes</h4>
                <span class="text-muted small">Directorio y registro de clientes</span>
            </div>
        </div>
    </div>

    <!-- ALERTAS -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> 
            <?= $_GET['success'] == '1' ? 'Cliente registrado exitosamente.' : 'Cliente actualizado exitosamente.' ?>
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
                        <input type="text" id="inputBuscar" class="form-control bg-light border-start-0" placeholder="Buscar cliente por nombre o documento...">
                    </div>
                </div>
                <div class="col-md-7 text-end">
                    <button class="btn btn-primary shadow-sm rounded-pill px-4 py-2 fw-medium d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalNuevoClienteModulo">
                        <i class="bi bi-plus-lg fs-5"></i> Nuevo Cliente
                    </button>
                </div>
            </div>

            <!-- TABLA -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaClientes">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Documento</th>
                            <th>Nombres / Razón Social</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th class="text-center pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($clientes)): ?>
                            <?php foreach($clientes as $c): ?>
                                <tr>
                                    <td class="ps-3 fw-medium text-muted"><?= $c['idCliente'] ?></td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-2 px-2 py-1 me-1">
                                            <?= htmlspecialchars($c['tipoDoc']) ?>
                                        </span> 
                                        <span class="fw-medium text-dark"><?= htmlspecialchars($c['numeroDoc']) ?></span>
                                    </td>
                                    <td class="fw-bold text-dark"><?= htmlspecialchars($c['nombres']) ?></td>
                                    <td><?= htmlspecialchars($c['telefono'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($c['direccion'] ?? '-') ?></td>
                                    <td class="text-center pe-3">
                                        <!-- BOTÓN EDITAR -->
                                        <button class="btn btn-sm btn-outline-primary rounded-circle" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditarCliente"
                                                onclick="cargarDatosEdicion(
                                                    '<?= $c['idCliente'] ?>', 
                                                    '<?= htmlspecialchars($c['tipoDoc'], ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($c['numeroDoc'], ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($c['nombres'], ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($c['telefono'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($c['direccion'] ?? '', ENT_QUOTES) ?>'
                                                )">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> No hay clientes registrados.
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
<!-- MODAL: REGISTRAR NUEVO CLIENTE -->
<!-- ============================================== -->
<div class="modal fade" id="modalNuevoClienteModulo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2"></i>Registrar Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tipo Documento</label>
                            <select name="tipoDoc" class="form-select" required>
                                <option value="DNI">DNI</option>
                                <option value="RUC">RUC</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Número de Documento</label>
                            <input type="number" name="numeroDoc" class="form-control" required placeholder="Ingrese DNI o RUC">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Nombres o Razón Social</label>
                            <input type="text" name="nombres" class="form-control" required placeholder="Nombre completo o nombre de la empresa">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" placeholder="Ej. 987654321">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dirección</label>
                            <input type="text" name="direccion" class="form-control" placeholder="Ej. Av. Principal 123">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn_guardar_cliente" class="btn btn-primary rounded-pill px-4">Guardar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL: EDITAR CLIENTE -->
<!-- ============================================== -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="idCliente" id="edit_idCliente">
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tipo Documento</label>
                            <select name="tipoDoc" id="edit_tipoDoc" class="form-select" required>
                                <option value="DNI">DNI</option>
                                <option value="RUC">RUC</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Número de Documento</label>
                            <input type="number" name="numeroDoc" id="edit_numeroDoc" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Nombres o Razón Social</label>
                            <input type="text" name="nombres" id="edit_nombres" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Teléfono</label>
                            <input type="text" name="telefono" id="edit_telefono" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dirección</label>
                            <input type="text" name="direccion" id="edit_direccion" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn_editar_cliente" class="btn btn-primary rounded-pill px-4">Actualizar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script>
// Cargar datos en el modal de edición
function cargarDatosEdicion(id, tipo, doc, nom, tel, dir) {
    document.getElementById('edit_idCliente').value = id;
    document.getElementById('edit_tipoDoc').value = tipo;
    document.getElementById('edit_numeroDoc').value = doc;
    document.getElementById('edit_nombres').value = nom;
    document.getElementById('edit_telefono').value = tel;
    document.getElementById('edit_direccion').value = dir;
}

// Buscador dinámico en tiempo real
document.getElementById('inputBuscar').addEventListener('keyup', function() {
    let valor = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tablaClientes tbody tr');
    
    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(valor) ? '' : 'none';
    });
});
</script>