<?php
require_once "../Controlador/ProveedorControlador.php";
$control = new ProveedorControlador();

// PROCESAR GUARDAR NUEVO PROVEEDOR
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_guardar'])) {
    $nombres       = $_POST["nombres"] ?? '';
    $apellidos     = $_POST["apellidos"] ?? '';
    $dni           = $_POST["dni"] ?? ''; 
    $telefono      = $_POST["telefono"] ?? '';
    $email         = $_POST["email"] ?? ''; 
    $razonSocial   = $_POST["razonSocial"] ?? '';
    $ruc           = $_POST["ruc"] ?? '';
    $direccion     = $_POST["direccion"] ?? '';
    $tipoProductos = $_POST["tipoProductos"] ?? '';

    if ($control->guardar($nombres, $apellidos, $dni, $telefono, $email, $razonSocial, $ruc, $direccion, $tipoProductos)) {
        echo "<script>window.location.href='menu.php?page=proveedor&success=1';</script>";
        exit; 
    }
}

// PROCESAR EDITAR PROVEEDOR
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_editar'])) {
    $idProveedor   = $_POST["idProveedor"] ?? 0;
    $idPersona     = $_POST["idPersona"] ?? 0;
    $nombres       = $_POST["nombres"] ?? '';
    $apellidos     = $_POST["apellidos"] ?? '';
    $dni           = $_POST["dni"] ?? ''; 
    $telefono      = $_POST["telefono"] ?? '';
    $email         = $_POST["email"] ?? ''; 
    $razonSocial   = $_POST["razonSocial"] ?? '';
    $ruc           = $_POST["ruc"] ?? '';
    $direccion     = $_POST["direccion"] ?? '';
    $tipoProductos = $_POST["tipoProductos"] ?? '';
    $idEstado      = $_POST["idEstado"] ?? 1;

    if (method_exists($control, 'actualizar')) {
        $control->actualizar($idProveedor, $idPersona, $nombres, $apellidos, $dni, $telefono, $email, $razonSocial, $ruc, $direccion, $tipoProductos, $idEstado);
    }
    
    echo "<script>window.location.href='menu.php?page=proveedor&success=2';</script>";
    exit;
}

$lista = $control->listar() ?? [];
?>

