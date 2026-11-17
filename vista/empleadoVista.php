<?php
require_once '../Controlador/empleadoControlador.php';
$controladorEmp = new EmpleadoControlador();

// Obtener lista de empleados
$empleados = $controladorEmp->listar() ?? [];

// PROCESAR GUARDAR NUEVO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_guardar'])) {
    $controladorEmp->guardar(
        $_POST['nombres'], 
        $_POST['apellidos'], 
        $_POST['dni'], 
        $_POST['telefono'], 
        $_POST['email'], 
        $_POST['tipo']
    );
    echo "<script>window.location.href='menu.php?page=empleados&success=1';</script>";
    exit;
}

// PROCESAR EDITAR
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_editar'])) {
    $controladorEmp->actualizar(
        $_POST['idEmpleado'], 
        $_POST['idPersona'], 
        $_POST['nombres'], 
        $_POST['apellidos'], 
        $_POST['dni'], 
        $_POST['telefono'], 
        $_POST['email'], 
        $_POST['tipo'], 
        $_POST['idEstado']
    );
    echo "<script>window.location.href='menu.php?page=empleados&success=2';</script>";
    exit;
}
?>

<div class="container-fluid pt-4 px-4 pb-5">
    
    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                <i class="bi bi-people-fill fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Empleados</h4>
                <span class="text-muted small">Directorio y accesos del personal</span>
            </div>
        </div>
    </div>

    <!-- ALERTAS -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> 
            <?= $_GET['success'] == '1' ? 'Empleado registrado exitosamente.' : 'Empleado actualizado exitosamente.' ?>
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
                        <input type="text" id="inputBuscar" class="form-control bg-light border-start-0" placeholder="Buscar empleado por nombre o cargo...">
                    </div>
                </div>
                <div class="col-md-7 text-end">
                    <button class="btn btn-primary shadow-sm rounded-pill px-4 py-2 fw-medium d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalNuevo">
                        <i class="bi bi-plus-lg fs-5"></i> Nuevo Empleado
                    </button>
                </div>
            </div>

            <!-- TABLA -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaEmpleados">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>DNI</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Cargo</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($empleados)): ?>
                            <?php foreach ($empleados as $e): ?>
                                <?php 
                                    $valEstado = $e['idEstado'] ?? $e['estado'] ?? 1;
                                    // Evalúa como inactivo si es igual a 0 ó a 2
                                    $esInactivo = ($valEstado == 0 || $valEstado == 2); 
                                ?>
                                <tr class="<?= $esInactivo ? 'opacity-50' : '' ?>">
                                    <td class="ps-3 fw-medium text-muted"><?= htmlspecialchars($e['idEmpleado'] ?? '') ?></td>
                                    <td class="fw-bold text-dark"><?= htmlspecialchars($e['nombres'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($e['apellidos'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($e['dni'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($e['telefono'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($e['email'] ?? '') ?></td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2 py-1">
                                            <?= htmlspecialchars($e['tipo'] ?? '') ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($esInactivo): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-2 py-1">Inactivo</span>
                                        <?php else: ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-1">Activo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center pe-3">
                                        <button class="btn btn-sm btn-outline-primary rounded-circle" 
                                                data-bs-toggle="modal" data-bs-target="#modalEditar"
                                                onclick="cargarDatosEdicion(
                                                    '<?= $e['idEmpleado'] ?? '' ?>', 
                                                    '<?= $e['idPersona'] ?? '' ?>', 
                                                    '<?= htmlspecialchars($e['nombres'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($e['apellidos'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($e['dni'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($e['telefono'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($e['email'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($e['tipo'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= $valEstado ?>'
                                                )">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> No hay empleados registrados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: REGISTRAR NUEVO EMPLEADO -->
<div class="modal fade" id="modalNuevo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2"></i>Registrar Empleado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombres</label>
                            <input type="text" name="nombres" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">DNI</label>
                            <input type="text" name="dni" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Cargo</label>
                            <input type="text" name="tipo" class="form-control" required placeholder="Ej. Administrador">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn_guardar" class="btn btn-primary rounded-pill px-4">Agregar Empleado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: EDITAR EMPLEADO -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Empleado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="idEmpleado" id="e_idEmpleado">
                    <input type="hidden" name="idPersona" id="e_idPersona">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombres</label>
                            <input type="text" name="nombres" id="e_nombres" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Apellidos</label>
                            <input type="text" name="apellidos" id="e_apellidos" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">DNI</label>
                            <input type="text" name="dni" id="e_dni" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Teléfono</label>
                            <input type="text" name="telefono" id="e_telefono" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Cargo</label>
                            <input type="text" name="tipo" id="e_tipo" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" id="e_email" class="form-control">
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

<script>
function cargarDatosEdicion(idEmpleado, idPersona, nombres, apellidos, dni, telefono, email, tipo, idEstado) {
    document.getElementById('e_idEmpleado').value = idEmpleado;
    document.getElementById('e_idPersona').value = idPersona;
    document.getElementById('e_nombres').value = nombres;
    document.getElementById('e_apellidos').value = apellidos;
    document.getElementById('e_dni').value = dni;
    document.getElementById('e_telefono').value = telefono;
    document.getElementById('e_email').value = email;
    document.getElementById('e_tipo').value = tipo;
    
    // Selecciona el estado actual correctamente en el combobox
    document.getElementById('e_estado').value = idEstado;
}

document.getElementById('inputBuscar').addEventListener('keyup', function() {
    let valor = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tablaEmpleados tbody tr');
    
    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(valor) ? '' : 'none';
    });
});
</script>