<?php
require_once '../Controlador/UsuarioControlador.php';
require_once '../Controlador/empleadoControlador.php';

$controladorUser = new UsuarioControlador();
$controladorEmp  = new EmpleadoControlador();

// Obtener listas
$usuarios  = $controladorUser->listar() ?? [];
$empleados = method_exists($controladorEmp, 'listar') ? ($controladorEmp->listar() ?? []) : [];

// PROCESAR GUARDAR NUEVO USUARIO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_guardar'])) {
    $idTrabajador = $_POST['idEmpleado'];
    $username     = $_POST['usuario'];
    $clave        = $_POST['clave'];

    $resultado = $controladorUser->registrar($idTrabajador, $username, $clave);
    
    if ($resultado === "exito") {
        echo "<script>window.location.href='menu.php?page=usuarios&success=1';</script>";
        exit;
    } else {
        echo "<script>alert('Error al registrar usuario: " . $resultado . "');</script>";
    }
}

// PROCESAR EDITAR USUARIO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_editar'])) {
    $idUsuario = $_POST['idUsuario'];
    $username  = $_POST['usuario'];
    $clave     = $_POST['clave'] ?? '';
    $idEstado  = $_POST['idEstado'];

    $controladorUser->actualizar($idUsuario, $username, $clave, $idEstado);
    echo "<script>window.location.href='menu.php?page=usuarios&success=2';</script>";
    exit;
}
?>

<div class="container-fluid pt-4 px-4 pb-5">
    
    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                <i class="bi bi-shield-lock-fill fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Gestión de Usuarios</h4>
                <span class="text-muted small">Administración de credenciales y accesos al sistema</span>
            </div>
        </div>
    </div>

    <!-- ALERTAS -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> 
            <?= $_GET['success'] == '1' ? 'Cuenta de usuario creada exitosamente.' : 'Cuenta de usuario actualizada exitosamente.' ?>
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
                        <input type="text" id="inputBuscar" class="form-control bg-light border-start-0" placeholder="Buscar usuario o empleado...">
                    </div>
                </div>
                <div class="col-md-7 text-end">
                    <button class="btn btn-primary shadow-sm rounded-pill px-4 py-2 fw-medium d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalNuevo">
                        <i class="bi bi-person-plus-fill fs-5"></i> Crear Cuenta
                    </button>
                </div>
            </div>

            <!-- TABLA -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaUsuarios">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Empleado</th>
                            <th>Nombre de Usuario</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $u): ?>
                                <?php 
                                    $valEstado = $u['idEstado'] ?? 1;
                                    $esInactivo = ($valEstado == 0 || $valEstado == 2); 
                                    $nombreEmpleado = trim(($u['nombres'] ?? '') . ' ' . ($u['apellidos'] ?? ''));
                                ?>
                                <tr class="<?= $esInactivo ? 'opacity-50' : '' ?>">
                                    <td class="ps-3 fw-medium text-muted"><?= htmlspecialchars($u['idUsuario'] ?? '') ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($nombreEmpleado ?: 'Empleado #' . ($u['idTrabajador'] ?? '-')) ?></div>
                                        <?php if (!empty($u['dni'])): ?>
                                            <small class="text-muted">DNI: <?= htmlspecialchars($u['dni']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">
                                            <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($u['username'] ?? '') ?>
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
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditar"
                                                onclick="cargarDatosEdicion(
                                                    '<?= $u['idUsuario'] ?? '' ?>', 
                                                    '<?= htmlspecialchars($u['username'] ?? '', ENT_QUOTES) ?>', 
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
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> No hay usuarios registrados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: REGISTRAR NUEVO USUARIO -->
<div class="modal fade" id="modalNuevo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Crear Cuenta de Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" onsubmit="return validarClavesNuevo();">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Seleccionar Empleado</label>
                            <?php if (!empty($empleados)): ?>
                                <select name="idEmpleado" class="form-select" required>
                                    <option value="" selected disabled>-- Seleccione un empleado --</option>
                                    <?php foreach ($empleados as $e): ?>
                                        <option value="<?= $e['idEmpleado'] ?>">
                                            <?= htmlspecialchars($e['nombres'] . ' ' . $e['apellidos']) ?> (DNI: <?= htmlspecialchars($e['dni']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <input type="number" name="idEmpleado" class="form-control" placeholder="Código / ID Trabajador" required>
                            <?php endif; ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Nombre de Usuario (Username)</label>
                            <input type="text" name="usuario" class="form-control" placeholder="Ej. jperez" required autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Contraseña</label>
                            <input type="password" name="clave" id="clave_nuevo" class="form-control" placeholder="******" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Confirmar Contraseña</label>
                            <input type="password" id="clave_confirmar" class="form-control" placeholder="******" required>
                        </div>
                        <div class="col-12">
                            <div id="error_clave_nuevo" class="text-danger small d-none"><i class="bi bi-exclamation-triangle-fill me-1"></i> Las contraseñas no coinciden.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn_guardar" class="btn btn-primary rounded-pill px-4">Registrar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: EDITAR USUARIO -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Cuenta de Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" onsubmit="return validarClavesEditar();">
                <div class="modal-body p-4">
                    <input type="hidden" name="idUsuario" id="e_idUsuario">

                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-gear me-1"></i> Información General</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Nombre de Usuario</label>
                            <input type="text" name="usuario" id="e_usuario" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Estado</label>
                            <select name="idEstado" id="e_estado" class="form-select">
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-1"><i class="bi bi-key-fill me-1"></i> Cambio de Contraseña</h6>
                    <p class="text-muted small mb-3">Deja estos campos vacíos si no deseas modificar la contraseña actual.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nueva Contraseña</label>
                            <input type="password" name="clave" id="e_clave" class="form-control" placeholder="Opcional">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                            <input type="password" id="e_clave_confirmar" class="form-control" placeholder="Opcional">
                        </div>
                        <div class="col-12">
                            <div id="error_clave_editar" class="text-danger small d-none"><i class="bi bi-exclamation-triangle-fill me-1"></i> Las nuevas contraseñas no coinciden.</div>
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
function cargarDatosEdicion(idUsuario, username, idEstado) {
    document.getElementById('e_idUsuario').value = idUsuario;
    document.getElementById('e_usuario').value = username;
    document.getElementById('e_estado').value = idEstado;
    
    document.getElementById('e_clave').value = '';
    document.getElementById('e_clave_confirmar').value = '';
    document.getElementById('error_clave_editar').classList.add('d-none');
}

function validarClavesNuevo() {
    let pass1 = document.getElementById('clave_nuevo').value;
    let pass2 = document.getElementById('clave_confirmar').value;
    let error = document.getElementById('error_clave_nuevo');

    if (pass1 !== pass2) {
        error.classList.remove('d-none');
        return false;
    }
    error.classList.add('d-none');
    return true;
}

function validarClavesEditar() {
    let pass1 = document.getElementById('e_clave').value;
    let pass2 = document.getElementById('e_clave_confirmar').value;
    let error = document.getElementById('error_clave_editar');

    if (pass1 !== '' || pass2 !== '') {
        if (pass1 !== pass2) {
            error.classList.remove('d-none');
            return false;
        }
    }
    error.classList.add('d-none');
    return true;
}

document.getElementById('inputBuscar').addEventListener('keyup', function() {
    let valor = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tablaUsuarios tbody tr');
    
    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(valor) ? '' : 'none';
    });
});
</script>