<div class="container-fluid pt-4 px-4 pb-5">
    
    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                <i class="bi bi-truck fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Gestión de Proveedores</h4>
                <span class="text-muted small">Directorio de empresas y datos de contacto</span>
            </div>
        </div>
    </div>

    <!-- ALERTAS -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> 
            <?= $_GET['success'] == '1' ? 'Proveedor registrado exitosamente.' : 'Proveedor actualizado exitosamente.' ?>
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
                        <input type="text" id="inputBuscar" class="form-control bg-light border-start-0" placeholder="Buscar proveedor por empresa, RUC o contacto...">
                    </div>
                </div>
                <div class="col-md-7 text-end">
                    <button class="btn btn-primary shadow-sm rounded-pill px-4 py-2 fw-medium d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalNuevo">
                        <i class="bi bi-plus-lg fs-5"></i> Nuevo Proveedor
                    </button>
                </div>
            </div>

            <!-- TABLA -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaProv">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Empresa / RUC</th>
                            <th>Rubro</th>
                            <th>Contacto</th>
                            <th>Teléfono / Email</th>
                            <th>Dirección</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($lista)): ?>
                            <?php foreach ($lista as $fila): ?>
                                <?php 
                                    $valEstado = $fila['idEstado'] ?? $fila['estado'] ?? 1;
                                    $esInactivo = ($valEstado == 0 || $valEstado == 2); 
                                    $nombreContacto = trim(($fila['nombres'] ?? '') . ' ' . ($fila['apellidos'] ?? ''));
                                ?>
                                <tr class="<?= $esInactivo ? 'opacity-50' : '' ?>">
                                    <td class="ps-3 fw-medium text-muted"><?= htmlspecialchars($fila['idProveedor'] ?? '') ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($fila['razonSocial'] ?? '') ?></div>
                                        <small class="text-muted"><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-2">RUC</span> <?= htmlspecialchars($fila['ruc'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill px-2 py-1">
                                            <?= htmlspecialchars($fila['tipoProductos'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark"><?= htmlspecialchars($nombreContacto ?: '-') ?></div>
                                        <?php if (!empty($fila['dni'])): ?>
                                            <small class="text-muted">DNI: <?= htmlspecialchars($fila['dni']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div><?= htmlspecialchars($fila['telefono'] ?? '-') ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($fila['email'] ?? '') ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($fila['direccion'] ?? '-') ?></td>
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
                                                    '<?= $fila['idProveedor'] ?? '' ?>', 
                                                    '<?= $fila['idPersona'] ?? '' ?>', 
                                                    '<?= htmlspecialchars($fila['razonSocial'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($fila['ruc'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($fila['direccion'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($fila['tipoProductos'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($fila['nombres'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($fila['apellidos'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($fila['dni'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($fila['telefono'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= htmlspecialchars($fila['email'] ?? '', ENT_QUOTES) ?>', 
                                                    '<?= $valEstado ?>'
                                                )">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> No hay proveedores registrados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: REGISTRAR NUEVO PROVEEDOR -->
<div class="modal fade" id="modalNuevo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-building-add me-2"></i>Registrar Proveedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-building me-1"></i> Datos de la Empresa</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Razón Social</label>
                            <input type="text" name="razonSocial" class="form-control" placeholder="Ej. Distribuidora Lima SAC" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">RUC</label>
                            <input type="text" name="ruc" class="form-control" placeholder="Ej. 20123456789" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Rubro / Tipo Productos</label>
                            <input type="text" name="tipoProductos" class="form-control" placeholder="Ej. Cemento y fierro">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dirección</label>
                            <input type="text" name="direccion" class="form-control" placeholder="Ej. Av. Industrial 100">
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-badge me-1"></i> Datos de Contacto</h6>
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
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn_guardar" class="btn btn-primary rounded-pill px-4">Agregar Proveedor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: EDITAR PROVEEDOR -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Proveedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="idProveedor" id="e_idProveedor">
                    <input type="hidden" name="idPersona" id="e_idPersona">

                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-building me-1"></i> Datos de la Empresa</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Razón Social</label>
                            <input type="text" name="razonSocial" id="e_razonSocial" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">RUC</label>
                            <input type="text" name="ruc" id="e_ruc" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Rubro / Tipo Productos</label>
                            <input type="text" name="tipoProductos" id="e_tipoProductos" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dirección</label>
                            <input type="text" name="direccion" id="e_direccion" class="form-control">
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-badge me-1"></i> Datos de Contacto</h6>
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

<!-- SCRIPTS -->
<script>
function cargarDatosEdicion(idProveedor, idPersona, razonSocial, ruc, direccion, tipoProductos, nombres, apellidos, dni, telefono, email, idEstado) {
    document.getElementById('e_idProveedor').value = idProveedor;
    document.getElementById('e_idPersona').value = idPersona;
    document.getElementById('e_razonSocial').value = razonSocial;
    document.getElementById('e_ruc').value = ruc;
    document.getElementById('e_direccion').value = direccion;
    document.getElementById('e_tipoProductos').value = tipoProductos;
    document.getElementById('e_nombres').value = nombres;
    document.getElementById('e_apellidos').value = apellidos;
    document.getElementById('e_dni').value = dni;
    document.getElementById('e_telefono').value = telefono;
    document.getElementById('e_email').value = email;
    document.getElementById('e_estado').value = idEstado;
}

document.getElementById('inputBuscar').addEventListener('keyup', function() {
    let valor = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tablaProv tbody tr');
    
    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(valor) ? '' : 'none';
    });
});
</script>